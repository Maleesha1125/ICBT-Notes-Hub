<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Notification.php';
if ($_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}
$notification_class = new Notification($db);
$student_id = $_SESSION['user_id'];
$notifications = $notification_class->getAllNotifications($student_id);
$notification_class->markAllAsRead($student_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notifications</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/view-notifications-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container notifications-container">
    <div class="notifications-card">
        <h2><i class="fas fa-bell"></i> My Notifications</h2>
        <a href="dashboard_student.php" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <hr>
        <?php if (!empty($notifications)): ?>
            <ul class="notifications-list">
                <?php foreach ($notifications as $notification): ?>
                    <li class="<?php echo $notification['is_read'] ? 'read' : 'unread'; ?>">
                        <strong><i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($notification['title']); ?>:</strong>
                        <p><?php echo nl2br(htmlspecialchars($notification['message'])); ?></p>
                        <span class="notification-date"><i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($notification['created_at']); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p><i class="fas fa-info-circle"></i> No new notifications.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>