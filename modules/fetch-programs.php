<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Program.php';

header('Content-Type: text/html');

if (isset($_POST['department_id']) && is_numeric($_POST['department_id'])) {
    $department_id = (int)$_POST['department_id'];
    $program_class = new Program($db);
    $programs = $program_class->getProgramsByDepartment($department_id);
    
    $options = '';
    foreach ($programs as $program) {
        $options .= '<option value="' . htmlspecialchars($program['id']) . '">' . htmlspecialchars($program['name']) . '</option>';
    }
    echo $options;
}
?>