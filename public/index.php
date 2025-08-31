<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICBT Notes Hub - Welcome</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* CSS for the Image Slider */
        .image-slider {
            width: 100%;
            height: 500px; /* Adjust height as needed */
            overflow: hidden;
            position: relative;
            margin: 2rem 0;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .slider-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            animation: fadeAnimation 15s infinite; /* 5s per image x 3 images = 15s total */
        }
        
        .slider-image:nth-child(1) {
            animation-delay: 0s;
        }

        .slider-image:nth-child(2) {
            animation-delay: 5s; /* Delay for the second image */
        }

        .slider-image:nth-child(3) {
            animation-delay: 10s; /* Delay for the third image */
        }

        @keyframes fadeAnimation {
            0% { opacity: 0; }
            10% { opacity: 1; }
            33% { opacity: 1; }
            43% { opacity: 0; }
            100% { opacity: 0; }
        }

        /* Hero Section Styling */
        .hero-section {
            text-align: center;
        }
        .hero-content h1 {
            color: #004d99;
        }
        .hero-content .tagline {
            color: #666;
        }
    </style>
</head>
<body>
<div class="container">
    <section class="hero-section">
        <div class="hero-content">
            <h1>ICBT Notes Hub - Your Collaborative Learning Platform</h1>
            <p class="tagline">Enhancing knowledge sharing between students and lecturers at ICBT</p>
            <div class="call-to-action">
                <a href="register.php" class="btn-primary">Register Now</a>
                <a href="about.php" class="btn-secondary">Learn More</a>
            </div>
        </div>
    </section>

    <section class="image-slider">
        <img class="slider-image" src="assets/images/dashboard-view.png" alt="Student Dashboard">
        <img class="slider-image" src="assets/images/admin-panel.png" alt="Admin Panel">
        <img class="slider-image" src="assets/images/registration-form.png" alt="Registration Form">
    </section>

    <section class="about-section">
        <h2>Key Features</h2>
        <p>Explore the main functionalities that make ICBT Notes Hub the perfect tool for your academic journey.</p>
        <div class="feature-grid">
            <div class="feature-card">
                <h3>📚 For Students</h3>
                <p>Seamlessly access and download notes, documents, and books for all your modules.</p>
            </div>
            <div class="feature-card">
                <h3>👨‍🏫 For Lecturers</h3>
                <p>Effortlessly upload, organize, and manage course materials for your students.</p>
            </div>
            <div class="feature-card">
                <h3>📝 For Admins</h3>
                <p>Maintain full control over user accounts, departments, and platform content.</p>
            </div>
        </div>
    </section>
</div>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>