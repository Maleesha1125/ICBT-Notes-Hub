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
    <div class="left-sidebar">
        <div class="logo-container">
            <h3><i class="fas fa-user-shield"></i> Admin Panel</h3>
        </div>
        <nav class="admin-nav">
            <ul>
                <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="nav-item"><a href="manage-users.php" class="nav-link"><i class="fas fa-users"></i> Manage Users</a></li>
                <li class="nav-item"><a href="manage-departments.php" class="nav-link"><i class="fas fa-building"></i> Manage Departments</a></li>
                <li class="nav-item"><a href="manage-programs.php" class="nav-link"><i class="fas fa-graduation-cap"></i> Manage Programs</a></li>
                <li class="nav-item"><a href="manage-modules.php" class="nav-link"><i class="fas fa-book"></i> Manage Modules</a></li>
                <li class="nav-item"><a href="manage-feedbacks.php" class="nav-link"><i class="fas fa-comments"></i> Manage Feedbacks</a></li>
                <li class="nav-item"><a href="profile.php" class="nav-link"><i class="fas fa-user-circle"></i> Admin Profile</a></li>
            </ul>
        </nav>
    </div>
    <div class="admin-content-wrapper">
        <header class="admin-header">
            <div class="header-left">
                </div>
            <div class="header-right">
                <a href="../logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </header>
        <main class="admin-main-content">