<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/lecturer_header.php';
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Content</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/upload-lecturer-content-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container">
    <div class="upload-lecturer-content-card">
        <a href="dashboard_lecturer.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <hr>
        <h2><i class="fas fa-upload"></i> Upload Content</h2>
        <?php if (empty($modules)): ?>
            <div class="alert alert-warning mt-4">
                <p><i class="fas fa-exclamation-triangle"></i> No modules have been assigned to your program yet.</p>
            </div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($modules as $module): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><?php echo htmlspecialchars($module['name']); ?> <?php echo htmlspecialchars($module['code']); ?></span>
                        <div class="btn-group" role="group">
                            <a href="view-notes.php?module_id=<?php echo $module['id']; ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i> View Content</a>
                            <a href="upload-content-form.php?module_id=<?php echo $module['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-cloud-upload-alt"></i> Upload Content</a>
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