<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Student.php';
require_once __DIR__ . '/../includes/auth_check.php';
if ($_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}
$student_class = new Student($db);
$student_id = $_SESSION['user_id'];
$student_data = $student_class->getStudentProfileByUserId($student_id);
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <h2>Student Profile</h2>
    <hr>
    <?php if ($student_data): ?>
        <div class="profile-details">
            <p><strong>First Name:</strong> <?php echo htmlspecialchars($student_data['firstname']); ?></p>
            <p><strong>Last Name:</strong> <?php echo htmlspecialchars($student_data['lastname']); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($student_data['username']); ?></p>
            <p><strong>Program:</strong> <?php echo htmlspecialchars($student_data['program_name']); ?></p>
            <p><strong>Batch:</strong> <?php echo htmlspecialchars($student_data['batch']); ?></p>
        </div>
        <hr>
        <a href="dashboard_student.php" class="btn btn-primary">Back to Dashboard</a>
    <?php else: ?>
        <div class="alert alert-danger">
            <p>Student data not found.</p>
        </div>
    <?php endif; ?>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>