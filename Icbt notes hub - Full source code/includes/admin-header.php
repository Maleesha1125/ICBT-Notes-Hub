<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/admin-styles.css">
</head>
<body>

<div class="admin-wrapper">
    <header class="admin-header">
        <nav class="admin-nav">
            <ul>
                <li><a href="#" class="nav-link"><i class="fas fa-user-shield"></i> <span>Admin Panel</span></a></li>
                <li><a href="dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="manage-users.php" class="nav-link"><i class="fas fa-users"></i> <span>Manage Users</span></a></li>
                <li><a href="manage-departments.php" class="nav-link"><i class="fas fa-building"></i> <span>Manage Departments</span></a></li>
                <li><a href="manage-programs.php" class="nav-link"><i class="fas fa-graduation-cap"></i> <span>Manage Programs</span></a></li>
                <li><a href="manage-modules.php" class="nav-link"><i class="fas fa-book"></i> <span>Manage Modules</span></a></li>
                <li><a href="manage-feedbacks.php" class="nav-link"><i class="fas fa-comments"></i> <span>Manage Feedbacks</span></a></li>
                <li><a href="profile.php" class="nav-link"><i class="fas fa-user-circle"></i> <span>Admin Profile</span></a></li>
            </ul>
        </nav>
        <a href="../admin/logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </header>
    <main class="admin-main-content">