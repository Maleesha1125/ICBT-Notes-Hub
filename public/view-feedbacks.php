<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Feedback.php';
require_once __DIR__ . '/../includes/header.php';
$feedback_class = new Feedback($db);
$feedbacks = $feedback_class->getAllFeedbacks();
?>
<div class="container">
    <h2>Student Feedbacks</h2>
    <div class="feedback-grid">
        <?php if (empty($feedbacks)): ?>
            <p>No feedback has been submitted yet.</p>
        <?php else: ?>
            <?php foreach ($feedbacks as $feedback): ?>
                <div class="feedback-card">
                    <h5>From: <?php echo htmlspecialchars($feedback['name']); ?> (Batch: <?php echo htmlspecialchars($feedback['batch']); ?>)</h5>
                    <p class="department">Department ID: <?php echo htmlspecialchars($feedback['department_id']); ?></p>
                    <p><?php echo htmlspecialchars($feedback['feedback']); ?></p>
                    <div class="feedback-meta">
                        <span class="rating">Rating: <?php echo htmlspecialchars($feedback['rating']); ?>/5</span>
                        <span class="date">Submitted on: <?php echo htmlspecialchars($feedback['date']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>