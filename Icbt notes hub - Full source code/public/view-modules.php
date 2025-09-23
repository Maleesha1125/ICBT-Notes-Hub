<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Module.php';
require_once __DIR__ . '/../classes/User.php';
if ($_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}
$user_class = new User($db);
$user = $user_class->getUserById($_SESSION['user_id']);
$modules = [];
if (isset($user['program_id']) && $user['program_id'] !== null) {
    $module_class = new Module($db);
    $modules = $module_class->getModulesByProgram($user['program_id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Modules</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/view-modules-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container">
    <div class="modules-card">
        <h2><i class="fas fa-book"></i> Your Modules</h2>
        <hr>
        <a href="dashboard_student.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <hr>
        <?php if (empty($modules)): ?>
            <p><i class="fas fa-exclamation-triangle"></i> No modules assigned to your program yet.</p>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($modules as $module): ?>
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><?php echo htmlspecialchars($module['name']); ?> <?php echo htmlspecialchars($module['code']); ?></span>
                        <div class="btn-group" role="group">
                            <a href="view-notes.php?module_id=<?php echo $module['id']; ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i> View</a>
                            <a href="upload-content.php?module_id=<?php echo $module['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-cloud-upload-alt"></i> Upload</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>