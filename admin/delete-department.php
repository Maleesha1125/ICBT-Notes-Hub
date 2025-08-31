<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Department.php';

$auth = new Auth($db);
if (!$auth->hasRole('admin')) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id'])) {
    $department_class = new Department($db);
    if ($department_class->deleteDepartment($_GET['id'])) {
        header('Location: manage-departments.php?message=Department deleted successfully!');
    } else {
        header('Location: manage-departments.php?message=Failed to delete department.');
    }
    exit;
} else {
    header('Location: manage-departments.php');
    exit;
}
?>