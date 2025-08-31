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
<div class="container">
    <h2>Add New Lecturer</h2>
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php if ($generated_credentials): ?>
            <div class="alert-info">
                <p>Username: <strong><?php echo htmlspecialchars($generated_credentials['username']); ?></strong></p>
                <p>Password: <strong><?php echo htmlspecialchars($generated_credentials['password']); ?></strong></p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="add-lecturer.php">
        <div class="form-group">
            <label for="firstname">First Name</label>
            <input type="text" id="firstname" name="firstname" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="lastname">Last Name</label>
            <input type="text" id="lastname" name="lastname" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Initial Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="department_id">Department</label>
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
            <label for="program_id">Program</label>
            <select name="program_id" id="program_id" class="form-control" required>
                <option value="">Select Program</option>
            </select>
        </div>
        <div class="form-group">
            <label for="mobile">Mobile Number</label>
            <input type="text" id="mobile" name="mobile" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Lecturer</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function() {
    $('#department_id').change(function() {
        var departmentId = $(this).val();
        if (departmentId) {
            $.ajax({
                url: '../includes/get_programs.php',
                type: 'POST',
                data: { department_id: departmentId },
                success: function(response) {
                    $('#program_id').html(response);
                }
            });
        } else {
            $('#program_id').html('<option value="">Select Program</option>');
        }
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>