<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getUserByUsername($username) {
        $query = "SELECT u.*, p.name AS program_name, d.name AS department_name FROM users u LEFT JOIN programs p ON u.program_id = p.id LEFT JOIN departments d ON u.department_id = d.id WHERE u.username = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function verifyPassword($username, $password) {
        $user = $this->getUserByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function updatePassword($user_id, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = ?, first_login = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $hashed_password, $user_id);
        return $stmt->execute();
    }
    
    public function changePassword($user_id, $current_password, $new_password) {
        $query = "SELECT password FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($current_password, $user['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET password = ? WHERE id = ?";
            $update_stmt = $this->conn->prepare($update_query);
            $update_stmt->bind_param("si", $hashed_password, $user_id);
            return $update_stmt->execute();
        }
        return false;
    }
    
    public function checkUsernameExists($username) {
        $query = "SELECT id FROM users WHERE username = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function registerUser($username, $hashed_password, $firstname, $lastname, $role, $department_id, $program_id, $batch) {
        $query = "INSERT INTO users (username, password, firstname, lastname, role, department_id, program_id, batch, registration_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'self')";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssssiii", $username, $hashed_password, $firstname, $lastname, $role, $department_id, $program_id, $batch);
        return $stmt->execute();
    }
    
    public function setRememberToken($user_id, $token) {
        $query = "UPDATE users SET remember_token = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $token, $user_id);
        return $stmt->execute();
    }

    public function clearRememberToken($user_id) {
        $query = "UPDATE users SET remember_token = NULL WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }

    public function addUserManually($firstname, $lastname, $username, $email, $password, $role, $department_id, $program_id, $mobile) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $status = 'active'; 
        $first_login = 1;

        $stmt = $this->conn->prepare("INSERT INTO users (firstname, lastname, username, email, password, role, department_id, program_id, status, first_login, mobile, registration_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'admin')");
        
        if ($stmt === false) {
            die('MySQL prepare error: ' . $this->conn->error);
        }
        
        $stmt->bind_param("sssssssiisi", $firstname, $lastname, $username, $email, $hashed_password, $role, $department_id, $program_id, $status, $first_login, $mobile);
        
        try {
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return false;
            }
            throw $e;
        }
    }
    
    public function getAllUsers() {
        $query = "SELECT u.id, u.firstname, u.lastname, u.username, u.email, u.role, u.status, d.name AS department_name, p.name AS program_name FROM users u LEFT JOIN departments d ON u.department_id = d.id LEFT JOIN programs p ON u.program_id = p.id";
        $result = $this->conn->query($query);
        return $result;
    }
    public function getUserById($user_id) {
        $query = "SELECT u.*, p.name AS program_name, d.name AS department_name FROM users u LEFT JOIN programs p ON u.program_id = p.id LEFT JOIN departments d ON u.department_id = d.id WHERE u.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function resetPassword($user_id, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password = ?, first_login = 1 WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        return $stmt->execute();
    }
    
    public function updateFirstLoginPassword($user_id, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password = ?, first_login = 0 WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        return $stmt->execute();
    }
    
    public function approveUser($user_id) {
        $stmt = $this->conn->prepare("UPDATE users SET status = 'active' WHERE id = ? AND role = 'lecturer'");
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }

    public function getStudentsByProgram($program_id) {
        $query = "SELECT id, firstname, lastname FROM users WHERE program_id = ? AND role = 'student'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $program_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>