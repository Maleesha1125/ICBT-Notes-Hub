<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../includes/admin-header.php';
$auth = new Auth($db);
if (!$auth->hasRole('admin')) {
    header('Location: ../login.php');
    exit;
}
?>

<div class="dashboard-content">
    <div class="welcome-section">
        <h2>Welcome, Admin!</h2>
        <p>This is your central hub to manage all aspects of the application.</p>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-4">
        <div class="col">
            <a href="manage-users.php" class="admin-button">
                <i class="fas fa-users"></i>
                <span>Manage Users</span>
            </a>
        </div>
        <div class="col">
            <a href="manage-departments.php" class="admin-button">
                <i class="fas fa-building"></i>
                <span>Manage Departments</span>
            </a>
        </div>
        <div class="col">
            <a href="manage-programs.php" class="admin-button">
                <i class="fas fa-graduation-cap"></i>
                <span>Manage Programs</span>
            </a>
        </div>
        <div class="col">
            <a href="manage-modules.php" class="admin-button">
                <i class="fas fa-book"></i>
                <span>Manage Modules</span>
            </a>
        </div>
        <div class="col">
            <a href="manage-feedbacks.php" class="admin-button">
                <i class="fas fa-comments"></i>
                <span>Manage Feedbacks</span>
            </a>
        </div>
        <div class="col">
            <a href="profile.php" class="admin-button">
                <i class="fas fa-user-cog"></i>
                <span>Admin Profile</span>
            </a>
        </div>
        <div class="col">
            <a href="logout.php" class="admin-button logout-button">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>