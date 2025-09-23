<?php
session_start();
require_once dirname(__FILE__) . '/../includes/config.php';
require_once dirname(__FILE__) . '/../classes/Auth.php';
require_once dirname(__FILE__) . '/../classes/User.php';

$auth = new Auth($db);
$user_class = new User($db);

$error = '';

if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($auth->loginUser($username, $password)) {
        if ($_SESSION['role'] === 'admin') {
            header('Location: dashboard.php');
            exit;
        } else {
            $auth->logout();
            $error = "You do not have administrative access.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - ICBT Notes Hub</title>
    <link rel="stylesheet" href="../assets/css/admin-login-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="form-container">
    <div class="form-card">
        <h2 class="site-title">ICBT Notes Hub</h2>
        <h2 class="login-title"><i class="fas fa-user-shield"></i> Admin Login</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST" class="mt-4">
            <div class="form-group">
                <label for="username"><i class="fas fa-user"></i> Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Login</button>
        </form>
    </div>
</div>
</body>
</html>