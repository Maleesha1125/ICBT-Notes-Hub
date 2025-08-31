<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/LecturerContent.php';
require_once __DIR__ . '/../classes/Module.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Lecturer.php';
if (!isset($_GET['module_id'])) {
    if ($_SESSION['role'] === 'lecturer') {
        header('Location: upload-lecturer-content.php');
    } else {
        header('Location: dashboard_student.php');
    }
    exit;
}
$module_id = $_GET['module_id'];
$lecturer_id = $_SESSION['user_id'] ?? null;
$module_class = new Module($db);
$module = $module_class->getModuleById($module_id);
$content_class = new LecturerContent($db);
$contents = $content_class->getModuleContents($module_id);
$user_class = new User($db);
$lecturer_class = new Lecturer($db);
$is_lecturer_assigned = false;
if ($_SESSION['role'] === 'lecturer' && $lecturer_id) {
    $is_lecturer_assigned = $lecturer_class->isLecturerAssignedToModule($lecturer_id, $module_id);
}
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <h2>Content for <?php echo htmlspecialchars($module['name']); ?></h2>
    <?php if ($_SESSION['role'] === 'lecturer'): ?>
        <a href="upload-lecturer-content.php" class="btn btn-secondary">Back to Modules</a>
    <?php else: ?>
        <a href="view-modules.php" class="btn btn-secondary">Back to Modules</a>
    <?php endif; ?>
    <hr>
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($contents)): ?>
        <ul class="list-group">
            <?php foreach ($contents as $content): ?>
                <li class="list-group-item">
                    <h5><?php echo htmlspecialchars($content['title']); ?></h5>
                    <p><?php echo htmlspecialchars($content['description']); ?></p>
                    <small>
                        Uploaded by: <?php
                            $uploader = $user_class->getUserById($content['uploaded_by']);
                            if ($uploader) {
                                echo htmlspecialchars($uploader['firstname'] . ' ' . $uploader['lastname']);
                            } else {
                                echo 'Unknown User';
                            }
                        ?>
                    </small><br>
                    <small>
                        Uploaded on: <?php echo date('Y-m-d', strtotime($content['upload_date'])); ?>
                    </small><br>
                    <?php if (!empty($content['reviewed_by'])): ?>
                    <small class="text-muted">
                        Last updated on: <?php echo date('Y-m-d', strtotime($content['review_date'])); ?> by: <?php
                            $reviewer = $user_class->getUserById($content['reviewed_by']);
                            if ($reviewer) {
                                echo htmlspecialchars($reviewer['firstname'] . ' ' . $reviewer['lastname']);
                            } else {
                                echo 'Unknown User';
                            }
                        ?>
                        (<?php echo htmlspecialchars($content['review_notes']); ?>)
                    </small><br>
                    <?php endif; ?>
                    <div class="mt-2 content-actions">
                        <?php if ($content['content_type'] === 'link'): ?>
                            <a href="<?php echo htmlspecialchars($content['file_path']); ?>" target="_blank" class="btn btn-primary btn-sm">Go to Link</a>
                        <?php else: ?>
                            <a href="../uploads/<?php echo htmlspecialchars(basename($content['file_path'])); ?>" target="_blank" class="btn btn-primary btn-sm">View</a>
                            <a href="../uploads/<?php echo htmlspecialchars(basename($content['file_path'])); ?>" class="btn btn-success btn-sm" download>Download</a>
                        <?php endif; ?>
                        <?php if ($_SESSION['role'] === 'lecturer' && $is_lecturer_assigned): ?>
                            <a href="manage-content.php?program_id=<?php echo $module['program_id']; ?>&module_id=<?php echo $module_id; ?>&action=edit&content_id=<?php echo $content['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="#" class="btn-danger" onclick="showDeleteModal('<?php echo $content['id']; ?>', '<?php echo htmlspecialchars(addslashes($content['title'])); ?>', '<?php echo $module['program_id']; ?>', '<?php echo $module_id; ?>')">Delete</a>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-info">
            No notes available for this module yet.
        </div>
    <?php endif; ?>
</div>
<div id="deleteModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeDeleteModal()">&times;</span>
        <form id="deleteForm" action="manage-content.php" method="POST">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="content_id" id="deleteContentId">
            <input type="hidden" name="program_id" id="deleteProgramId">
            <input type="hidden" name="module_id" id="deleteModuleId">
            <p>Are you sure you want to delete <strong id="deleteContentTitle"></strong>?</p>
            <button type="submit" class="btn-danger">Confirm Delete</button>
            <button type="button" class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
        </form>
    </div>
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
    function showDeleteModal(id, title, program_id, module_id) {
        document.getElementById('deleteContentId').value = id;
        document.getElementById('deleteContentTitle').innerText = title;
        document.getElementById('deleteProgramId').value = program_id;
        document.getElementById('deleteModuleId').value = module_id;
        document.getElementById('deleteModal').style.display = 'block';
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>