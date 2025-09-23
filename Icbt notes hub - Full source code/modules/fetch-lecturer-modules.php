<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Module.php';
if (isset($_POST['program_id']) && $_SESSION['role'] === 'lecturer') {
    $program_id = $_POST['program_id'];
    $module_obj = new Module($db);
    $modules = $module_obj->getModulesForAjax($program_id);
    echo json_encode($modules);
} else {
    echo json_encode([]);
}
?>