<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/User.php';

$auth = new Auth($db);
if (!$auth->hasRole('admin')) {
    header('Location: ../login.php');
    exit;
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $userId = (int)$_GET['id'];
    $newStatus = $_GET['status'];

    if ($newStatus === 'active' || $newStatus === 'inactive') {
        $user_class = new User($db);
        
        if ($user_class->toggleStatus($userId, $newStatus)) {
            header('Location: manage-users.php?message=User status updated successfully.');
        } else {
            header('Location: manage-users.php?error=Failed to update user status.');
        }
    } else {
        header('Location: manage-users.php?error=Invalid status.');
    }
    exit;
}

header('Location: manage-users.php');
exit;