<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Note.php';
require_once __DIR__ . '/../classes/Module.php';
require_once __DIR__ . '/../classes/Program.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || !isset($_GET['module_id'])) {
    header('Location: login.php');
    exit;
}

$module_id = $_GET['module_id'];
$module_class = new Module($db);
$module = $module_class->getModuleById($module_id);

if (!$module) {
    header('Location: dashboard_student.php');
    exit;
}

$program_class = new Program($db);
$program = $program_class->getProgramById($module['program_id']);
$department_id = $program['department_id'] ?? null;
$program_id = $module['program_id'];

$note_class = new Note($db);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $document_path = '';
    $content_type = '';

    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . '/../uploads/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = uniqid() . '_' . basename($_FILES['document']['name']);
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['document']['tmp_name'], $target_file)) {
            $document_path = 'uploads/' . $file_name;
            $content_type = $_POST['content_type'] ?? 'lecture_notes';
        } else {
            $error = 'Failed to upload file.';
        }
    } elseif (!empty($_POST['link'])) {
        $document_path = $_POST['link'];
        $content_type = $_POST['content_type'] ?? 'link';
    }

    if (empty($error)) {
        $result = $note_class->uploadNote(
            $_SESSION['user_id'],
            $module_id,
            $title,
            $description,
            $content_type,
            $document_path,
            $department_id,
            $program_id
        );

        if ($result) {
            $success = 'Content uploaded successfully!';
        } else {
            $error = 'Failed to upload Content.';
        }
    }
}
require_once __DIR__ . '/../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Content</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/upload-notes-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container upload-content-container">
    <a href="view-modules.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Back to Modules</a>
    <h2 class="text-center my-4"><i class="fas fa-upload"></i> Upload Content for <?php echo htmlspecialchars($module['name']); ?></h2>
    <?php if ($success): ?>
        <div class="alert alert-success text-center"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <div class="upload-form-card">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label"><i class="fas fa-heading"></i> Title</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label"><i class="fas fa-align-left"></i> Description</label>
                <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
            </div>
            
            <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'lecturer' || $_SESSION['role'] === 'admin')): ?>
            <div class="mb-3">
                <label for="content_type" class="form-label"><i class="fas fa-file-alt"></i> Content Type</label>
                <select id="content_type" name="content_type" class="form-select" required>
                    <option value="lecture_notes">Lecture Notes</option>
                    <option value="link">Module Link</option>
                    <option value="quiz">Quiz</option>
                    <option value="notice">Important Notice</option>
                </select>
            </div>
            <?php else: ?>
            <input type="hidden" name="content_type" value="lecture_notes">
            <?php endif; ?>

            <div class="mb-3">
                <label for="document" class="form-label"><i class="fas fa-file-upload"></i> Upload Document</label>
                <input type="file" id="document" name="document" class="form-control">
            </div>
            <div class="mb-4">
                <label for="link" class="form-label"><i class="fas fa-link"></i> Related Links</label>
                <input type="url" id="link" name="link" class="form-control">
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Content</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>