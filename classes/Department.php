<?php
class Department {
    private $db;
    private $table_name = "departments";

    public function __construct($db) {
        $this->db = $db;
    }

    public function addDepartment($name, $code) {
        $sql = "INSERT INTO " . $this->table_name . " (name, code) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("ss", $name, $code);
        return $stmt->execute();
    }

    public function getAllDepartments() {
        $sql = "SELECT id, name, code FROM " . $this->table_name . " ORDER BY name";
        $result = $this->db->query($sql);
        if (!$result) return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDepartmentById($id) {
        $sql = "SELECT id, name, code FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateDepartment($id, $name, $code) {
        $sql = "UPDATE " . $this->table_name . " SET name = ?, code = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssi", $name, $code, $id);
        return $stmt->execute();
    }

    public function deleteDepartment($id) {
        $sql = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>