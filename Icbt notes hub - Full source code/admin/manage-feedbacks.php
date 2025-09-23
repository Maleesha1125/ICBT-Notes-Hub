<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Feedback.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Department.php';
require_once __DIR__ . '/../classes/Program.php';
require_once __DIR__ . '/../includes/admin-header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$feedback_class = new Feedback($db);
$user_class = new User($db);
$department_class = new Department($db);
$program_class = new Program($db);

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $feedback_id = intval($_GET['id']);
    $feedback_class->deleteFeedback($feedback_id);
    header('Location: manage-feedbacks.php');
    exit;
}

$feedbacks = $feedback_class->getAllFeedbacks();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedbacks</title>
    <link rel="stylesheet" href="../assets/css/manage-feedbacks-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container manage-feedbacks-container">
    <h2><i class="fas fa-comments"></i> Manage Feedbacks</h2>
    <?php if (empty($feedbacks)): ?>
        <p class="no-feedback-message"><i class="fas fa-info-circle"></i> No feedbacks have been submitted yet.</p>
    <?php else: ?>
        <table class="content-table">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> ID</th>
                    <th><i class="fas fa-user"></i> Name</th>
                    <th><i class="fas fa-building"></i> Department</th>
                    <th><i class="fas fa-graduation-cap"></i> Program</th>
                    <th><i class="fas fa-comment"></i> Feedback</th>
                    <th><i class="fas fa-star"></i> Rating</th>
                    <th><i class="fas fa-calendar-alt"></i> Date</th>
                    <th><i class="fas fa-check-circle"></i> Status</th>
                    <th><i class="fas fa-cogs"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedbacks as $feedback): 
                    $user_details = $user_class->getUserById($feedback['user_id']);
                    $department_name = 'N/A';
                    $program_name = 'N/A';
                    if ($user_details && $user_details['department_id']) {
                        $department = $department_class->getDepartmentById($user_details['department_id']);
                        $department_name = $department ? $department['name'] : 'N/A';
                    }
                    if ($user_details && $user_details['program_id']) {
                        $program = $program_class->getProgramById($user_details['program_id']);
                        $program_name = $program ? $program['name'] : 'N/A';
                    }
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($feedback['id']); ?></td>
                        <td><?php echo htmlspecialchars($user_details['firstname'] . ' ' . $user_details['lastname'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($department_name); ?></td>
                        <td><?php echo htmlspecialchars($program_name); ?></td>
                        <td><?php echo htmlspecialchars($feedback['feedback']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['rating']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['date']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['status']); ?></td>
                        <td>
                            <a href="manage-feedbacks.php?action=delete&id=<?php echo htmlspecialchars($feedback['id']); ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this feedback?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>