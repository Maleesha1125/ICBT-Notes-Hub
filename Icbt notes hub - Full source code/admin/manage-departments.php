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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departments</title>
    <link rel="stylesheet" href="../assets/css/manage-departments-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container manage-departments-container">
    <div class="manage-departments-card">
        <h2><i class="fas fa-building"></i> Manage Departments</h2>
        <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <hr>
        <?php if ($message): ?>
            <div class="alert alert-info"><i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <h3><i class="fas fa-plus-circle"></i> Add New Department</h3>
        <form action="manage-departments.php" method="POST" class="add-form">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="name"><i class="fas fa-tag"></i> Department Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="code"><i class="fas fa-barcode"></i> Department Code:</label>
                <input type="text" id="code" name="code" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Department</button>
        </form>
        <hr>
        <h3><i class="fas fa-list-alt"></i> Existing Departments</h3>
        <?php if (!empty($departments)): ?>
            <table class="content-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-building"></i> Name</th>
                        <th><i class="fas fa-barcode"></i> Code</th>
                        <th><i class="fas fa-cogs"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($departments as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['code']); ?></td>
                            <td>
                                <a href="edit-department.php?id=<?php echo $row['id']; ?>" class="btn-action edit"><i class="fas fa-edit"></i> Edit</a>
                                <a href="manage-departments.php?action=delete&id=<?php echo $row['id']; ?>" class="btn-action delete" onclick="return confirm('Are you sure you want to delete this department?');"><i class="fas fa-trash-alt"></i> Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><i class="fas fa-info-circle"></i> No departments found.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>