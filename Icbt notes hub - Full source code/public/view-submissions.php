<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/lecturer_header.php';
require_once __DIR__ . '/../classes/Quiz.php';
if ($_SESSION['role'] !== 'lecturer') {
    header('Location: login.php');
    exit;
}
$quiz_id = $_GET['quiz_id'] ?? null;
if (!$quiz_id) {
    header('Location: manage-quizzes.php');
    exit;
}
$quiz_class = new Quiz($db);
$quiz_data = $quiz_class->getQuizById($quiz_id);
$submissions = $quiz_class->getQuizSubmissions($quiz_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Submissions</title>
    <link rel="stylesheet" href="../assets/css/view-submissions-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container submissions-container">
    <div class="submissions-card">
        <h2><i class="fas fa-list-alt"></i> Submissions for: <?php echo htmlspecialchars($quiz_data['title']); ?></h2>
        <hr>
        <a href="manage-quizzes.php" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Back to Manage Quizzes</a>
        <hr>
        <?php if (!empty($submissions)): ?>
            <table class="content-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-user-graduate"></i> Student Name</th>
                        <th><i class="fas fa-book-open"></i> Module Name</th>
                        <th><i class="fas fa-star"></i> Score</th>
                        <th><i class="fas fa-clock"></i> Submission Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $submission): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($submission['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($submission['module_name']); ?></td>
                            <td><?php echo htmlspecialchars($submission['score']); ?></td>
                            <td><?php echo htmlspecialchars($submission['submission_time']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><i class="fas fa-info-circle"></i> No submissions found for this quiz yet.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>