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
<div class="container">
    <h2>My Quizzes</h2>
    <a href="dashboard_student.php" class="btn btn-secondary">Back to Dashboard</a>
    <hr>
    <?php if (!empty($quizzes_for_student)): ?>
        <table class="content-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Module</th>
                    <th>Duration</th>
                    <th>Actions</th>
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
                                <a href="view-results.php?quiz_id=<?php echo $quiz['id']; ?>" class="btn btn-success">View Results</a>
                            <?php else: ?>
                                <a href="take-quiz.php?quiz_id=<?php echo $quiz['id']; ?>" class="btn btn-primary">Take Quiz</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No quizzes available at the moment.</p>
    <?php endif; ?>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>