<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'icbt_notes_hub');

$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($db === false){
    die("ERROR: Could not connect. " . $db->connect_error);
}

$db->set_charset("utf8mb4");
?>