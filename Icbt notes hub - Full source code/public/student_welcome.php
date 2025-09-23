<?php require_once __DIR__ . '/../includes/header.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="../assets/css/student-welcome-style.css">
<div class="welcome-hero">
    <div class="hero-overlay"></div>
    <div class="welcome-content-container">
        <h2>Welcome Student!</h2>
        <p>Please log in to your account or register if you are a new student.</p>
        <div class="cta-buttons">
            <a href="login_student.php" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Login</a>
            <a href="register.php" class="btn btn-success"><i class="fas fa-user-plus"></i> Register</a>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>