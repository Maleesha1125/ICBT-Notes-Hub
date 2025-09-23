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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/view-results-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container results-container">
    <div class="results-card">
        <h2><i class="fas fa-poll-h"></i> Quiz Results for: <?php echo htmlspecialchars($quiz_data['title']); ?></h2>
        <p><i class="fas fa-book-open"></i> Module: <?php echo htmlspecialchars($quiz_data['module_name']); ?></p>
        <hr>
        <h3><i class="fas fa-star"></i> Your Score: <?php echo htmlspecialchars($student_score); ?> out of <?php echo htmlspecialchars($total_points); ?></h3>
        <a href="view-my-quizzes.php" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Back to Quizzes</a>
    </div>
</div>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>