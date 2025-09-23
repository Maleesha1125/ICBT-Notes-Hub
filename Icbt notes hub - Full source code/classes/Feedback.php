<?php
class Feedback {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllFeedbacks() {
        $query = "SELECT
                    f.*,
                    u.firstname,
                    u.lastname,
                    d.name AS department_name,
                    p.name AS program_name
                  FROM feedbacks f
                  JOIN users u ON f.user_id = u.id
                  LEFT JOIN departments d ON f.department_id = d.id
                  LEFT JOIN programs p ON f.program_id = p.id
                  ORDER BY f.date DESC";
        $result = $this->conn->query($query);
        if ($result === false) {
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteFeedback($id) {
        $query = "DELETE FROM feedbacks WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function submitFeedback($user_id, $feedback_text, $rating, $module_id) {
        $query = "INSERT INTO feedbacks (user_id, feedback, rating, module_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issi", $user_id, $feedback_text, $rating, $module_id);
        return $stmt->execute();
    }
}
?>