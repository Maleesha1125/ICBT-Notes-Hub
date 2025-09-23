<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Program.php';
require_once __DIR__ . '/../classes/Department.php';
require_once __DIR__ . '/../classes/Auth.php';

session_start();
$auth = new Auth($db);
if (!$auth->hasRole('admin')) {
    header('Location: index.php');
    exit;
}

$user_class = new User($db);
$program_class = new Program($db);
$department_class = new Department($db);
$message = '';
$error = '';
$departments = $department_class->getAllDepartments();
$programs = [];
$generated_credentials = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $department_id = $_POST['department_id'] ?? null;
    $program_id = $_POST['program_id'] ?? null;
    $mobile = $_POST['mobile'] ?? null;

    if (empty($firstname) || empty($lastname) || empty($username) || empty($password) || empty($department_id) || empty($program_id) || empty($mobile)) {
        $error = "All fields are required.";
    } elseif ($user_class->checkUsernameExists($username)) {
        $error = "A user with this username already exists.";
    } else {
        if ($user_class->addUserManually($firstname, $lastname, $username, $password, 'lecturer', $department_id, $program_id, $mobile)) {
            $message = "Lecturer registered successfully!";
            $generated_credentials = ['username' => $username, 'password' => $password];
        } else {
            $error = "Error registering lecturer. Please try again.";
        }
    }
}

require_once __DIR__ . '/../includes/admin-header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Lecturer</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/add-lecturer-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container add-lecturer-container">
    <div class="add-lecturer-card">
        <h2><i class="fas fa-user-plus"></i> Add New Lecturer</h2>
        <?php if ($message): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?></div>
            <?php if ($generated_credentials): ?>
                <div class="alert-info mt-3">
                    <p><i class="fas fa-user"></i> Username: <strong><?php echo htmlspecialchars($generated_credentials['username']); ?></strong></p>
                    <p><i class="fas fa-lock"></i> Password: <strong><?php echo htmlspecialchars($generated_credentials['password']); ?></strong></p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="add-lecturer.php" class="mt-4">
            <div class="form-group">
                <label for="firstname"><i class="fas fa-user-circle"></i> First Name</label>
                <input type="text" id="firstname" name="firstname" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="lastname"><i class="fas fa-user-circle"></i> Last Name</label>
                <input type="text" id="lastname" name="lastname" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="username"><i class="fas fa-id-badge"></i> Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password"><i class="fas fa-key"></i> Initial Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="department_id"><i class="fas fa-building"></i> Department</label>
                <select name="department_id" id="department_id" class="form-control" required>
                    <option value="">Select Department</option>
                    <?php
                    if (isset($departments) && is_array($departments)) {
                        foreach ($departments as $dept) {
                            echo "<option value='{$dept['id']}'>".htmlspecialchars($dept['name'])."</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="program_id"><i class="fas fa-graduation-cap"></i> Program</label>
                <select name="program_id" id="program_id" class="form-control" required>
                    <option value="">Select Program</option>
                </select>
            </div>
            <div class="form-group">
                <label for="mobile"><i class="fas fa-phone-alt"></i> Mobile Number</label>
                <input type="text" id="mobile" name="mobile" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Add Lecturer</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function() {
    $('#department_id').change(function() {
        var departmentId = $(this).val();
        if (departmentId) {
            $.ajax({
                url: '../modules/fetch-programs.php',
                type: 'POST',
                data: { department_id: departmentId },
                dataType: 'json',
                success: function(programs) {
                    var programOptions = '<option value="">Select Program</option>';
                    $.each(programs, function(i, program) {
                        programOptions += '<option value="' + program.id + '">' + program.program_name + '</option>';
                    });
                    $('#program_id').html(programOptions);
                }
            });
        } else {
            $('#program_id').html('<option value="">Select Program</option>');
        }
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>