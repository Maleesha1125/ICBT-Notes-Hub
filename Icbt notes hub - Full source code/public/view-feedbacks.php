<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Feedback.php';
$feedback_class = new Feedback($db);
$feedbacks = $feedback_class->getAllFeedbacks();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedbacks</title>
    <link rel="stylesheet" href="../assets/css/view-feedbacks-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<a href="javascript:history.back()" class="back-button">
    <i class="fas fa-arrow-left"></i> Back
</a>
<div class="container">
    <div class="page-header">
        <h4><i class="fas fa-comments"></i> Feedbacks</h4>
        <p>This page displays all submitted feedbacks.</p>
    </div>
    <div class="feedback-grid">
        <?php if (empty($feedbacks)): ?>
            <div class="feedback-card empty-state">
                <p>No feedback has been submitted yet.</p>
            </div>
        <?php else: ?>
            <?php foreach ($feedbacks as $feedback): ?>
                <div class="feedback-card">
                    <div class="feedback-content">
                        <p class="feedback-text"><?php echo htmlspecialchars($feedback['feedback']); ?></p>
                        <div class="feedback-meta">
                            <span class="rating"><i class="fas fa-star"></i> Rating: <?php echo htmlspecialchars($feedback['rating']); ?>/5</span>
                            <span class="date"><i class="fas fa-calendar-alt"></i> Submitted on: <?php echo htmlspecialchars($feedback['date']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>