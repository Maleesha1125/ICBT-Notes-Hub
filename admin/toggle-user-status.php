<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Auth.php';

$auth = new Auth($db);
if (!$auth->hasRole('admin')) {
    header('Location: ../login.php');
    exit;
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $userId = (int)$_GET['id'];
    $newStatus = $_GET['status'] === 'active' ? 'active' : 'inactive';

    $stmt = $db->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $userId);
    
    if ($stmt->execute()) {
        header('Location: manage-users.php?message=User status updated successfully.');
    } else {
        header('Location: manage-users.php?error=Failed to update user status.');
    }
    exit;
}

header('Location: manage-users.php');
exit;
?>