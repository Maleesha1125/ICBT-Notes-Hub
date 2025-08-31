<?php
class Quiz {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    public function addQuiz($module_id, $lecturer_id, $title, $description, $duration) {
        $query = "INSERT INTO quizzes (module_id, lecturer_id, title, description, duration) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iissi", $module_id, $lecturer_id, $title, $description, $duration);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }
    public function addQuestion($quiz_id, $question_text, $type, $points) {
        $query = "INSERT INTO quiz_questions (quiz_id, question_text, question_type, points) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("issi", $quiz_id, $question_text, $type, $points);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            die("Execute failed: " . $stmt->error);
        }
    }
    public function addOption($question_id, $option_text, $is_correct) {
        $query = "INSERT INTO quiz_options (question_id, option_text, is_correct) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("isi", $question_id, $option_text, $is_correct);
        if ($stmt->execute()) {
            return true;
        } else {
            die("Execute failed: " . $stmt->error);
        }
    }
    public function getQuizzesByLecturer($lecturer_id) {
        $query = "SELECT q.*, m.name AS module_name FROM quizzes q LEFT JOIN modules m ON q.module_id = m.id WHERE q.lecturer_id = ? GROUP BY q.id ORDER BY q.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $lecturer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getQuizById($quiz_id) {
        $query = "SELECT q.*, m.name AS module_name FROM quizzes q LEFT JOIN modules m ON q.module_id = m.id WHERE q.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getQuizWithQuestions($quiz_id) {
        $quiz = $this->getQuizById($quiz_id);
        if ($quiz) {
            $query = "SELECT * FROM quiz_questions WHERE quiz_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $quiz_id);
            $stmt->execute();
            $questions_result = $stmt->get_result();
            $questions = $questions_result->fetch_all(MYSQLI_ASSOC);
            foreach ($questions as &$question) {
                $options_query = "SELECT * FROM quiz_options WHERE question_id = ?";
                $options_stmt = $this->db->prepare($options_query);
                $options_stmt->bind_param("i", $question['id']);
                $options_stmt->execute();
                $options_result = $options_stmt->get_result();
                $question['options'] = $options_result->fetch_all(MYSQLI_ASSOC);
            }
            $quiz['questions'] = $questions;
        }
        return $quiz;
    }
    public function deleteQuiz($quiz_id) {
        try {
            $this->db->begin_transaction();
            $query_submissions = "SELECT id FROM student_submissions WHERE quiz_id = ?";
            $stmt_submissions = $this->db->prepare($query_submissions);
            if (!$stmt_submissions) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }
            $stmt_submissions->bind_param("i", $quiz_id);
            $stmt_submissions->execute();
            $submissions = $stmt_submissions->get_result()->fetch_all(MYSQLI_ASSOC);
            if (!empty($submissions)) {
                $submission_ids = array_column($submissions, 'id');
                $in_clause = implode(',', array_fill(0, count($submission_ids), '?'));
                $types = str_repeat('i', count($submission_ids));
                $query_answers = "DELETE FROM student_answers WHERE submission_id IN ($in_clause)";
                $stmt_answers = $this->db->prepare($query_answers);
                if (!$stmt_answers) {
                    throw new Exception("Prepare failed: " . $this->db->error);
                }
                $stmt_answers->bind_param($types, ...$submission_ids);
                $stmt_answers->execute();
            }
            $query1 = "DELETE FROM student_submissions WHERE quiz_id = ?";
            $stmt1 = $this->db->prepare($query1);
            if (!$stmt1) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }
            $stmt1->bind_param("i", $quiz_id);
            $stmt1->execute();
            $query2 = "SELECT id FROM quiz_questions WHERE quiz_id = ?";
            $stmt2 = $this->db->prepare($query2);
            if (!$stmt2) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }
            $stmt2->bind_param("i", $quiz_id);
            $stmt2->execute();
            $questions = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
            if (!empty($questions)) {
                $question_ids = array_column($questions, 'id');
                $in_clause = implode(',', array_fill(0, count($question_ids), '?'));
                $types = str_repeat('i', count($question_ids));
                $query3 = "DELETE FROM quiz_options WHERE question_id IN ($in_clause)";
                $stmt3 = $this->db->prepare($query3);
                if (!$stmt3) {
                    throw new Exception("Prepare failed: " . $this->db->error);
                }
                $stmt3->bind_param($types, ...$question_ids);
                $stmt3->execute();
            }
            $query4 = "DELETE FROM quiz_questions WHERE quiz_id = ?";
            $stmt4 = $this->db->prepare($query4);
            if (!$stmt4) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }
            $stmt4->bind_param("i", $quiz_id);
            $stmt4->execute();
            $query5 = "DELETE FROM quizzes WHERE id = ?";
            $stmt5 = $this->db->prepare($query5);
            if (!$stmt5) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }
            $stmt5->bind_param("i", $quiz_id);
            $stmt5->execute();
            $this->db->commit();
            return true;
        } catch (mysqli_sql_exception $e) {
            $this->db->rollback();
            return false;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    public function getQuizSubmissions($quiz_id) {
        $query = "
            SELECT 
                u.username AS student_name, 
                m.name AS module_name, 
                ss.submission_time,
                (SELECT SUM(sa.score_awarded) FROM student_answers sa WHERE sa.submission_id = ss.id) AS score
            FROM student_submissions ss
            JOIN users u ON ss.student_id = u.id
            JOIN quizzes q ON ss.quiz_id = q.id
            JOIN modules m ON q.module_id = m.id
            WHERE ss.quiz_id = ?
            ORDER BY ss.submission_time DESC
        ";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function getQuizScore($student_id, $quiz_id) {
        $query = "
            SELECT SUM(sa.score_awarded) AS score 
            FROM student_answers sa
            JOIN student_submissions ss ON sa.submission_id = ss.id
            WHERE ss.student_id = ? AND ss.quiz_id = ?
        ";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param("ii", $student_id, $quiz_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $score = $result->fetch_assoc();
        return $score['score'] ?? 0;
    }
    public function getTotalPoints($quiz_id) {
        $query = "SELECT SUM(points) AS total_points FROM quiz_questions WHERE quiz_id = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $total_points = $result->fetch_assoc();
        return $total_points['total_points'] ?? 0;
    }
    public function checkSubmission($student_id, $quiz_id) {
        $query = "SELECT COUNT(*) as count FROM student_submissions WHERE student_id = ? AND quiz_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $student_id, $quiz_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $submission = $result->fetch_assoc();
        return $submission['count'] > 0;
    }
    public function submitQuiz($student_id, $quiz_id, $answers) {
        $this->db->begin_transaction();
        try {
            $submission_query = "INSERT INTO student_submissions (student_id, quiz_id, submission_time) VALUES (?, ?, NOW())";
            $submission_stmt = $this->db->prepare($submission_query);
            if (!$submission_stmt) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }
            $submission_stmt->bind_param("ii", $student_id, $quiz_id);
            $submission_stmt->execute();
            $submission_id = $this->db->insert_id;
            foreach ($answers as $question_id => $selected_option_id) {
                $query = "SELECT o.is_correct, qq.points FROM quiz_options o JOIN quiz_questions qq ON o.question_id = qq.id WHERE o.id = ? AND qq.id = ?";
                $stmt = $this->db->prepare($query);
                if (!$stmt) {
                    throw new Exception("Prepare failed: " . $this->db->error);
                }
                $stmt->bind_param("ii", $selected_option_id, $question_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_assoc();
                $is_correct = $data['is_correct'] ?? 0;
                $score_awarded = ($is_correct) ? ($data['points'] ?? 0) : 0;
                $insert_query = "INSERT INTO student_answers (submission_id, question_id, selected_option_id, is_correct, score_awarded) VALUES (?, ?, ?, ?, ?)";
                $insert_stmt = $this->db->prepare($insert_query);
                if (!$insert_stmt) {
                    throw new Exception("Prepare failed: " . $this->db->error);
                }
                $insert_stmt->bind_param("iiiii", $submission_id, $question_id, $selected_option_id, $is_correct, $score_awarded);
                $insert_stmt->execute();
            }
            $this->db->commit();
            return true;
        } catch (mysqli_sql_exception $e) {
            $this->db->rollback();
            throw $e;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}
?>