<?php
require_once '../includes/auth_session.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
checkStaff();

$user_id = $_SESSION['user_id'];
$staff = $conn->query("SELECT id FROM staff WHERE user_id = $user_id")->fetch_assoc();
$staff_id = $staff['id'];

// Handle Status Update
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $status = $_GET['action']; // in_progress or done
    
    if (in_array($status, ['in_progress', 'done'])) {
        $stmt = $conn->prepare("UPDATE tickets SET status = ? WHERE id = ? AND staff_id = ?");
        $stmt->bind_param("sii", $status, $id, $staff_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: my_tickets.php");
    exit();
}

$tickets = $conn->query("SELECT * FROM tickets WHERE staff_id = $staff_id ORDER BY FIELD(status, 'open', 'in_progress', 'done'), created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets - Rockview</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="wrapper">
        <nav class="sidebar">
            <?php include '../includes/staff_sidebar.php'; ?>
        </nav>

        <div class="main-content">
            <?php include '../includes/header.php'; ?>

            <div class="content">
                <h2 style="margin-bottom: 1.5rem;">My Assigned Tasks</h2>

                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Description</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $tickets->fetch_assoc()): ?>
                                <tr>
                                    <td style="font-weight: 600;"><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $row['priority'] == 'high' ? 'danger' : ($row['priority'] == 'medium' ? 'warning' : 'success'); ?>">
                                            <?php echo ucfirst($row['priority']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo getStatusBadge($row['status']); ?></td>
                                    <td>
                                        <?php if($row['status'] == 'open'): ?>
                                            <a href="my_tickets.php?action=in_progress&id=<?php echo $row['id']; ?>" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Start</a>
                                        <?php elseif($row['status'] == 'in_progress'): ?>
                                            <a href="my_tickets.php?action=done&id=<?php echo $row['id']; ?>" class="btn btn-success" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Mark Done</a>
                                        <?php else: ?>
                                            <span class="text-success">Completed</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/script.js"></script>
</body>
</html>
