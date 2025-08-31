<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Lecturer.php';
if ($_SESSION['role'] !== 'lecturer') {
    header('Location: login.php');
    exit;
}
$lecturer_class = new Lecturer($db);
$lecturer_id = $_SESSION['user_id'];
$lecturer_data = $lecturer_class->getLecturerProfile($lecturer_id);
?>
<div class="container">
    <h2>Lecturer Profile</h2>
    <hr>
    <?php if ($lecturer_data): ?>
        <div class="profile-details">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($lecturer_data['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($lecturer_data['email']); ?></p>
            <p><strong>Program:</strong> <?php echo htmlspecialchars($lecturer_data['program_name']); ?></p>
            <p><strong>Joined Date:</strong> <?php echo htmlspecialchars($lecturer_data['created_at']); ?></p>
        </div>
        <hr>
        <a href="dashboard_lecturer.php" class="btn btn-primary">Back to Dashboard</a>
    <?php else: ?>
        <div class="alert alert-danger">
            <p>Lecturer data not found.</p>
        </div>
    <?php endif; ?>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>