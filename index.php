<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ICBT Notes Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="welcome-container">
    <div class="welcome-header">
        <h1>ICBT Notes Hub</h1>
        <p>A place for students and lecturers to connect and learn.</p>
    </div>
    <div class="login-card">
        <h2>Welcome to the Portal</h2>
        <a href="public/student_welcome.php" class="btn btn-primary">Student</a>
        <a href="public/login_lecturer.php" class="btn btn-primary">Lecturer</a>
    </div>
</div>
<?php
require_once __DIR__ . '/includes/footer.php';
?>