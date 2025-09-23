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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="../assets/css/stu-lec-profile-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container">
    <div class="profile-card">
        <h2><i class="fas fa-user-circle"></i> Student Profile</h2>
        <hr>
        <?php if ($student_data): ?>
            <div class="profile-details">
                <p><strong><i class="fas fa-user"></i> First Name:</strong> <?php echo htmlspecialchars($student_data['firstname']); ?></p>
                <p><strong><i class="fas fa-user"></i> Last Name:</strong> <?php echo htmlspecialchars($student_data['lastname']); ?></p>
                <p><strong><i class="fas fa-id-card-alt"></i> Username:</strong> <?php echo htmlspecialchars($student_data['username']); ?></p>
                <p><strong><i class="fas fa-graduation-cap"></i> Program:</strong> <?php echo htmlspecialchars($student_data['program_name']); ?></p>
                <p><strong><i class="fas fa-users"></i> Batch:</strong> <?php echo htmlspecialchars($student_data['batch']); ?></p>
            </div>
            <hr>
            <a href="dashboard_student.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <?php else: ?>
            <div class="alert alert-danger">
                <p>Student data not found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>