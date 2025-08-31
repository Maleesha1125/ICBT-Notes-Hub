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
<div class="container">
    <h2>My Notifications</h2>
    <hr>
    <a href="dashboard_student.php" class="btn btn-secondary">Back to Dashboard</a>
    <hr>
    <?php if (!empty($notifications)): ?>
        <ul class="notifications-list">
            <?php foreach ($notifications as $notification): ?>
                <li class="<?php echo $notification['is_read'] ? 'read' : 'unread'; ?>">
                    <strong><?php echo htmlspecialchars($notification['title']); ?>:</strong>
                    <p><?php echo nl2br(htmlspecialchars($notification['message'])); ?></p>
                    <span class="notification-date"><?php echo htmlspecialchars($notification['created_at']); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No new notifications.</p>
    <?php endif; ?>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>