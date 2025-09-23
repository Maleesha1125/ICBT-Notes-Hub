<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../includes/lecturer_header.php';

$auth = new Auth($db);
$user_class = new User($db);

$error = '';
$message = '';
$message_from_change_password = $_GET['message'] ?? '';

if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'lecturer' && (!isset($_SESSION['first_login']) || $_SESSION['first_login'] != 1)) {
    header('Location: dashboard_lecturer.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $user = $user_class->getUserByUsername($username);

    if ($user) {
        if (strtolower($user['status']) === 'inactive') {
            $error = "Your account is not active. Please contact an administrator.";
        } elseif (strtolower($user['role']) === 'lecturer') {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['full_name'] = $user['firstname'] . ' ' . $user['lastname'];

                if (strtolower($user['registration_method']) === 'admin' && $user['first_login'] == 1) {
                    $_SESSION['first_login'] = 1;
                    $_SESSION['message'] = "Login successful. Please change your password.";
                    header('Location: change-password.php');
                    exit;
                } else {
                    header('Location: dashboard_lecturer.php');
                    exit;
                }
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Access denied. This login is for lecturers only.";
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
    <title>Lecturer Login</title>
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="main-content-wrapper">
    <div class="form-container">
        <div class="login-card">
            <h2><i class="fas fa-chalkboard-teacher"></i> Lecturer Login</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <?php if ($message_from_change_password): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message_from_change_password); ?></div>
            <?php endif; ?>
            <form action="login_lecturer.php" method="POST">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Login</button>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>