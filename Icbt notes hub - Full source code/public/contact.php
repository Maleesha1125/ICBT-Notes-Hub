<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact ICBT Notes Hub</title>
    <link rel="stylesheet" href="../assets/css/contact-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<a href="javascript:history.back()" class="back-button">
    <i class="fas fa-arrow-left"></i> Back
</a>
<div class="container">
    <section class="contact-hero">
        <h1><i class="fas fa-headset"></i> Contact ICBT Notes Hub</h1>
        <p>Reach out to our academic support team for assistance</p>
    </section>
    <div class="contact-container">
        <div class="contact-info">
            <div class="contact-card">
                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                <h3>Academic Support</h3>
                <p>noteshub@icbt.edu.lk</p>
            </div>
            <div class="contact-card">
                <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                <h3>Campus Hotline</h3>
                <p>+94 11 2 345 678</p>
            </div>
            <div class="contact-card">
                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                <h3>Main Campus</h3>
                <p>ICBT Headquarters, Colombo 03, Sri Lanka</p>
            </div>
        </div>
        <div class="form-container">
            <form class="contact-form" method="POST">
                <h2>Send an Inquiry</h2>
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" name="name" placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-comment-alt"></i> Your Message</label>
                    <textarea name="message" rows="5" placeholder="Describe your inquiry" required></textarea>
                </div>
                <button type="submit" class="btn-primary"><i class="fas fa-paper-plane"></i> Send Inquiry</button>
            </form>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>
</body>
</html>