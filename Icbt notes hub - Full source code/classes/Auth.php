<?php
require_once __DIR__ . '/User.php';

class Auth {
    private $conn;
    private $user_class;

    public function __construct($db) {
        $this->conn = $db;
        $this->user_class = new User($db);
    }

    public function loginUser($username, $password) {
        $user = $this->user_class->getUserByUsername($username);

        if ($user) {
            if ($user['status'] === 'inactive') {
                return 'inactive'; 
            }
            if (password_verify($password, $user['password'])) {
                if ($user['status'] === 'active') {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['full_name'] = $user['firstname'] . ' ' . $user['lastname'];
                    return true;
                }
            }
        }
        return false;
    }

    public function logout() {
        session_unset();
        session_destroy();
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function hasRole($role) {
        return $this->isLoggedIn() && $_SESSION['role'] === $role;
    }
}
?>