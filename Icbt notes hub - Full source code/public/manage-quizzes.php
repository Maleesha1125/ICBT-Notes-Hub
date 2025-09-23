<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/lecturer_header.php';
require_once __DIR__ . '/../classes/Quiz.php';
require_once __DIR__ . '/../classes/Module.php';
require_once __DIR__ . '/../classes/User.php';
if ($_SESSION['role'] !== 'lecturer') {
    header('Location: login.php');
    exit;
}
$quiz_class = new Quiz($db);
$user_class = new User($db);
if (isset($_GET['delete_quiz_id'])) {
    $quiz_id_to_delete = $_GET['delete_quiz_id'];
    if ($quiz_class->deleteQuiz($quiz_id_to_delete)) {
        $_SESSION['message'] = "Quiz deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting quiz.";
    }
    header('Location: manage-quizzes.php');
    exit;
}
$lecturer_id = $_SESSION['user_id'];
$quizzes = $quiz_class->getQuizzesByLecturer($lecturer_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage My Quizzes</title>
    <link rel="stylesheet" href="../assets/css/manage-quizzes-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container">
    <div class="manage-quizzes-card">
        <h2><i class="fas fa-tasks"></i> Manage My Quizzes</h2>
        <hr>
        <div class="button-group">
            <a href="dashboard_lecturer.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            <a href="add-quiz.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Quiz</a>
        </div>
        <hr>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($_SESSION['message']); ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <?php if (!empty($quizzes)): ?>
            <table class="content-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Module</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quizzes as $quiz): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($quiz['title']); ?></td>
                            <td><?php echo htmlspecialchars($quiz['module_name']); ?></td>
                            <td class="button-actions">
                                <a href="add-questions.php?quiz_id=<?php echo $quiz['id']; ?>" class="btn btn-warning"><i class="fas fa-question-circle"></i> Add Questions</a>
                                <a href="view-submissions.php?quiz_id=<?php echo $quiz['id']; ?>" class="btn btn-info"><i class="fas fa-eye"></i> View Submissions</a>
                                <a href="manage-quizzes.php?delete_quiz_id=<?php echo $quiz['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this quiz? This action cannot be undone.');"><i class="fas fa-trash-alt"></i> Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have not created any quizzes yet.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>