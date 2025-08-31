<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Quiz.php';
require_once __DIR__ . '/../classes/Module.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Notification.php';
if ($_SESSION['role'] !== 'lecturer') {
    header('Location: login.php');
    exit;
}
$quiz_class = new Quiz($db);
$module_class = new Module($db);
$user_class = new User($db);
$notification_class = new Notification($db);
$lecturer_id = $_SESSION['user_id'];
$lecturer = $user_class->getUserById($lecturer_id);
$assigned_modules = [];
if (isset($lecturer['program_id'])) {
    $assigned_modules = $module_class->getModulesByProgram($lecturer['program_id']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $module_id = $_POST['module_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $quiz_id = $quiz_class->addQuiz($module_id, $lecturer_id, $title, $description, $duration);
    if ($quiz_id) {
        $module = $module_class->getModuleById($module_id);
        $message = "A new quiz titled **" . htmlspecialchars($title) . "** has been added for your module: **" . htmlspecialchars($module['name']) . "**.";
        $program_id = $lecturer['program_id'];
        $students_in_program = $user_class->getStudentsByProgram($program_id);
        foreach ($students_in_program as $student) {
            $notification_class->addNotification($student['id'], $message, 'info');
        }
        $_SESSION['message'] = "Quiz added successfully. Now, add questions.";
        header("Location: add-questions.php?quiz_id=" . $quiz_id);
        exit;
    } else {
        $_SESSION['message'] = "Error adding quiz.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Quiz</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Add New Quiz</h2>
    <hr>
    <a href="dashboard_lecturer.php" class="btn btn-secondary">Back to Dashboard</a>
    <hr>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    <form action="add-quiz.php" method="POST">
        <div class="form-group">
            <label for="module_id">Select Module:</label>
            <select name="module_id" id="module_id" class="form-control" required>
                <?php foreach ($assigned_modules as $module): ?>
                    <option value="<?php echo $module['id']; ?>"><?php echo htmlspecialchars($module['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="title">Quiz Title:</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="duration">Duration (in minutes):</label>
            <input type="number" name="duration" id="duration" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Quiz</button>
    </form>
</div>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>