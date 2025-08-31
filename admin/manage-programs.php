```php
<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Program.php';
require_once __DIR__ . '/../classes/Department.php';
require_once __DIR__ . '/../includes/admin-header.php';
$auth = new Auth($db);
if (!$auth->hasRole('admin')) {
    header('Location: ../login.php');
    exit;
}
$program_class = new Program($db);
$department_class = new Department($db);
$message = '';
$error = '';
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $program_id = intval($_GET['delete_id']);
    if ($program_class->deleteProgram($program_id)) {
        $message = "Program deleted successfully!";
        header('Location: manage-programs.php?message=' . urlencode($message));
        exit;
    } else {
        $error = "Error deleting program. It might be associated with modules.";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['program_name'])) {
    $program_name = $_POST['program_name'];
    $program_code = $_POST['program_code'];
    $department_id = $_POST['department_id'];
    $duration = $_POST['duration'];
    $degree_type = $_POST['degree_type'];
    if (empty($program_name) || empty($department_id) || empty($program_code) || empty($duration) || empty($degree_type)) {
        $error = "All fields are required.";
    } else {
        if ($program_class->addProgram($program_name, $program_code, $department_id, $duration, $degree_type)) {
            $message = "Program added successfully!";
        } else {
            $error = "Error adding program. It may already exist.";
        }
    }
}
$programs = $program_class->getAllPrograms();
$departments = $department_class->getAllDepartments();
if (isset($_GET['message'])) {
    $message = urldecode($_GET['message']);
}
?>
<div class="container-fluid">
    <div class="admin-header mb-4">
        <h2>Manage Programs</h2>
    </div>
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Add New Program</h5>
            <form method="POST" action="manage-programs.php">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="program_name" class="form-label">Program Name</label>
                        <input type="text" id="program_name" name="program_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="program_code" class="form-label">Program Code</label>
                        <input type="text" id="program_code" name="program_code" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select id="department_id" name="department_id" class="form-select" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo htmlspecialchars($dept['id']); ?>">
                                    <?php echo htmlspecialchars($dept['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="duration" class="form-label">Duration (in years)</label>
                        <input type="number" id="duration" name="duration" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="degree_type" class="form-label">Degree Type</label>
                    <input type="text" id="degree_type" name="degree_type" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Program</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Existing Programs</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Program Name</th>
                            <th>Department</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($programs as $program): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($program['name']); ?></td>
                                <td><?php echo htmlspecialchars($program['department_name']); ?></td>
                                <td>
                                    <a href="manage-programs.php?delete_id=<?php echo htmlspecialchars($program['id']); ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this program?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>
```