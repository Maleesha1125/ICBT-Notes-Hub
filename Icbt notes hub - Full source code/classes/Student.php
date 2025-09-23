<?php
class Student {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    public function createStudent($user_id, $program_id, $enrollment_date) {
        $query = "INSERT INTO students (user_id, program_id, enrollment_date) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iis", $user_id, $program_id, $enrollment_date);
        return $stmt->execute();
    }
    public function getStudentProfileByUserId($user_id) {
        $query = "SELECT u.id, u.username, u.firstname, u.lastname, u.email, u.batch, p.name AS program_name FROM users u LEFT JOIN programs p ON u.program_id = p.id WHERE u.id = ? AND u.role = 'student'";
        $stmt = $this->db->prepare($query);
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
            return [];
        }
        $stmt->bind_param("i", $program_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getAllStudents() {
        $query = "SELECT u.id, u.username, u.email, u.firstname, u.lastname, u.batch, p.name AS program_name FROM users u LEFT JOIN programs p ON u.program_id = p.id WHERE u.role = 'student'";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>