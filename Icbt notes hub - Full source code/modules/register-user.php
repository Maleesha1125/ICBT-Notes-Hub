<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/User.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = 'student';
    $department_id = $_POST['department_id'] ?? null;
    $program_id = $_POST['program_id'] ?? null;
    $batch = $_POST['batch'] ?? null;

    if (empty($firstname) || empty($lastname) || empty($mobile) || empty($password) || empty($department_id) || empty($program_id)) {
        header("Location: ../public/register.php?error=missing_fields");
        exit;
    }

    $user_class = new User($db);

    $first_letter_of_lastname = substr($lastname, 0, 1);
    $base_username = $firstname . $first_letter_of_lastname;
    $username = $base_username;
    $i = 1;
    while ($user_class->getUserByUsername($username)) {
        $username = $base_username . $i;
        $i++;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if ($user_class->registerUser($username, $hashed_password, $firstname, $lastname, $role, $department_id, $program_id, $batch, $mobile)) {
        $success_message = urlencode("Registration successful! Your username is: " . $username . ". Please use this to log in.");
        header("Location: ../public/login.php?status=success&message=" . $success_message);
        exit;
    } else {
        header("Location: ../public/register.php?error=system_error");
        exit;
    }
}

header("Location: ../public/register.php");
exit;