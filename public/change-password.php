<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/User.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['first_login']) || $_SESSION['first_login'] != 1) {
    header('Location: login_lecturer.php');
    exit;
}
$user_class = new User($db);
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        if ($user_class->updateFirstLoginPassword($_SESSION['user_id'], $new_password)) {
            unset($_SESSION['first_login']);
            $success = "Password changed successfully! Redirecting to dashboard...";
            header('refresh:3; url=../lecturer/index.php');
            exit;
        } else {
            $error = "Failed to change password. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="form-container">
    <h2>Change Your Password</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <form action="change-password.php" method="POST">
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn-primary">Change Password</button>
    </form>
</div>
</body>
</html>