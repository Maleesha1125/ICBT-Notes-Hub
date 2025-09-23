<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Student.php';
require_once __DIR__ . '/../classes/Notification.php';
require_once __DIR__ . '/../classes/User.php';
if ($_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}
$student_class = new Student($db);
$notification_class = new Notification($db);
$user_class = new User($db);
$student_id = $_SESSION['user_id'];
$user = $user_class->getUserById($student_id);
$unread_notifications = $notification_class->getUnreadNotifications($student_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard/student-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="main-content-wrapper">
    <div class="dashboard-container">
        <h2><i class="fas fa-tachometer-alt"></i> Welcome, <?php echo htmlspecialchars($user['firstname']); ?>!</h2>
        <div class="notifications-section">
            <h3><i class="fas fa-bell"></i> Notifications (<?php echo count($unread_notifications); ?>)</h3>
            <?php if (!empty($unread_notifications)): ?>
                <ul>
                    <?php foreach ($unread_notifications as $notification): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($notification['title']); ?>:</strong> <?php echo htmlspecialchars($notification['message']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="view-notifications.php" class="btn btn-view"><i class="fas fa-eye"></i> View All Notifications</a>
            <?php else: ?>
                <p>No new notifications.</p>
            <?php endif; ?>
        </div>
        <div class="dashboard-grid">
            <a href="view-modules.php" class="dashboard-card blue-card">
                <i class="fas fa-book"></i>
                <span>View & Upload Content</span>
            </a>
            <a href="view-my-quizzes.php" class="dashboard-card purple-card">
                <i class="fas fa-question-circle"></i>
                <span>My Quizzes</span>
            </a>
            <a href="student-profile.php" class="dashboard-card red-card">
                <i class="fas fa-user-circle"></i>
                <span>View Profile</span>
            </a>
        </div>
    </div>
</div>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>