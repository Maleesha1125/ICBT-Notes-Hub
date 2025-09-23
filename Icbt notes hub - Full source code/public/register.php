<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Department.php';
require_once __DIR__ . '/../classes/Program.php';
require_once __DIR__ . '/../classes/User.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$department_class = new Department($db);
$all_departments = $department_class->getAllDepartments();
$user_class = new User($db);
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $password = $_POST['password'] ?? '';
    $department_id = $_POST['department_id'] ?? null;
    $program_id = $_POST['program_id'] ?? null;
    $batch = $_POST['batch'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    if (empty($firstname) || empty($lastname)) {
        $error = "First name and last name are required.";
    } else {
        $username = strtolower($firstname . substr($lastname, 0, 1));
        if ($user_class->checkUsernameExists($username)) {
            $error = "A user with this username already exists. Please contact support.";
        } else {
            if ($user_class->registerUser($username, $password, $firstname, $lastname, 'student', $department_id, $program_id, $batch, $mobile)) {
                header('Location: login_student.php?registration_success=true&username=' . urlencode($username));
                exit;
            } else {
                $error = "Error registering user. Please try again.";
            }
        }
    }
}
require_once __DIR__ . '/../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/registration-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container">
    <div class="registration-card">
        <h2><i class="fas fa-user-plus"></i> Student Registration</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="firstname"><i class="fas fa-user"></i> First Name</label>
                <input type="text" id="firstname" name="firstname" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="lastname"><i class="fas fa-user"></i> Last Name</label>
                <input type="text" id="lastname" name="lastname" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="department_id"><i class="fas fa-building"></i> Department</label>
                <select id="department_id" name="department_id" class="form-control" required>
                    <option value="" disabled selected>Select Department</option>
                    <?php foreach ($all_departments as $dept): ?>
                        <option value="<?php echo htmlspecialchars($dept['id']); ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="program_id"><i class="fas fa-graduation-cap"></i> Program</label>
                <select id="program_id" name="program_id" class="form-control" required>
                    <option value="" disabled selected>Select Program</option>
                </select>
            </div>
            <div class="form-group">
                <label for="batch"><i class="fas fa-calendar-alt"></i> Batch</label>
                <input type="text" id="batch" name="batch" class="form-control" placeholder="e.g., 22.1" required>
            </div>
            <div class="form-group">
                <label for="mobile"><i class="fas fa-mobile-alt"></i> Mobile Number</label>
                <input type="text" id="mobile" name="mobile" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Register</button>
        </form>
    </div>
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
                    if(response.length > 0) {
                        $.each(response, function(key, value) {
                            $('#program_id').append('<option value="' + value.id + '">' + value.program_name + '</option>');
                        });
                    }
                }
            });
        } else {
            $('#program_id').empty().append('<option value="" disabled selected>Select Program</option>');
        }
    });
});
</script>
</body>
</html>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>