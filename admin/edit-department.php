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

if (isset($_GET['id'])) {
    $department = $department_class->getDepartmentById($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $code = $_POST['code'];

    if ($department_class->updateDepartment($id, $name, $code)) {
        $message = "Department updated successfully!";
        $department = $department_class->getDepartmentById($id);
    } else {
        $message = "Failed to update department.";
    }
}
?>

<div class="container">
    <h2>Edit Department</h2>
    <?php if ($message): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if ($department): ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($department['id']); ?>">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($department['name']); ?>" required>
        </div>
        <div class="form-group">
            <label>Code:</label>
            <input type="text" name="code" value="<?php echo htmlspecialchars($department['code']); ?>" required>
        </div>
        <button type="submit" class="btn-primary">Update Department</button>
        <a href="manage-departments.php" class="btn-secondary">Cancel</a>
    </form>
    <?php else: ?>
    <p>Department not found.</p>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>