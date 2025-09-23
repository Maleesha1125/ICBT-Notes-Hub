<?php require_once __DIR__ . '/../includes/lecturer_header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/lecturer-welcome-style.css">
</head>
<body>

<div class="welcome-hero">
    <div class="hero-overlay"></div>
    <div class="welcome-content-container">
        <h2><i class="fas fa-chalkboard-teacher"></i> Welcome Lecturer!</h2>
        <p class="mt-4">Please log in to your account to access the system.</p>
        <a href="login_lecturer.php" class="btn btn-primary mt-3"><i class="fas fa-sign-in-alt"></i> Login</a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>