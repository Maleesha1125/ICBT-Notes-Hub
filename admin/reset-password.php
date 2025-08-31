<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/admin-header.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/User.php';

$auth = new Auth($db);
if (!$auth->hasRole('admin')) {
    header('Location: index.php');
    exit;
}

$user_class = new User($db);
$message = '';
$user_id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$user_id) {
    header('Location: manage-users.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $new_password = $_POST['new_password'];

    if ($user_class->resetPassword($user_id, $new_password)) {
        $message = "Password reset successfully.";
    } else {
        $message = "Error resetting password.";
    }
}
?>
<div class="container">
    <h2>Reset User Password</h2>
    <a href="manage-users.php" class="btn-secondary">Back to Manage Users</a>
    <hr>
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form action="reset-password.php?id=<?php echo htmlspecialchars($user_id); ?>" method="POST">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" required>
        </div>
        <button type="submit" class="btn-primary">Reset Password</button>
    </form>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>