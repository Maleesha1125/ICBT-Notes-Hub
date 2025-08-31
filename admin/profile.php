<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../includes/admin-header.php';

$auth = new Auth($db);
if (!$auth->hasRole('admin')) {
    header('Location: ../login.php');
    exit;
}

$user_class = new User($db);
$user_id = $_SESSION['user_id'];
$user = $user_class->getUserById($user_id);

if (!$user) {
    echo "User not found.";
    exit;
}

$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset_password') {
    $new_password = bin2hex(random_bytes(8));
    if ($user_class->resetPassword($user_id, $new_password)) {
        $message = "Your password has been reset to: " . $new_password;
    } else {
        $error = "Error resetting your password.";
    }
}
?>
<div class="container-fluid">
    <div class="admin-header mb-4">
        <h2>Admin Profile</h2>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">My Details</h5>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Password Management</h5>
            <p>For security, you may reset your password. A new, randomly generated password will be provided.</p>
            <form method="POST" action="profile.php" onsubmit="return confirm('Are you sure you want to reset your password? This will generate a new, random password.');">
                <input type="hidden" name="action" value="reset_password">
                <button type="submit" class="btn btn-warning professional-btn-warning">
                    <i class="fas fa-key"></i> Reset My Password
                </button>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>