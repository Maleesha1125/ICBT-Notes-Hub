<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/lecturer_header.php';
require_once __DIR__ . '/../classes/Lecturer.php';
require_once __DIR__ . '/../classes/User.php';
if ($_SESSION['role'] !== 'lecturer') {
    header('Location: login.php');
    exit;
}
$lecturer_class = new Lecturer($db);
$user_class = new User($db);
$lecturer_id = $_SESSION['user_id'];
$lecturer_department_id = $lecturer_class->getLecturerDepartmentId($lecturer_id);
$departments = $db->query("SELECT * FROM departments");
$assigned_programs = $lecturer_class->getAssignedPrograms($lecturer_id);
$assigned_modules = $lecturer_class->getAssignedModules($lecturer_id);
$user = $user_class->getUserById($lecturer_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard/lecturer-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="main-content-wrapper">
    <div class="dashboard-container">
        <h2><i class="fas fa-tachometer-alt"></i> Welcome, <?php echo htmlspecialchars($user['firstname']); ?>!</h2>
        <div class="dashboard-content">
            <div class="dashboard-grid">
                <a href="upload-lecturer-content.php" class="dashboard-card blue-card">
                    <i class="fas fa-upload"></i>
                    <span>View & Upload Content</span>
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
            </div>
            <a href="lecturer-profile.php" class="profile-card red-card">
                <i class="fas fa-user-circle"></i>
                <span>View Profile</span>
            </a>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>
</body>
</html>