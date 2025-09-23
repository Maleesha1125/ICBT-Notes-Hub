<?php
class LecturerQuiz {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function isLecturerAssignedToModule($lecturer_id, $module_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM modules WHERE id = ? AND lecturer_id = ?");
        $stmt->bind_param("ii", $module_id, $lecturer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    public function addQuestion($quiz_id, $question_text, $question_type, $points, $options, $correct_option_index) {
        $stmt = $this->db->prepare("INSERT INTO quiz_questions (quiz_id, question_text, question_type, points) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $quiz_id, $question_text, $question_type, $points);
        if ($stmt->execute()) {
            $question_id = $this->db->insert_id;
            if ($question_type === 'multiple_choice' || $question_type === 'true_false') {
                foreach ($options as $index => $option_text) {
                    $is_correct = ($index == $correct_option_index) ? 1 : 0;
                    $stmt_opt = $this->db->prepare("INSERT INTO quiz_options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
                    $stmt_opt->bind_param("isi", $question_id, $option_text, $is_correct);
                    $stmt_opt->execute();
                }
            }
            return true;
        }
        return false;
    }

    public function getQuizQuestions($quiz_id) {
        $stmt = $this->db->prepare("SELECT * FROM quiz_questions WHERE quiz_id = ? ORDER BY id ASC");
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getQuestionOptions($question_id) {
        $stmt = $this->db->prepare("SELECT * FROM quiz_options WHERE question_id = ?");
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>