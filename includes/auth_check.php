<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../classes/Auth.php';

$auth = new Auth($db);
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}
?>