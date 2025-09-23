<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Program.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$user_class = new User($db);
$program_class = new Program($db);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember_me = isset($_POST['remember_me']);

    $user = $user_class->getUserByUsername($username);

    if ($user) {
        if (strtolower($user['status']) === 'inactive') {
            $error = "Your account is not active. Please contact an administrator.";
        } elseif (strtolower($user['role']) === 'student') {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'] ?? $user['firstname'] . ' ' . $user['lastname'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['program_id'] = $user['program_id'];
                $_SESSION['department_id'] = $user['department_id'];
                $_SESSION['batch'] = $user['batch'];
                $_SESSION['first_login'] = $user['first_login'];

                if ($remember_me) {
                    $token = bin2hex(random_bytes(32));
                    $user_class->setRememberToken($user['id'], $token);
                    setcookie('remember_me', $token, time() + (86400 * 30), "/");
                }

                if (strtolower($user['registration_method']) === 'admin' && $user['first_login'] == 1) {
                    $_SESSION['first_login'] = 1;
                    $_SESSION['message'] = "Login successful. Please change your password.";
                    header('Location: change-password.php');
                    exit;
                } else {
                    $program = $program_class->getProgramById($user['program_id']);
                    $_SESSION['program_name'] = $program ? $program['name'] : 'N/A';
                    header('Location: dashboard_student.php');
                    exit;
                }
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Access denied. This login is for students only.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
$registration_success = isset($_GET['registration_success']) && $_GET['registration_success'] === 'true';
$new_username = $_GET['username'] ?? '';
$message_from_change_password = $_GET['message'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<div class="main-content-wrapper">
    <div class="form-container">
        <div class="login-card">
            <h2><i class="fas fa-user-graduate"></i> Student Login</h2>
            <?php if ($registration_success): ?>
                <div class="alert alert-success">
                    Registration successful! Your username is: <strong><?php echo htmlspecialchars($new_username); ?></strong>. Please log in.
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($message_from_change_password): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message_from_change_password); ?></div>
            <?php endif; ?>
            <form method="POST" action="login_student.php">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                    <label class="form-check-label" for="remember_me">Remember Me</label>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Login</button>
            </form>
            <p class="mt-3">If not a registered student, <a href="register.php">Register here</a></p>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>