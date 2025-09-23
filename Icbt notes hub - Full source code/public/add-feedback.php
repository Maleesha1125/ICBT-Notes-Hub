<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Department.php';
require_once __DIR__ . '/../classes/Feedback.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$department_class = new Department($db);
$all_departments = $department_class->getAllDepartments();
$feedback_class = new Feedback($db);
$user_id = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['firstname'] ?? '';
$user_batch = $_SESSION['batch'] ?? '';
$success_message = "";
$error_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $batch = $_POST['batch'] ?? '';
    $department_id = $_POST['department_id'] ?? null;
    $feedback = $_POST['feedback'] ?? '';
    $rating = $_POST['rating'] ?? 0;
    if ($user_id && $feedback && $rating) {
        if ($feedback_class->submitFeedback($user_id, $feedback, $rating, 0)) {
            $success_message = "Your feedback has been successfully added. Thank you!";
        } else {
            $error_message = "There was an error submitting your feedback. Please try again.";
        }
    } else {
        $error_message = "Please fill in all required fields.";
    }
}
require_once __DIR__ . '/../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo (isset($_SESSION['user_id']) && $_SESSION['role'] === 'student') ? 'Submit' : 'Guest'; ?> Feedback</title>
    <link rel="stylesheet" href="../assets/css/add-feedback-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<a href="javascript:history.back()" class="back-button">
    <i class="fas fa-arrow-left"></i> Back
</a>
<div class="container">
    <div class="feedback-card">
        <h1 class="feedback-title"><i class="fas fa-comment-dots"></i> <?php echo (isset($_SESSION['user_id']) && $_SESSION['role'] === 'student') ? 'Submit' : 'Guest'; ?> Feedback</h1>
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
            <script>
                setTimeout(function() {
                    window.location.href = 'view-feedbacks.php';
                }, 3000); 
            </script>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form method="POST" class="feedback-form" action="add-feedback.php">
            <div class="form-group">
                <label class="form-label"><i class="fas fa-user"></i> Your Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-input" value="<?php echo htmlspecialchars($user_name); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-users"></i> Your Batch</label>
                <input type="text" name="batch" class="form-input" value="<?php echo htmlspecialchars($user_batch); ?>">
            </div>
            <div class="form-group">
                <label for="department_id" class="form-label"><i class="fas fa-building"></i> Department <span class="required">*</span></label>
                <select name="department_id" id="department_id" class="form-select" required>
                    <option value="" disabled selected>Select Department</option>
                    <?php foreach ($all_departments as $dept): ?>
                        <option value="<?php echo htmlspecialchars($dept['id']); ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-comment-alt"></i> Feedback <span class="required">*</span></label>
                <textarea name="feedback" rows="5" class="form-textarea" required></textarea>
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-star"></i> Rating <span class="required">*</span></label>
                <select name="rating" class="form-select" required>
                    <option value="" disabled selected>Select rating</option>
                    <option value="1">1 - Poor</option>
                    <option value="2">2 - Fair</option>
                    <option value="3">3 - Good</option>
                    <option value="4">4 - Very Good</option>
                    <option value="5">5 - Excellent</option>
                </select>
            </div>
            <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> Submit Feedback</button>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>