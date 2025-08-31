<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Module.php';
require_once __DIR__ . '/../includes/config.php';

if ($_SESSION['role'] !== 'lecturer') {
    header('Location: login.php');
    exit;
}

$user_class = new User($db);
$module_class = new Module($db);

$lecturer_id = $_SESSION['user_id'];
$lecturer = $user_class->getUserById($lecturer_id);

$modules = [];
if (isset($lecturer['program_id']) && $lecturer['program_id'] !== null) {
    $modules = $module_class->getModulesByProgram($lecturer['program_id']);
}
?>
<div class="container">
    <a href="dashboard_lecturer.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
    <hr>
    <h2>Upload Content</h2>
    <?php if (empty($modules)): ?>
        <div class="alert alert-warning mt-4">
            <p>No modules have been assigned to your program yet.</p>
        </div>
    <?php else: ?>
        <h3 class="mt-4">Your Modules</h3>
        <div class="list-group">
            <?php foreach ($modules as $module): ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span><?php echo htmlspecialchars($module['name']); ?> (<?php echo htmlspecialchars($module['code']); ?>)</span>
                    <div class="btn-group" role="group">
                        <a href="view-notes.php?module_id=<?php echo $module['id']; ?>" class="btn btn-sm btn-outline-info">View Content</a>
                        <a href="upload-content-form.php?module_id=<?php echo $module['id']; ?>" class="btn btn-sm btn-outline-primary">Upload Content</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>