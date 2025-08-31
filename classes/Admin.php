<?php
class Admin {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getLecturerApprovals() {
        $sql = "SELECT id, firstname, lastname, email FROM users WHERE role = 'lecturer' AND status = 'inactive'";
        $result = $this->db->query($sql);
        return $result;
    }

    public function approveLecturer($user_id) {
        $stmt = $this->db->prepare("UPDATE users SET status = 'active' WHERE id = ? AND role = 'lecturer'");
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }

    public function manageUsers() {
        $sql = "SELECT u.id, u.firstname, u.lastname, u.email, u.role, u.status, d.name AS department_name, p.name AS program_name 
                FROM users u 
                LEFT JOIN departments d ON u.department_id = d.id 
                LEFT JOIN programs p ON u.program_id = p.id";
        
        $result = $this->db->query($sql);
        
        if ($result) {
            return $result;
        } else {
            error_log("SQL Error: " . $this->db->error);
            return false;
        }
    }
    
    public function managePrograms() {
        $sql = "SELECT p.*, d.name AS department_name FROM programs p LEFT JOIN departments d ON p.department_id = d.id";
        $result = $this->db->query($sql);
        if ($result) {
            return $result;
        } else {
            error_log("SQL Error: " . $this->db->error);
            return false;
        }
    }

    public function manageDepartments() {
        $sql = "SELECT * FROM departments";
        $result = $this->db->query($sql);
        if ($result) {
            return $result;
        } else {
            error_log("SQL Error: " . $this->db->error);
            return false;
        }
    }

    public function manageModules() {
        $sql = "SELECT m.*, p.name AS program_name FROM modules m LEFT JOIN programs p ON m.program_id = p.id";
        $result = $this->db->query($sql);
        if ($result) {
            return $result;
        } else {
            error_log("SQL Error: " . $this->db->error);
            return false;
        }
    }

    public function addDepartment($name, $code) {
        $stmt = $this->db->prepare("INSERT INTO departments (name, code) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $code);
        return $stmt->execute();
    }
}
?>