<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';
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
<div class="container">
    <h2>Submissions for: <?php echo htmlspecialchars($quiz_data['title']); ?></h2>
    <hr>
    <a href="manage-quizzes.php" class="btn btn-secondary">Back to Manage Quizzes</a>
    <hr>
    <?php if (!empty($submissions)): ?>
        <table class="content-table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Module Name</th>
                    <th>Score</th>
                    <th>Submission Time</th>
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
        <p>No submissions found for this quiz yet.</p>
    <?php endif; ?>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>