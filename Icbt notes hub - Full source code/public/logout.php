<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/User.php';
session_start();
$redirect_page = 'login.php';
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'lecturer') {
        $redirect_page = 'lecturer_welcome.php';
    } elseif ($_SESSION['role'] === 'student') {
        $redirect_page = 'student_welcome.php';
    }
}
if (isset($_COOKIE['remember_me'])) {
    if (isset($_SESSION['user_id'])) {
        $user_class = new User($db);
        $user_class->clearRememberToken($_SESSION['user_id']);
    }
    setcookie('remember_me', '', time() - 3600, "/");
}
$_SESSION = array();
session_destroy();
header('Location: ' . $redirect_page);
exit;
?>