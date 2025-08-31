<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Department.php';
require_once __DIR__ . '/../classes/Program.php';
require_once __DIR__ . '/../classes/Feedback.php';
require_once __DIR__ . '/../classes/Module.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$department_class = new Department($db);
$all_departments = $department_class->getAllDepartments();
$program_class = new Program($db);
$feedback_class = new Feedback($db);
$user_id = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['firstname'] ?? '';
$user_batch = $_SESSION['batch'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $batch = $_POST['batch'] ?? '';
    $department_id = $_POST['department_id'] ?? null;
    $program_id = $_POST['program_id'] ?? null;
    $module_id = $_POST['module_id'] ?? null;
    $feedback = $_POST['feedback'] ?? '';
    $rating = $_POST['rating'] ?? 0;
    $date = date('Y-m-d H:i:s');
    // Ensure all required fields are present
    if ($name && $department_id && $program_id && $module_id && $feedback && $rating) {
        if ($feedback_class->submitFeedback($name, $batch, $department_id, $program_id, $module_id, $feedback, $rating, $date, $user_id)) {
            header("Location: dashboard_student.php?feedback_status=success");
            exit;
        } else {
            header("Location: dashboard_student.php?feedback_status=error");
            exit;
        }
    } else {
        header("Location: dashboard_student.php?feedback_status=error&message=missing_fields");
        exit;
    }
}
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <h1 class="feedback-title"><?php echo (isset($_SESSION['user_id']) && $_SESSION['role'] === 'student') ? 'Submit' : 'Guest'; ?> Feedback</h1>
    <form method="POST" class="feedback-form" action="add-feedback.php">
        <div class="form-group">
            <label class="form-label">Your Name <span class="required">*</span></label>
            <input type="text" name="name" class="form-input" value="<?php echo htmlspecialchars($user_name); ?>" <?php echo $user_id ? 'readonly' : 'required'; ?>>
        </div>
        <div class="form-group">
            <label class="form-label">Your Batch</label>
            <input type="text" name="batch" class="form-input" value="<?php echo htmlspecialchars($user_batch); ?>" <?php echo $user_id ? 'readonly' : ''; ?>>
        </div>
        <div class="form-group">
            <label for="department_id" class="form-label">Department <span class="required">*</span></label>
            <select name="department_id" id="department_id" class="form-select" required>
                <option value="" disabled selected>Select Department</option>
                <?php foreach ($all_departments as $dept): ?>
                    <option value="<?php echo htmlspecialchars($dept['id']); ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="program_id" class="form-label">Program <span class="required">*</span></label>
            <select name="program_id" id="program_id" class="form-select" required>
                <option value="" disabled selected>Select Program</option>
            </select>
        </div>
        <div class="form-group">
            <label for="module_id" class="form-label">Module <span class="required">*</span></label>
            <select name="module_id" id="module_id" class="form-select" required>
                <option value="" disabled selected>Select Module</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Feedback <span class="required">*</span></label>
            <textarea name="feedback" rows="5" class="form-textarea" required></textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Rating <span class="required">*</span></label>
            <select name="rating" class="form-select" required>
                <option value="" disabled selected>Select rating</option>
                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Good</option>
                <option value="4">4 - Very Good</option>
                <option value="5">5 - Excellent</option>
            </select>
        </div>
        <button type="submit" class="submit-btn">Submit Feedback</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#department_id').change(function() {
        var department_id = $(this).val();
        if (department_id) {
            $.ajax({
                url: '../modules/fetch-programs.php',
                type: 'POST',
                data: { department_id: department_id },
                dataType: 'json',
                success: function(response) {
                    $('#program_id').empty().append('<option value="" disabled selected>Select Program</option>');
                    $('#module_id').empty().append('<option value="" disabled selected>Select Module</option>');
                    if(response.success && response.data.length > 0) {
                        $.each(response.data, function(key, value) {
                            $('#program_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                }
            });
        } else {
            $('#program_id').empty().append('<option value="" disabled selected>Select Program</option>');
            $('#module_id').empty().append('<option value="" disabled selected>Select Module</option>');
        }
    });
    $('#program_id').change(function() {
        var program_id = $(this).val();
        if (program_id) {
            $.ajax({
                url: '../modules/fetch-modules.php',
                type: 'POST',
                data: { program_id: program_id },
                dataType: 'json',
                success: function(response) {
                    $('#module_id').empty().append('<option value="" disabled selected>Select Module</option>');
                    if(response.success && response.data.length > 0) {
                        $.each(response.data, function(key, value) {
                            $('#module_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                }
            });
        } else {
            $('#module_id').empty().append('<option value="" disabled selected>Select Module</option>');
        }
    });
});
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>