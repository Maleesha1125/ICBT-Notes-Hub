<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About ICBT Notes Hub</title>
    <link rel="stylesheet" href="../assets/css/about-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<a href="javascript:history.back()" class="back-button">
    <i class="fas fa-arrow-left"></i> Back
</a>
<div class="container">
    <section class="about-hero">
        <div class="hero-content">
            <h1>ICBT Notes Hub - Your Collaborative Learning Platform</h1>
            <p class="tagline">Enhancing knowledge sharing between students and lecturers at ICBT</p>
        </div>
    </section>
    <section class="mission-section">
        <div class="section-header">
            <i class="fas fa-bullseye icon-large"></i>
            <h2>Our Mission</h2>
        </div>
        <p>At ICBT Notes Hub, we're revolutionizing how course materials are shared and accessed. Our platform bridges the gap between students and lecturers, creating a seamless knowledge-sharing ecosystem.</p>
        <div class="feature-grid">
            <div class="feature-card">
                <h3><i class="fas fa-user-graduate"></i> For Students</h3>
                <ul>
                    <li><i class="fas fa-download"></i> View,download and upload content</li>
                    <li><i class="fas fa-clock"></i> Access materials anytime</li>
                    <li><i class="fas fa-folder-open"></i> Module-based categorization</li>
                    <li><i class="fas fa-comments"></i> Give and view feedback</li>
                </ul>
            </div>
            <div class="feature-card">
                <h3><i class="fas fa-chalkboard-teacher"></i> For Lecturers</h3>
                <ul>
                    <li><i class="fas fa-upload"></i> Upload and manage course materials</li>
                    <li><i class="fas fa-chalkboard"></i> Provide academic guidance</li>
                    <li><i class="fas fa-archive"></i> Organized resource repository</li>
                    <li><i class="fas fa-user-check"></i> Provide Quizzes and Check results</li>
                </ul>
            </div>
        </div>
    </section>
    <section class="tech-stack-section">
        <div class="section-header">
            <i class="fas fa-cogs icon-large"></i>
            <h2>Platform Technology</h2>
        </div>
        <div class="tech-badges">
            <span class="badge"><i class="fab fa-php"></i> PHP</span>
            <span class="badge"><i class="fas fa-database"></i> MySQL</span>
            <span class="badge"><i class="fab fa-js-square"></i> JavaScript</span>
            <span class="badge"><i class="fab fa-html5"></i> HTML5</span>
            <span class="badge"><i class="fab fa-css3-alt"></i> CSS3</span>
        </div>
    </section>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>
</body>
</html>