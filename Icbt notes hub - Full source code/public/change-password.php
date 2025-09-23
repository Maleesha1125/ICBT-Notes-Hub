<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/User.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['first_login']) || $_SESSION['first_login'] != 1) {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'student') {
            header('Location: login_student.php');
            exit;
        } elseif ($_SESSION['role'] === 'lecturer') {
            header('Location: login_lecturer.php');
            exit;
        }
    }
    header('Location: login.php');
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
            if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] === 'student') {
                    header('Location: login_student.php?message=' . urlencode("Password changed successfully! Log with new password"));
                    exit;
                } elseif ($_SESSION['role'] === 'lecturer') {
                    header('Location: login_lecturer.php?message=' . urlencode("Password changed successfully! Log with new password"));
                    exit;
                }
            }
            header('Location: login.php');
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
    <link rel="stylesheet" href="../assets/css/change-password-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="form-container">
    <div class="password-card">
        <h2><i class="fas fa-key"></i> Change Your Password</h2>
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
                <label for="new_password"><i class="fas fa-lock"></i> New Password</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="confirm_password"><i class="fas fa-lock"></i> Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-check-circle"></i> Change Password</button>
        </form>
    </div>
</div>
</body>
</html>