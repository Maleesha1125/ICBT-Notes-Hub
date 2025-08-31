<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Department.php';
require_once __DIR__ . '/../includes/admin-header.php';

$auth = new Auth($db);
if (!$auth->hasRole('admin')) {
    header('Location: index.php');
    exit;
}

$department_class = new Department($db);
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $name = $_POST['name'];
    $code = $_POST['code'];
    if (!empty($name) && !empty($code)) {
        if ($department_class->addDepartment($name, $code)) {
            $message = "Department added successfully!";
        } else {
            $message = "Error adding department.";
        }
    } else {
        $message = "Name and Code cannot be empty.";
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($department_class->deleteDepartment($id)) {
        $message = "Department deleted successfully!";
    } else {
        $message = "Error deleting department.";
    }
}

$departments = $department_class->getAllDepartments();
?>
<div class="container">
    <h2>Manage Departments</h2>
    <a href="dashboard.php" class="btn-secondary">Back to Dashboard</a>
    <hr>
    <?php if ($message): ?>
        <div class="alert"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <h3>Add New Department</h3>
    <form action="manage-departments.php" method="POST">
        <input type="hidden" name="action" value="add">
        <div class="form-group">
            <label for="name">Department Name:</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label for="code">Department Code:</label>
            <input type="text" name="code" required>
        </div>
        <button type="submit" class="btn-primary">Add Department</button>
    </form>

    <hr>

    <h3>Existing Departments</h3>
    <?php if (!empty($departments)): ?>
        <table border="1" class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departments as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['code']); ?></td>
                        <td>
                            <a href="edit-department.php?id=<?php echo $row['id']; ?>" class="btn-secondary">Edit</a>
                            <a href="manage-departments.php?action=delete&id=<?php echo $row['id']; ?>" class="btn-danger" onclick="return confirm('Are you sure you want to delete this department?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No departments found.</p>
    <?php endif; ?>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>