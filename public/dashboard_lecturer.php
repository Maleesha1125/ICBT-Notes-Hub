<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Lecturer.php';
if ($_SESSION['role'] !== 'lecturer') {
    header('Location: login.php');
    exit;
}
$lecturer_class = new Lecturer($db);
$lecturer_id = $_SESSION['user_id'];
$lecturer_department_id = $lecturer_class->getLecturerDepartmentId($lecturer_id);
$departments = $db->query("SELECT * FROM departments");
$assigned_programs = $lecturer_class->getAssignedPrograms($lecturer_id);
$assigned_modules = $lecturer_class->getAssignedModules($lecturer_id);
?>
<div class="container">
    <h2>Lecturer Dashboard</h2>
    <div class="dashboard-grid">
        <a href="upload-lecturer-content.php" class="dashboard-card blue-card">
            <i class="fas fa-upload"></i>
            <span>Upload Content</span>
        </a>
        <a href="manage-content.php" class="dashboard-card green-card">
            <i class="fas fa-edit"></i>
            <span>Manage Content</span>
        </a>
        <a href="add-quiz.php" class="dashboard-card purple-card">
            <i class="fas fa-question-circle"></i>
            <span>Add Quiz</span>
        </a>
        <a href="manage-quizzes.php" class="dashboard-card orange-card">
            <i class="fas fa-tasks"></i>
            <span>Manage Quizzes</span>
        </a>
        <a href="lecturer-profile.php" class="dashboard-card red-card">
            <i class="fas fa-user-circle"></i>
            <span>View Profile</span>
        </a>
    </div>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>