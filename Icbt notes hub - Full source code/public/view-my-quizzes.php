<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Student.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Quiz.php';
if ($_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}
$student_class = new Student($db);
$user_class = new User($db);
$quiz_class = new Quiz($db);
$student_id = $_SESSION['user_id'];
$user_data = $user_class->getUserById($student_id);
$quizzes_for_student = [];
if (!empty($user_data) && isset($user_data['program_id'])) {
    $quizzes_for_student = $student_class->getQuizzesByProgramModules($user_data['program_id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Quizzes</title>
    <link rel="stylesheet" href="../assets/css/view-my-quizzes-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="main-content-wrapper">
    <div class="dashboard-container">
        <h2><i class="fas fa-clipboard-list"></i> My Quizzes</h2>
        <a href="dashboard_student.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <hr>
        <?php if (!empty($quizzes_for_student)): ?>
            <table class="content-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-file-signature"></i> Title</th>
                        <th><i class="fas fa-book-open"></i> Module</th>
                        <th><i class="fas fa-clock"></i> Duration</th>
                        <th><i class="fas fa-cogs"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quizzes_for_student as $quiz): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($quiz['title']); ?></td>
                            <td><?php echo htmlspecialchars($quiz['module_name']); ?></td>
                            <td><?php echo htmlspecialchars($quiz['duration']); ?> minutes</td>
                            <td>
                                <?php if ($quiz_class->checkSubmission($student_id, $quiz['id'])): ?>
                                    <a href="view-results.php?quiz_id=<?php echo $quiz['id']; ?>" class="btn btn-success"><i class="fas fa-poll-h"></i> View Results</a>
                                <?php else: ?>
                                    <a href="take-quiz.php?quiz_id=<?php echo $quiz['id']; ?>" class="btn btn-primary"><i class="fas fa-pen"></i> Take Quiz</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><i class="fas fa-info-circle"></i> No quizzes available at the moment.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>