<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Program.php';

$database = new Database();
$db = $database->getConnection();

$program = new Program($db);

$department_id = $_POST['department_id'] ?? null;

$programs_arr = array();

if ($department_id) {
    $programs = $program->getProgramsByDepartment($department_id);
    if ($programs) {
        foreach ($programs as $program_item) {
            $programs_arr[] = $program_item;
        }
    }
}

echo json_encode($programs_arr);
?>