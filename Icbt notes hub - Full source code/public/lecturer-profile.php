<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/lecturer_header.php';
require_once __DIR__ . '/../classes/Lecturer.php';
if ($_SESSION['role'] !== 'lecturer') {
    header('Location: login.php');
    exit;
}
$lecturer_class = new Lecturer($db);
$lecturer_id = $_SESSION['user_id'];
$lecturer_data = $lecturer_class->getLecturerProfile($lecturer_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Profile</title>
    <link rel="stylesheet" href="../assets/css/stu-lec-profile-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container">
    <div class="profile-card">
        <h2><i class="fas fa-user-circle"></i> Lecturer Profile</h2>
        <hr>
        <?php if ($lecturer_data): ?>
            <div class="profile-details">
                <p><strong><i class="fas fa-user"></i> Username:</strong> <?php echo htmlspecialchars($lecturer_data['username']); ?></p>
                <p><strong><i class="fas fa-envelope"></i> Email:</strong> <?php echo htmlspecialchars($lecturer_data['email']); ?></p>
                <p><strong><i class="fas fa-graduation-cap"></i> Program:</strong> <?php echo htmlspecialchars($lecturer_data['program_name']); ?></p>
                <p><strong><i class="fas fa-calendar-alt"></i> Joined Date:</strong> <?php echo htmlspecialchars($lecturer_data['created_at']); ?></p>
            </div>
            <hr>
            <a href="dashboard_lecturer.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <?php else: ?>
            <div class="alert alert-danger">
                <p>Lecturer data not found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>