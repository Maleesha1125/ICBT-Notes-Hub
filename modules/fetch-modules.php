<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Module.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$response = ['success' => false, 'data' => [], 'message' => 'User not logged in or invalid request.'];
if (isset($_SESSION['user_id']) && isset($_SESSION['program_id'])) {
    $program_id = $_SESSION['program_id'];
    $search_term = $_GET['search'] ?? '';
    $module_class = new Module($db);
    $modules = $module_class->getModulesByProgramIdAndSearch($program_id, $search_term);
    if (!empty($modules)) {
        $response['success'] = true;
        $response['data'] = $modules;
        $response['message'] = 'Modules fetched successfully.';
    } else {
        $response['message'] = 'No modules found for your program matching the search.';
    }
} else {
    $response['message'] = 'User does not have a program assigned.';
}
header('Content-Type: application/json');
echo json_encode($response);
?>