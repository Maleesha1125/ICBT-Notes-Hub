<?php
require_once __DIR__ . '/../includes/config.php';
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

    $user = $user_class->verifyPassword($username, $password);

    if ($user) {
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
        
        if ($_SESSION['role'] === 'admin') {
            header('Location: ../admin/index.php');
            exit;
        } elseif ($_SESSION['role'] === 'lecturer' && $_SESSION['first_login'] == 1) {
            header('Location: reset-password.php');
            exit;
        } elseif ($_SESSION['role'] === 'lecturer') {
            header('Location: ../lecturer/index.php');
            exit;
        } elseif ($_SESSION['role'] === 'student') {
            $program = $program_class->getProgramById($user['program_id']);
            $_SESSION['program_name'] = $program ? $program['name'] : 'N/A';
            header('Location: dashboard_student.php');
            exit;
        }
    } else {
        $error = "Invalid username or password, or account is not active.";
    }
}
$registration_success = isset($_GET['registration_success']) && $_GET['registration_success'] === 'true';
$new_username = $_GET['username'] ?? '';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <h2>Login</h2>
    <?php if ($registration_success): ?>
        <div class="alert alert-success">
            Registration successful! Your username is: <strong><?php echo htmlspecialchars($new_username); ?></strong>. Please log in.
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
            <label class="form-check-label" for="remember_me">Remember Me</label>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <p class="mt-3">If not a registered student, <a href="register.php">Register here</a></p>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>