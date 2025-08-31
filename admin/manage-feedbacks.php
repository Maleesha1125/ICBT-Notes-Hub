<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Feedback.php';
require_once __DIR__ . '/../includes/admin-header.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
$feedback_class = new Feedback($db);
$feedbacks = $feedback_class->getAllFeedbacks();
?>
<div class="container">
    <h2>Manage Feedbacks</h2>
    <?php if (empty($feedbacks)): ?>
        <p>No feedbacks have been submitted yet.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Batch</th>
                    <th>Department</th>
                    <th>Program</th>
                    <th>Feedback</th>
                    <th>Rating</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedbacks as $feedback): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($feedback['id']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($feedback['batch'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($feedback['department_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($feedback['program_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($feedback['feedback']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['rating']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['date']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>