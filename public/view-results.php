<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Quiz.php';
if ($_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}
$quiz_class = new Quiz($db);
$student_id = $_SESSION['user_id'];
$quiz_id = $_GET['quiz_id'] ?? null;
if (!$quiz_id) {
    header('Location: view-my-quizzes.php');
    exit;
}
$quiz_data = $quiz_class->getQuizById($quiz_id);
if (!$quiz_data) {
    echo "Quiz not found.";
    exit;
}
$student_score = $quiz_class->getQuizScore($student_id, $quiz_id);
$total_points = $quiz_class->getTotalPoints($quiz_id);
?>
<div class="container">
    <h2>Quiz Results for: <?php echo htmlspecialchars($quiz_data['title']); ?></h2>
    <p>Module: <?php echo htmlspecialchars($quiz_data['module_name']); ?></p>
    <hr>
    <h3>Your Score: <?php echo htmlspecialchars($student_score); ?> out of <?php echo htmlspecialchars($total_points); ?></h3>
    <a href="view-my-quizzes.php" class="btn btn-secondary">Back to Quizzes</a>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>