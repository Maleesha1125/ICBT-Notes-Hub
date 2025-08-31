<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <section class="contact-hero">
        <h1>Contact ICBT Notes Hub</h1>
        <p>Reach out to our academic support team for assistance</p>
    </section>
    <div class="contact-container">
        <div class="contact-info">
            <div class="contact-card">
                <div class="contact-icon">📧</div>
                <h3>Academic Support</h3>
                <p>noteshub@icbt.edu.lk</p>
            </div>
            <div class="contact-card">
                <div class="contact-icon">📞</div>
                <h3>Campus Hotline</h3>
                <p>+94 11 2 345 678</p>
            </div>
            <div class="contact-card">
                <div class="contact-icon">🏫</div>
                <h3>Main Campus</h3>
                <p>ICBT Headquarters, Colombo 03, Sri Lanka</p>
            </div>
        </div>
        <div class="form-container">
            <form class="contact-form" method="POST">
                <h2>Send an Inquiry</h2>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label>Your Message</label>
                    <textarea name="message" rows="5" placeholder="Describe your inquiry" required></textarea>
                </div>
                <button type="submit" class="btn-primary">Send Inquiry</button>
            </form>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>