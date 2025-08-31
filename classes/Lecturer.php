<?php
class Lecturer {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    public function getLecturerDepartmentId($user_id) {
        $stmt = $this->db->prepare("SELECT department_id FROM users WHERE id = ? AND role = 'lecturer'");
        if (!$stmt) {
            error_log("SQL Error in getLecturerDepartmentId: " . $this->db->error);
            return null;
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user ? $user['department_id'] : null;
    }
    public function getAssignedPrograms($user_id) {
        $sql = "SELECT DISTINCT p.id, p.name FROM programs p JOIN lecturer_modules lm ON p.id = lm.program_id WHERE lm.lecturer_id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("SQL Error in getAssignedPrograms: " . $this->db->error);
            return [];
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getAssignedModules($user_id) {
        $sql = "SELECT m.id, m.name, m.program_id FROM modules m JOIN lecturer_modules lm ON m.id = lm.module_id WHERE lm.lecturer_id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("SQL Error in getAssignedModules: " . $this->db->error);
            return [];
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getModulesByProgram($program_id, $lecturer_id) {
        $sql = "SELECT m.id, m.name FROM modules m JOIN lecturer_modules ml ON m.id = ml.module_id WHERE m.program_id = ? AND ml.lecturer_id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("SQL Error in getModulesByProgram: " . $this->db->error);
            return [];
        }
        $stmt->bind_param("ii", $program_id, $lecturer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getLecturerProfile($user_id) {
        $sql = "SELECT u.id, u.username, u.email, u.created_at, p.name AS program_name FROM users u LEFT JOIN programs p ON u.program_id = p.id WHERE u.id = ? AND u.role = 'lecturer'";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("SQL Error in getLecturerProfile: " . $this->db->error);
            return null;
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getLecturerById($id) {
        $sql = "SELECT u.id, u.name, u.email, u.phone, d.name AS department_name FROM users u JOIN departments d ON u.department_id = d.id WHERE u.id = ? AND u.role = 'lecturer'";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("SQL Error in getLecturerById: " . $this->db->error);
            return null;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function updateProfile($id, $email, $phone) {
        $stmt = $this->db->prepare("UPDATE users SET email = ?, phone = ? WHERE id = ?");
        if (!$stmt) {
            error_log("SQL Error in updateProfile: " . $this->db->error);
            return false;
        }
        $stmt->bind_param("ssi", $email, $phone, $id);
        return $stmt->execute();
    }
    public function isLecturerAssignedToModule($lecturer_id, $module_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM lecturer_modules WHERE lecturer_id = ? AND module_id = ?");
        if (!$stmt) {
            error_log("SQL Error in isLecturerAssignedToModule: " . $this->db->error);
            return false;
        }
        $stmt->bind_param("ii", $lecturer_id, $module_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }
}
?>