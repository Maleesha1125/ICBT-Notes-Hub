<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/User.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember_me = isset($_POST['remember_me']);
    if (empty($username) || empty($password)) {
        header("Location: ../public/login.php?error=invalid_credentials");
        exit;
    }
    $user_class = new User($db);
    $user = $user_class->getUserByUsername($username);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['program_id'] = $user['program_id'];
        if ($remember_me) {
            $token = bin2hex(random_bytes(32));
            $user_class->setRememberToken($user['id'], $token);
            setcookie('remember_me', $token, time() + (86400 * 30), "/");
        }
        if ($user['role'] === 'admin') {
            header("Location: ../admin/index.php");
        } else if ($user['role'] === 'lecturer') {
            header("Location: ../public/dashboard_lecturer.php");
        } else {
            header("Location: ../public/dashboard_student.php");
        }
        exit;
    } else {
        header("Location: ../public/login.php?error=invalid_credentials");
        exit;
    }
}
header("Location: ../public/login.php");
exit;