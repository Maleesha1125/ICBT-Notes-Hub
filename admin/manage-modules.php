<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Module.php';
require_once __DIR__ . '/../classes/Program.php';
require_once __DIR__ . '/../classes/Department.php';
require_once __DIR__ . '/../includes/admin-header.php';

$auth = new Auth($db);
if (!$auth->hasRole('admin')) {
    header('Location: ../login.php');
    exit;
}

$module_class = new Module($db);
$program_class = new Program($db);
$department_class = new Department($db);
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $name = $_POST['name'];
        $code = $_POST['code'];
        $description = $_POST['description'];
        $program_id = $_POST['program_id'];
        if ($module_class->createModule($name, $code, $description, $program_id)) {
            $message = "Module added successfully!";
        } else {
            $error = "Error adding module.";
        }
    } elseif ($_POST['action'] == 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $code = $_POST['code'];
        $description = $_POST['description'];
        $program_id = $_POST['program_id'];
        if ($module_class->updateModule($id, $name, $code, $description, $program_id)) {
            $message = "Module updated successfully!";
        } else {
            $error = "Error updating module.";
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    if ($module_class->deleteModule($_GET['id'])) {
        $message = "Module deleted successfully!";
    } else {
        $error = "Error deleting module.";
    }
}

$modules = $module_class->getAllModules();
$departments = $department_class->getAllDepartments();
$edit_module = null;
$programs_in_department = [];

if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $edit_module = $module_class->getModuleById($_GET['id']);
    if ($edit_module) {
        $program_of_module = $program_class->getProgramById($edit_module['program_id']);
        if ($program_of_module) {
            $programs_in_department = $program_class->getProgramsByDepartment($program_of_module['department_id']);
        }
    }
}
?>
<div class="container">
    <h2>Manage Modules</h2>
    <a href="dashboard.php" class="btn-secondary">Back to Dashboard</a>
    <hr>
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <h3><?php echo $edit_module ? 'Edit Module' : 'Add New Module'; ?></h3>
    <form action="manage-modules.php" method="POST">
        <?php if ($edit_module): ?>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_module['id']); ?>">
        <?php else: ?>
            <input type="hidden" name="action" value="add">
        <?php endif; ?>
        <div class="form-group">
            <label for="name">Module Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($edit_module['name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="code">Module Code:</label>
            <input type="text" name="code" value="<?php echo htmlspecialchars($edit_module['code'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" class="form-control"><?php echo htmlspecialchars($edit_module['description'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="department_id">Department:</label>
            <select name="department_id" id="department_id" class="form-control" required>
                <option value="">Select Department</option>
                <?php foreach ($departments as $dept): ?>
                    <option value="<?php echo htmlspecialchars($dept['id']); ?>" 
                        <?php echo (isset($program_of_module) && $program_of_module['department_id'] == $dept['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($dept['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="program_id">Program:</label>
            <select name="program_id" id="program_id" class="form-control" required>
                <option value="">Select Program</option>
                <?php if ($edit_module): ?>
                    <?php foreach ($programs_in_department as $program): ?>
                        <option value="<?php echo htmlspecialchars($program['id']); ?>" <?php echo ($edit_module['program_id'] == $program['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($program['name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <button type="submit" class="btn-primary"><?php echo $edit_module ? 'Update Module' : 'Add Module'; ?></button>
        <?php if ($edit_module): ?>
            <a href="manage-modules.php" class="btn-secondary">Cancel</a>
        <?php endif; ?>
    </form>
    <hr>
    <h3>Existing Modules</h3>
    <?php if (!empty($modules)): ?>
        <table border="1" class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Program</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($modules as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['code']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['program_name']); ?></td>
                        <td>
                            <a href="manage-modules.php?action=edit&id=<?php echo $row['id']; ?>" class="btn-secondary">Edit</a>
                            <a href="manage-modules.php?action=delete&id=<?php echo $row['id']; ?>" class="btn-danger" onclick="return confirm('Are you sure you want to delete this module?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No modules found.</p>
    <?php endif; ?>
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
                success: function(data) {
                    var programSelect = $('#program_id');
                    programSelect.empty();
                    programSelect.append(data);
                }
            });
        } else {
            $('#program_id').html('<option value="">Select Program</option>');
        }
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>