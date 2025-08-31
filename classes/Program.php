<?php
class Program {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    public function getAllPrograms() {
        $query = "SELECT id, name FROM programs ORDER BY name ASC";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getProgramsByDepartment($department_id) {
        $stmt = $this->db->prepare("SELECT id, name FROM programs WHERE department_id = ? ORDER BY name ASC");
        $stmt->bind_param("i", $department_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getProgramById($program_id) {
        $stmt = $this->db->prepare("SELECT * FROM programs WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $program_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function addProgram($program_name, $program_code, $department_id, $duration, $degree_type) {
        $query = "INSERT INTO programs (name, code, department_id, duration, degree_type) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssiis", $program_name, $program_code, $department_id, $duration, $degree_type);
        return $stmt->execute();
    }
    public function deleteProgram($program_id) {
        $query = "DELETE FROM programs WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $program_id);
        return $stmt->execute();
    }
}
?>