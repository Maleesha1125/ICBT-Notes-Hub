<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/LecturerContent.php';
require_once __DIR__ . '/../classes/Module.php';
require_once __DIR__ . '/../includes/config.php';

if ($_SESSION['role'] !== 'lecturer' || !isset($_GET['module_id'])) {
    header('Location: login.php');
    exit;
}

$module_id = $_GET['module_id'];
$lecturer_id = $_SESSION['user_id'];
$content_class = new LecturerContent($db);
$module_class = new Module($db);
$module = $module_class->getModuleById($module_id);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upload_type = $_POST['upload_type'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $file_path = null;
    $url = null;

    if ($upload_type === 'file' || $upload_type === 'book') {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file_tmp_path = $_FILES['file']['tmp_name'];
            $file_name = basename($_FILES['file']['name']);
            $upload_dir = __DIR__ . '/../uploads/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $file_path = $upload_dir . $file_name;

            if (!move_uploaded_file($file_tmp_path, $file_path)) {
                $message = "Error uploading file.";
            }
        }
    } else if ($upload_type === 'link') {
        $url = $_POST['url'];
    }

    if ($content_class->addContent($module_id, $lecturer_id, $title, $description, $upload_type, $file_path, $url)) {
        $message = "Content uploaded successfully!";
    } else {
        $message = "Error uploading content.";
    }
}
?>
<div class="container">
    <h2>Upload Content for <?php echo htmlspecialchars($module['name']); ?></h2>
    <a href="upload-lecturer-content.php" class="btn btn-secondary mb-3">Back to Modules</a>
    <hr>
    <?php if ($message): ?>
        <div class="alert"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form action="upload-content-form.php?module_id=<?php echo $module_id; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="upload_type">Content Type:</label>
            <select name="upload_type" id="upload_type" class="form-control">
                <option value="file">File (Notes/Docs)</option>
                <option value="link">Link (YouTube/Website)</option>
                <option value="book">Book</option>
            </select>
        </div>
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="form-group" id="file_group">
            <label for="file">File:</label>
            <input type="file" name="file" class="form-control-file">
        </div>
        <div class="form-group" id="url_group" style="display: none;">
            <label for="url">URL:</label>
            <input type="url" name="url" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Upload Content</button>
    </form>
</div>
<script src="../assets/js/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('#upload_type').change(function() {
        var fileGroup = $('#file_group');
        var urlGroup = $('#url_group');
        if ($(this).val() === 'file' || $(this).val() === 'book') {
            fileGroup.show();
            urlGroup.hide();
        } else if ($(this).val() === 'link') {
            fileGroup.hide();
            urlGroup.show();
        }
    });
});
</script>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>