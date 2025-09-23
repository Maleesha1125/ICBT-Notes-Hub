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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset User Password</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/reset-password-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container reset-password-container">
    <div class="card">
        <div class="card-body">
            <h2><i class="fas fa-key"></i> Reset User Password</h2>
            <a href="manage-users.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Manage Users</a>
            <hr>
            <?php if ($message): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form action="reset-password.php?id=<?php echo htmlspecialchars($user_id); ?>" method="POST">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                <div class="form-group">
                    <label for="new_password"><i class="fas fa-lock"></i> New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-redo"></i> Reset Password</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>