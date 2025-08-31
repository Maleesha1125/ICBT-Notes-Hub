<?php
class Feedback {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllFeedbacks() {
        $query = "SELECT f.*, u.firstname, u.lastname FROM feedbacks f JOIN users u ON f.user_id = u.id ORDER BY f.date DESC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function submitFeedback($user_id, $feedback_text, $rating, $module_id) {
        $query = "INSERT INTO feedbacks (user_id, feedback, rating, module_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issi", $user_id, $feedback_text, $rating, $module_id);
        return $stmt->execute();
    }
}
?>