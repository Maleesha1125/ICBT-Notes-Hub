<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Quiz.php';
require_once __DIR__ . '/../classes/Module.php';
require_once __DIR__ . '/../classes/User.php';
if ($_SESSION['role'] !== 'lecturer') {
    header('Location: login.php');
    exit;
}
$quiz_class = new Quiz($db);
$user_class = new User($db);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_quiz_id'])) {
    $quiz_id_to_delete = $_POST['delete_quiz_id'];
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
<div class="container">
    <h2>Manage My Quizzes</h2>
    <hr>
    <a href="dashboard_lecturer.php" class="btn btn-secondary">Back to Dashboard</a>
    <a href="add-quiz.php" class="btn btn-primary">Add New Quiz</a>
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
                        <td>
                            <a href="add-questions.php?quiz_id=<?php echo $quiz['id']; ?>" class="btn btn-warning">Add/Edit Questions</a>
                            <a href="view-submissions.php?quiz_id=<?php echo $quiz['id']; ?>" class="btn btn-info">View Submissions</a>
                            <form action="manage-quizzes.php" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this quiz? This action cannot be undone.');">
                                <input type="hidden" name="delete_quiz_id" value="<?php echo $quiz['id']; ?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have not created any quizzes yet.</p>
    <?php endif; ?>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>