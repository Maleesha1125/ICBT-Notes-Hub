<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Quiz.php';
require_once __DIR__ . '/../classes/User.php';
if ($_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}
$quiz_class = new Quiz($db);
$user_class = new User($db);
$student_id = $_SESSION['user_id'];
$quiz_id = $_GET['quiz_id'] ?? null;
if (!$quiz_id) {
    header('Location: view-my-quizzes.php');
    exit;
}
$quiz_data = $quiz_class->getQuizWithQuestions($quiz_id);
if (!$quiz_data || empty($quiz_data['questions'])) {
    echo "<div class='container'><p>Quiz not found or no questions available.</p></div>";
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answers = $_POST['answers'] ?? [];
    $quiz_class->submitQuiz($student_id, $quiz_id, $answers);
    $_SESSION['message'] = "Quiz submitted successfully!";
    header('Location: view-results.php?quiz_id=' . $quiz_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/take-quiz-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container">
    <div class="quiz-card">
        <h2><i class="fas fa-file-alt"></i> Take Quiz: <?php echo htmlspecialchars($quiz_data['title']); ?></h2>
        <p><i class="fas fa-book"></i> Module: <?php echo htmlspecialchars($quiz_data['module_name']); ?></p>
        <p><i class="fas fa-clock"></i> Duration: <?php echo htmlspecialchars($quiz_data['duration']); ?> minutes</p>
        <hr>
        <form action="take-quiz.php?quiz_id=<?php echo $quiz_id; ?>" method="POST">
            <?php foreach ($quiz_data['questions'] as $q_index => $question): ?>
                <div class="quiz-question">
                    <h4><?php echo ($q_index + 1) . '. ' . htmlspecialchars($question['question_text']); ?></h4>
                    <input type="hidden" name="question_ids[]" value="<?php echo $question['id']; ?>">
                    <div class="options-group">
                        <?php foreach ($question['options'] as $o_index => $option): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answers[<?php echo $question['id']; ?>]" id="option-<?php echo $question['id'] . '-' . $o_index; ?>" value="<?php echo $option['id']; ?>" required>
                                <label class="form-check-label" for="option-<?php echo $question['id'] . '-' . $o_index; ?>">
                                    <?php echo htmlspecialchars($option['option_text']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <hr>
            <button type="submit" class="btn btn-primary"><i class="fas fa-check-circle"></i> Submit Quiz</button>
        </form>
    </div>
</div>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>