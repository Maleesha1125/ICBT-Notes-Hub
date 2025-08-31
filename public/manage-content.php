<?php
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Lecturer.php';
require_once __DIR__ . '/../classes/LecturerContent.php';
require_once __DIR__ . '/../classes/Module.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Department.php';

if ($_SESSION['role'] !== 'lecturer') {
    header('Location: login.php');
    exit;
}

$lecturer_class = new Lecturer($db);
$content_class = new LecturerContent($db);
$user_class = new User($db);
$module_class = new Module($db);
$department_class = new Department($db);

$lecturer_id = $_SESSION['user_id'];
$message = '';

$lecturer = $user_class->getUserById($lecturer_id);
$assigned_modules = [];
if (isset($lecturer['program_id']) && $lecturer['program_id'] !== null) {
    $assigned_modules = $module_class->getModulesByProgram($lecturer['program_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete') {
        $content_id = $_POST['content_id'];
        $module_id = $_POST['module_id'];
        
        $lecturer_details = $user_class->getUserById($lecturer_id);
        $department_details = $department_class->getDepartmentById($lecturer_details['department_id']);
        $review_notes = $lecturer_details['firstname'] . ' ' . $lecturer_details['lastname'] . ' - ' . $department_details['name'];

        if ($content_class->deleteContent($content_id, $lecturer_id, $review_notes)) {
            $_SESSION['message'] = "Content deleted successfully.";
        } else {
            $_SESSION['message'] = "Error deleting content.";
        }
        header("Location: manage-content.php?module_id=" . $module_id);
        exit;
    } elseif ($_POST['action'] === 'update') {
        $content_id = $_POST['content_id'];
        $module_id = $_POST['module_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $upload_type = $_POST['upload_type'];
        $current_content = $content_class->getContentById($content_id);
        $file_path = $current_content['file_path'];
        $url = null;
        if ($upload_type === 'link') {
            $url = $_POST['url'];
            $file_path = null;
        } else {
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $file_tmp_path = $_FILES['file']['tmp_name'];
                $file_name = basename($_FILES['file']['name']);
                $upload_dir = __DIR__ . '/../uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $file_path = $upload_dir . $file_name;
                if (!move_uploaded_file($file_tmp_path, $file_path)) {
                    $_SESSION['message'] = "Error uploading new file.";
                    header("Location: manage-content.php?module_id=" . $module_id);
                    exit;
                }
            }
        }
        $lecturer_details = $user_class->getUserById($lecturer_id);
        $department_details = $department_class->getDepartmentById($lecturer_details['department_id']);
        $review_notes = $lecturer_details['firstname'] . ' ' . $lecturer_details['lastname'] . ' - ' . $department_details['name'];
        if ($content_class->updateContent($content_id, $title, $description, $upload_type, $file_path, $url, $lecturer_id, $review_notes)) {
            $_SESSION['message'] = "Content updated successfully!";
        } else {
            $_SESSION['message'] = "Error updating content.";
        }
        header("Location: manage-content.php?module_id=" . $module_id);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage My Content</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/manage-content-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container">
    <h2>Manage My Content</h2>
    <hr>
    <a href="dashboard_lecturer.php" class="btn btn-secondary">Back to Dashboard</a>
    <hr>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    <?php if (!empty($assigned_modules)): ?>
        <?php foreach ($assigned_modules as $module): ?>
            <div class="module-card">
                <div class="module-header">
                    <h4><?php echo htmlspecialchars($module['name']); ?> (<?php echo htmlspecialchars($module['code']); ?>)</h4>
                </div>
                <div class="module-body">
                    <?php 
                        $module_contents = $content_class->getModuleContents($module['id']);
                        if (!empty($module_contents)): 
                    ?>
                        <table class="content-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($module_contents as $content): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($content['title']); ?></td>
                                        <td>
                                            <a href="#" class="btn btn-edit" onclick="showEditModal('<?php echo $content['id']; ?>', '<?php echo htmlspecialchars(addslashes($content['title'])); ?>', '<?php echo htmlspecialchars(addslashes($content['description'])); ?>', '<?php echo htmlspecialchars(addslashes($content['content_type'])); ?>', '<?php echo htmlspecialchars(addslashes($content['file_path'])); ?>', '<?php echo $module['id']; ?>')">Edit</a>
                                            <a href="#" class="btn btn-delete" onclick="showDeleteModal('<?php echo $content['id']; ?>', '<?php echo htmlspecialchars(addslashes($content['title'])); ?>', '<?php echo $module['id']; ?>')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info">No content available for this module yet.</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning mt-4">
            You are not assigned to any modules.
        </div>
    <?php endif; ?>
</div>

<div id="editModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <form id="editForm" action="manage-content.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="content_id" id="editContentId">
            <input type="hidden" name="module_id" id="editModuleId">
            <h3>Edit Content</h3>
            <div class="form-group">
                <label for="edit_title">Title:</label>
                <input type="text" name="title" id="edit_title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="edit_description">Description:</label>
                <textarea name="description" id="edit_description" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="edit_upload_type">Content Type:</label>
                <select name="upload_type" id="edit_upload_type" class="form-control">
                    <option value="file">File (Notes/Docs)</option>
                    <option value="link">Link (YouTube/Website)</option>
                    <option value="book">Book</option>
                </select>
            </div>
            <div class="form-group" id="edit_file_group">
                <label for="edit_file">File (Leave blank to keep existing file):</label>
                <input type="file" name="file" id="edit_file" class="form-control-file">
            </div>
            <div class="form-group" id="edit_url_group" style="display:none;">
                <label for="edit_url">URL:</label>
                <input type="url" name="url" id="edit_url" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Update Content</button>
            <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>
</div>

<div id="deleteModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close" onclick="closeDeleteModal()">&times;</span>
    <form id="deleteForm" action="manage-content.php" method="POST">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="content_id" id="deleteContentId">
        <input type="hidden" name="module_id" id="deleteModuleId">
        <p>Are you sure you want to delete <strong id="deleteContentTitle"></strong>?</p>
        <button type="submit" class="btn btn-delete">Confirm Delete</button>
        <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
    </form>
  </div>
</div>

<script src="../assets/js/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('#edit_upload_type').change(function() {
        if ($(this).val() === 'link') {
            $('#edit_file_group').hide();
            $('#edit_url_group').show();
        } else {
            $('#edit_file_group').show();
            $('#edit_url_group').hide();
        }
    });
});

function showEditModal(id, title, description, content_type, file_path, module_id) {
    document.getElementById('editContentId').value = id;
    document.getElementById('editModuleId').value = module_id;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_upload_type').value = content_type;

    if (content_type === 'link') {
        document.getElementById('edit_file_group').style.display = 'none';
        document.getElementById('edit_url_group').style.display = 'block';
        document.getElementById('edit_url').value = file_path;
    } else {
        document.getElementById('edit_file_group').style.display = 'block';
        document.getElementById('edit_url_group').style.display = 'none';
        document.getElementById('edit_file').value = '';
    }

    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function showDeleteModal(id, title, module_id) {
    document.getElementById('deleteContentId').value = id;
    document.getElementById('deleteContentTitle').innerText = title;
    document.getElementById('deleteModuleId').value = module_id;
    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
</script>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>