<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Auth.php';

$auth = new Auth($db);
$auth->logout();
header('Location: login.php');
exit;
?>