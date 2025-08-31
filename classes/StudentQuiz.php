<?php
class StudentQuiz {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    public function getStudentData($user_id) {
        $query = "SELECT program_id FROM students WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            error_log("SQL Prepare Error in getStudentData: " . $this->db->error);
            return null;
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getQuizzesByProgramModules($program_id) {
        $query = "
            SELECT
                q.id,
                q.title,
                q.duration,
                m.name AS module_name
            FROM quizzes q
            JOIN modules m ON q.module_id = m.id
            WHERE m.program_id = ?
            GROUP BY q.id
        ";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            error_log("SQL Prepare Error in getQuizzesByProgramModules: " . $this->db->error);
            return [];
        }
        $stmt->bind_param("i", $program_id);
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
    public function getQuizScore($student_id, $quiz_id) {
        $query = "
            SELECT SUM(sa.score_awarded) AS score 
            FROM student_answers sa
            JOIN student_submissions ss ON sa.submission_id = ss.id
            WHERE ss.student_id = ? AND ss.quiz_id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $student_id, $quiz_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $score = $result->fetch_assoc();
        return $score['score'] ?? 0;
    }
    public function getTotalPoints($quiz_id) {
        $query = "SELECT SUM(points) AS total_points FROM quiz_questions WHERE quiz_id = ?";
        $stmt = $this->db->prepare($query);
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
            $submission_stmt->bind_param("ii", $student_id, $quiz_id);
            $submission_stmt->execute();
            $submission_id = $this->db->insert_id;
            foreach ($answers as $question_id => $selected_option_id) {
                $query = "SELECT o.is_correct, qq.points FROM quiz_options o JOIN quiz_questions qq ON o.question_id = qq.id WHERE o.id = ? AND qq.id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param("ii", $selected_option_id, $question_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_assoc();
                $is_correct = $data['is_correct'] ?? 0;
                $score_awarded = ($is_correct) ? ($data['points'] ?? 0) : 0;
                $insert_query = "INSERT INTO student_answers (submission_id, question_id, selected_option_id, is_correct, score_awarded) VALUES (?, ?, ?, ?, ?)";
                $insert_stmt = $this->db->prepare($insert_query);
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