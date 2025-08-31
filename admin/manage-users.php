<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Department.php';
require_once __DIR__ . '/../classes/Program.php';
require_once __DIR__ . '/../includes/admin-header.php';

$auth = new Auth($db);
if (!$auth->hasRole('admin')) {
    header('Location: ../login.php');
    exit;
}

$user_class = new User($db);
$department_class = new Department($db);
$program_class = new Program($db);

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_user') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $mobile = $_POST['mobile'];

    $department_id = !empty($_POST['department_id']) ? (int)$_POST['department_id'] : null;
    $program_id = !empty($_POST['program_id']) ? (int)$_POST['program_id'] : null;
    
    if ($user_class->addUserManually($firstname, $lastname, $username, $email, $password, $role, $department_id, $program_id, $mobile)) {
        $message = "User added successfully!";
    } else {
        $error = "Error adding user. Username may already exist.";
    }
}

if (isset($_GET['reset_id']) && is_numeric($_GET['reset_id'])) {
    $user_id = intval($_GET['reset_id']);
    $new_password = bin2hex(random_bytes(8));
    if ($user_class->resetPassword($user_id, $new_password)) {
        $message = "Password for user ID " . $user_id . " has been reset to: " . $new_password;
    } else {
        $error = "Error resetting password.";
    }
}

if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}
if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}

$users = $user_class->getAllUsers();
$departments = $department_class->getAllDepartments();
?>

<div class="container-fluid">
    <div class="admin-header mb-4">
        <h2>Manage Users</h2>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Add New User</h5>
            <form method="POST" action="manage-users.php" class="user-form">
                <input type="hidden" name="action" value="add_user">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" id="firstname" name="firstname" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" id="lastname" name="lastname" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" class="form-select" required>
                            <option value="student">Student</option>
                            <option value="lecturer">Lecturer</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select id="department_id" name="department_id" class="form-select">
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo htmlspecialchars($dept['id']); ?>">
                                    <?php echo htmlspecialchars($dept['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="program_id" class="form-label">Program</label>
                        <select id="program_id" name="program_id" class="form-select">
                            <option value="">Select Program</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="mobile" class="form-label">Mobile</label>
                        <input type="text" id="mobile" name="mobile" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Add User</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Existing Users</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Program</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td><?php echo htmlspecialchars($user['department_name'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($user['program_name'] ?? '-'); ?></td>
                                <td>
                                    <span class="badge <?php echo $user['status'] === 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo htmlspecialchars($user['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                        <a href="manage-users.php?reset_id=<?php echo htmlspecialchars($user['id']); ?>"
                                           class="btn btn-sm btn-secondary"
                                           onclick="return confirm('Are you sure you want to reset the password for this user?');">
                                            <i class="fas fa-key"></i> Reset Password
                                        </a>
                                        <a href="toggle-user-status.php?id=<?php echo htmlspecialchars($user['id']); ?>&status=<?php echo $user['status'] === 'active' ? 'inactive' : 'active'; ?>"
                                           class="btn btn-sm <?php echo $user['status'] === 'active' ? 'btn-warning' : 'btn-success'; ?>"
                                           onclick="return confirm('Are you sure you want to <?php echo $user['status'] === 'active' ? 'deactivate' : 'activate'; ?> this user?');">
                                            <i class="fas fa-power-off"></i> <?php echo $user['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById('department_id');
    const programSelect = document.getElementById('program_id');

    departmentSelect.addEventListener('change', function() {
        const departmentId = this.value;
        if (departmentId) {
            fetch('../modules/fetch-programs.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'department_id=' + departmentId
            })
            .then(response => response.text())
            .then(data => {
                programSelect.innerHTML = '<option value="">Select Program</option>' + data;
            })
            .catch(error => console.error('Error fetching programs:', error));
        } else {
            programSelect.innerHTML = '<option value="">Select Program</option>';
        }
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>