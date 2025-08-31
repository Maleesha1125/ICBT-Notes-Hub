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
<div class="container">
    <h2>Your Modules</h2>
    <hr>
    <a href="dashboard_student.php" class="btn btn-secondary">Back to Dashboard</a>
    <hr>
    <?php if (empty($modules)): ?>
        <p>No modules assigned to your program yet.</p>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($modules as $module): ?>
                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <span><?php echo htmlspecialchars($module['name']); ?> <?php echo htmlspecialchars($module['code']); ?></span>
                    <div class="btn-group" role="group">
                        <a href="view-notes.php?module_id=<?php echo $module['id']; ?>" class="btn btn-sm btn-outline-info">View</a>
                        <a href="upload-content.php?module_id=<?php echo $module['id']; ?>" class="btn btn-sm btn-outline-primary">Upload</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>