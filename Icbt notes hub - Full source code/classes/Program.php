<?php
class Program {
    private $db;
    private $table_name = "programs";
    public function __construct($db) {
        $this->db = $db;
    }
    public function getAllPrograms() {
        $query = "SELECT id, name FROM " . $this->table_name . " ORDER BY name ASC";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getProgramsByDepartment($department_id) {
        $stmt = $this->db->prepare("SELECT id, name AS program_name FROM " . $this->table_name . " WHERE department_id = ? ORDER BY name ASC");
        $stmt->bind_param("i", $department_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getProgramById($program_id) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $program_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function addProgram($program_name, $program_code, $department_id, $duration, $degree_type) {
        $query = "INSERT INTO " . $this->table_name . " (name, department_id, duration, degree_type) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("siis", $program_name, $department_id, $duration, $degree_type);
        return $stmt->execute();
    }
    public function deleteProgram($program_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $program_id);
        return $stmt->execute();
    }
}
?>