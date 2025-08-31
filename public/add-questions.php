<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Quiz.php';
require_once __DIR__ . '/../classes/Notification.php';
require_once __DIR__ . '/../classes/Module.php';
require_once __DIR__ . '/../classes/User.php';
if ($_SESSION['role'] !== 'lecturer') {
    header('Location: login.php');
    exit;
}
$quiz_class = new Quiz($db);
$notification_class = new Notification($db);
$module_class = new Module($db);
$user_class = new User($db);
$quiz_id = $_GET['quiz_id'] ?? null;
$action = $_GET['action'] ?? null;
if ($action === 'done' && $quiz_id) {
    $quiz_data = $quiz_class->getQuizById($quiz_id);
    if ($quiz_data && $quiz_data['lecturer_id'] === $_SESSION['user_id']) {
        $lecturer = $user_class->getUserById($_SESSION['user_id']);
        $program_id = $lecturer['program_id'] ?? null;
        if ($program_id) {
            $students = $user_class->getStudentsByProgram($program_id);
            $message = "A new quiz titled '" . htmlspecialchars($quiz_data['title']) . "' has been added to your module.";
            foreach ($students as $student) {
                $notification_class->addNotification($student['id'], $message, 'info', 'New Quiz');
            }
        }
        $_SESSION['message'] = "Quiz finalized and students notified.";
        header('Location: manage-quizzes.php');
        exit;
    }
}
$quiz_data = null;
if ($quiz_id) {
    $quiz_data = $quiz_class->getQuizById($quiz_id);
    if (!$quiz_data || $quiz_data['lecturer_id'] !== $_SESSION['user_id']) {
        $_SESSION['message'] = "Quiz not found or you don't have permission.";
        header('Location: manage-quizzes.php');
        exit;
    }
} else {
    $_SESSION['message'] = "Invalid quiz ID.";
    header('Location: manage-quizzes.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_text = $_POST['question_text'];
    $points = $_POST['points'];
    $options = $_POST['options'];
    $correct_option_index = $_POST['correct_option'];
    $question_id = $quiz_class->addQuestion($quiz_id, $question_text, 'multiple_choice', $points);
    if ($question_id) {
        foreach ($options as $index => $option_text) {
            $is_correct = ($index == $correct_option_index) ? 1 : 0;
            $quiz_class->addOption($question_id, $option_text, $is_correct);
        }
        $_SESSION['message'] = "Question added successfully.";
        header("Location: add-questions.php?quiz_id=" . $quiz_id);
        exit;
    } else {
        $_SESSION['message'] = "Error adding question.";
    }
}
$existing_questions = $quiz_class->getQuizWithQuestions($quiz_id)['questions'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Questions</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Add Questions to Quiz: <?php echo htmlspecialchars($quiz_data['title']); ?></h2>
    <hr>
    <a href="manage-quizzes.php" class="btn btn-secondary">Back to Quizzes</a>
    <a href="add-questions.php?quiz_id=<?php echo $quiz_id; ?>&action=done" class="btn btn-success">Done Adding Questions</a>
    <hr>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    <div class="question-list">
        <h3>Current Questions</h3>
        <?php if (!empty($existing_questions)): ?>
            <ul>
                <?php foreach ($existing_questions as $q): ?>
                    <li><?php echo htmlspecialchars($q['question_text']); ?> (<?php echo htmlspecialchars($q['points']); ?> points)</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No questions added yet.</p>
        <?php endif; ?>
    </div>
    <hr>
    <form action="add-questions.php?quiz_id=<?php echo $quiz_id; ?>" method="POST">
        <div class="form-group">
            <label for="question_text">Question Text:</label>
            <textarea name="question_text" id="question_text" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="points">Points:</label>
            <input type="number" name="points" id="points" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Options:</label>
            <div id="options-container">
                <div class="option-item">
                    <input type="text" name="options[]" placeholder="Option 1" required>
                    <input type="radio" name="correct_option" value="0" required> Correct
                </div>
                <div class="option-item">
                    <input type="text" name="options[]" placeholder="Option 2" required>
                    <input type="radio" name="correct_option" value="1"> Correct
                </div>
                <div class="option-item">
                    <input type="text" name="options[]" placeholder="Option 3" required>
                    <input type="radio" name="correct_option" value="2"> Correct
                </div>
                <div class="option-item">
                    <input type="text" name="options[]" placeholder="Option 4" required>
                    <input type="radio" name="correct_option" value="3"> Correct
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add Question</button>
    </form>
</div>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>