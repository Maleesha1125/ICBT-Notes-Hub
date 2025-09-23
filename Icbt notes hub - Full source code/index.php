<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ICBT Notes Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/main-welcome-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<header class="main-header">
    <div class="logo">
        <i class="fas fa-book-open"></i>
        <h1>ICBT Notes Hub</h1>
    </div>
    <nav>
        <a href="#about-section">About</a>
        <a href="#programs-section">Notes by</a>
        <a href="#contact-section">Contact</a>
    </nav>
</header>
<main>
    <section class="hero-section">
        <div class="hero-content">
            <h2>Your Ultimate Academic Resource Hub</h2>
            <p>Connect with peers and lecturers. Access lecture notes, quizzes, and other study materials with ease.</p>
            <div class="cta-buttons">
                <a href="public/student_welcome.php" class="btn btn-student"><i class="fas fa-user-graduate"></i> Student Portal</a>
                <a href="public/lecturer_welcome.php" class="btn btn-lecturer"><i class="fas fa-chalkboard-teacher"></i> Lecturer Portal</a>
            </div>
        </div>
    </section>
    <section id="about-section" class="info-section">
        <h3>About ICBT Notes Hub</h3>
        <p>ICBT Notes Hub is a dedicated platform designed to streamline the sharing of academic resources within the university. We provide a centralized location for lecturers to upload content and for students to access it, ensuring everyone has the tools they need to succeed.</p>
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
                    <li><i class="fas fa-download"></i> View and download notes</li>
                    <li><i class="fas fa-clock"></i> Access materials anytime</li>
                    <li><i class="fas fa-folder-open"></i> Subject-based categorization</li>
                    <li><i class="fas fa-comments"></i> Give and view feedback</li>
                </ul>
            </div>
            <div class="feature-card">
                <h3><i class="fas fa-chalkboard-teacher"></i> For Lecturers</h3>
                <ul>
                    <li><i class="fas fa-upload"></i> Upload and manage course materials</li>
                    <li><i class="fas fa-chalkboard"></i> Provide academic guidance</li>
                    <li><i class="fas fa-archive"></i> Organized resource repository</li>
                    <li><i class="fas fa-user-check"></i> Approve and manage student uploads</li>
                </ul>
            </div>
        </div>
    </section>
    <section id="programs-section" class="programs-section">
        <div class="section-header">
            <i class="fas fa-graduation-cap icon-large"></i>
            <h2>Your Faculty Notes Hub</h2>
        </div>
        <div class="program-grid-horizontal">
            <div class="program-card">
                <img src="assets/images/business-management.jpg" alt="Business Management Department">
                <div class="card-content">
                    <h3>Business Management</h3>
                    <p>Access notes and resources for business programs.</p>
                </div>
            </div>
            <div class="program-card">
                <img src="assets/images/information-technology.jpg" alt="Information Technology Department">
                <div class="card-content">
                    <h3>Information Technology</h3>
                    <p>Find notes and materials for IT and computing degrees.</p>
                </div>
            </div>
            <div class="program-card">
                <img src="assets/images/engineering-construction.jpg" alt="Engineering & Construction Department">
                <div class="card-content">
                    <h3>Engineering & Construction</h3>
                    <p>Get study materials for all engineering fields.</p>
                </div>
            </div>
            <div class="program-card">
                <img src="assets/images/science.jpg" alt="Science Department">
                <div class="card-content">
                    <h3>Science</h3>
                    <p>Access notes and lab reports for science majors.</p>
                </div>
            </div>
            <div class="program-card">
                <img src="assets/images/english.jpg" alt="English Department">
                <div class="card-content">
                    <h3>English</h3>
                    <p>Explore resources for languages, history, and arts.</p>
                </div>
            </div>
            <div class="program-card">
                <img src="assets/images/law.jpg" alt="Law Department">
                <div class="card-content">
                    <h3>Law</h3>
                    <p>Find case studies and legal notes for law students.</p>
                </div>
            </div>
        </div>
    </section>
    <section id="contact-section" class="info-section">
        <h3>Contact Us</h3>
        <p>Have a question or need support? Reach out to us through our official university contact channels.</p>
        <a href="public/contact.php" class="btn btn-secondary">Get in Touch</a>
    </section>
</main>
<a href="public/contact.php" class="contact-bubble">
    <i class="fas fa-comments"></i>
</a>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
 <script src="assets/css/js/main.js"></script>
</body>
</html>