<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Modules</title>
    <link rel="stylesheet" href="../assets/css/manage-modules-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container manage-modules-container">
    <div class="manage-modules-card">
        <h2><i class="fas fa-book-open"></i> Manage Modules</h2>
        <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <hr>
        <?php if ($message): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <h3><?php echo $edit_module ? '<i class="fas fa-edit"></i> Edit Module' : '<i class="fas fa-plus-circle"></i> Add New Module'; ?></h3>
        <form action="manage-modules.php" method="POST" class="add-edit-form">
            <?php if ($edit_module): ?>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_module['id']); ?>">
            <?php else: ?>
                <input type="hidden" name="action" value="add">
            <?php endif; ?>
            <div class="form-group">
                <label for="name"><i class="fas fa-book"></i> Module Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($edit_module['name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="code"><i class="fas fa-barcode"></i> Module Code:</label>
                <input type="text" id="code" name="code" class="form-control" value="<?php echo htmlspecialchars($edit_module['code'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="description"><i class="fas fa-info-circle"></i> Description:</label>
                <textarea id="description" name="description" class="form-control"><?php echo htmlspecialchars($edit_module['description'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="department_id"><i class="fas fa-building"></i> Department:</label>
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
                <label for="program_id"><i class="fas fa-graduation-cap"></i> Program:</label>
                <select name="program_id" id="program_id" class="form-control" required>
                    <option value="">Select Program</option>
                    <?php if ($edit_module): ?>
                        <?php foreach ($programs_in_department as $program): ?>
                            <option value="<?php echo htmlspecialchars($program['id']); ?>" <?php echo ($edit_module['program_id'] == $program['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($program['program_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <?php if ($edit_module): ?>
                    <i class="fas fa-save"></i> Update Module
                <?php else: ?>
                    <i class="fas fa-plus"></i> Add Module
                <?php endif; ?>
            </button>
            <?php if ($edit_module): ?>
                <a href="manage-modules.php" class="btn btn-secondary"><i class="fas fa-times-circle"></i> Cancel</a>
            <?php endif; ?>
        </form>

        <hr>

        <h3><i class="fas fa-list-alt"></i> Existing Modules</h3>
        <?php if (!empty($modules)): ?>
            <table class="content-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-book"></i> Name</th>
                        <th><i class="fas fa-barcode"></i> Code</th>
                        <th><i class="fas fa-info-circle"></i> Description</th>
                        <th><i class="fas fa-graduation-cap"></i> Program</th>
                        <th><i class="fas fa-cogs"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modules as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['code']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <?php
                                $program_of_module = $program_class->getProgramById($row['program_id']);
                                $program_name = $program_of_module ? $program_of_module['name'] : '-';
                            ?>
                            <td><?php echo htmlspecialchars($program_name); ?></td>
                            <td>
                                <a href="manage-modules.php?action=edit&id=<?php echo $row['id']; ?>" class="btn-action edit"><i class="fas fa-edit"></i> Edit</a>
                                <a href="manage-modules.php?action=delete&id=<?php echo $row['id']; ?>" class="btn-action delete" onclick="return confirm('Are you sure you want to delete this module?');"><i class="fas fa-trash-alt"></i> Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><i class="fas fa-info-circle"></i> No modules found.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">Select Program</option>';
                data.forEach(program => {
                    options += '<option value="' + program.id + '">' + program.program_name + '</option>';
                });
                programSelect.innerHTML = options;
            })
            .catch(error => console.error('Error fetching programs:', error));
        } else {
            programSelect.innerHTML = '<option value="">Select Program</option>';
        }
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>