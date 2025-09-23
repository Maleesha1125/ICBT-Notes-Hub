<?php
class Module {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    public function createModule($name, $code, $description, $program_id) {
        $query = "INSERT INTO modules (name, code, description, program_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssi", $name, $code, $description, $program_id);
        return $stmt->execute();
    }
    public function updateModule($id, $name, $code, $description, $program_id) {
        $query = "UPDATE modules SET name = ?, code = ?, description = ?, program_id = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssii", $name, $code, $description, $program_id, $id);
        return $stmt->execute();
    }
    public function deleteModule($id) {
        $query = "DELETE FROM modules WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    public function getAllModules() {
        $query = "SELECT m.*, p.name AS program_name FROM modules m LEFT JOIN programs p ON m.program_id = p.id ORDER BY m.name ASC";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getModuleById($id) {
        $query = "SELECT m.*, p.name AS program_name FROM modules m LEFT JOIN programs p ON m.program_id = p.id WHERE m.id = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getModulesByProgram($program_id) {
        $query = "SELECT * FROM modules WHERE program_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $program_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getModulesByProgramIdAndSearch($program_id, $search_term) {
        $search = "%{$search_term}%";
        $query = "SELECT * FROM modules WHERE program_id = ? AND (name LIKE ? OR code LIKE ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iss", $program_id, $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getModulesForAjax($program_id) {
        $query = "SELECT id, name, code FROM modules WHERE program_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $program_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getStudentsByModule($module_id) {
        $query = "SELECT u.id, u.firstname, u.lastname FROM users u JOIN user_modules um ON u.id = um.user_id WHERE um.module_id = ? AND u.role = 'student'";
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            error_log("SQL Error in getStudentsByModule: " . $this->db->error);
            return [];
        }
        $stmt->bind_param("i", $module_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>