<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Content.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'lecturer') {
    header('Location: ../public/login.php');
    exit;
}
$content_class = new Content($db);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upload_type = $_POST['upload_type'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $program_id = $_SESSION['program_id'];
    $module_id = $_POST['module_id'];
    $user_id = $_SESSION['user_id'];
    if ($upload_type === 'link') {
        $url = $_POST['url'] ?? '';
        if ($content_class->uploadLink($title, $url, $description, $user_id, $program_id, $module_id)) {
            header('Location: ../public/dashboard_lecturer.php?status=success');
            exit;
        } else {
            header('Location: ../public/dashboard_lecturer.php?status=error');
            exit;
        }
    } else if ($upload_type === 'file') {
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $new_file_name = uniqid() . '.' . $file_ext;
        $upload_dir = __DIR__ . '/../assets/uploads/';
        $file_path = $upload_dir . $new_file_name;
        if (move_uploaded_file($file_tmp, $file_path)) {
            if ($content_class->uploadFile($title, $description, $new_file_name, $file_type, $user_id, $program_id, $module_id)) {
                header('Location: ../public/dashboard_lecturer.php?status=success');
                exit;
            } else {
                header('Location: ../public/dashboard_lecturer.php?status=error');
                exit;
            }
        } else {
            header('Location: ../public/dashboard_lecturer.php?status=error');
            exit;
        }
    }
}
header('Location: ../public/dashboard_lecturer.php');
exit;