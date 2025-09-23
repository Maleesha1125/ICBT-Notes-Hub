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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Department</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/edit-department-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container edit-department-container">
    <div class="edit-department-card">
        <h2><i class="fas fa-edit"></i> Edit Department</h2>
        <?php if ($message): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($department): ?>
            <form method="POST" class="mt-4">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($department['id']); ?>">
                <div class="form-group">
                    <label for="name"><i class="fas fa-building"></i> Name:</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($department['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="code"><i class="fas fa-barcode"></i> Code:</label>
                    <input type="text" id="code" name="code" class="form-control" value="<?php echo htmlspecialchars($department['code']); ?>" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Department</button>
                    <a href="manage-departments.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancel</a>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-danger">
                <i class="fas fa-times-circle"></i> Department not found.
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>