<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/User.php';

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/profile-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container-fluid profile-container">
    <div class="admin-header mb-4">
        <h2><i class="fas fa-user-circle"></i> Admin Profile</h2>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <a href="dashboard.php" class="btn btn-secondary professional-btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><i class="fas fa-id-card-alt"></i> My Details</h5>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><i class="fas fa-lock"></i> Password Management</h5>
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
</body>
</html>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>