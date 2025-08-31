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
    <title>Admin Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="form-container">
    <h2>Admin Login</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn-primary">Login</button>
    </form>
</div>
</body>
</html>