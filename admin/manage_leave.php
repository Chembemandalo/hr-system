<?php
require_once '../includes/auth_session.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
checkAdmin();

// Handle Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $status = $_GET['action'] == 'approve' ? 'approved' : 'rejected';
    
    $stmt = $conn->prepare("UPDATE leaves SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: manage_leave.php");
    exit();
}

$result = $conn->query("SELECT l.*, s.name, s.department FROM leaves l JOIN staff s ON l.staff_id = s.id ORDER BY l.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Leaves - Rockview</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="wrapper">
        <nav class="sidebar">
            <?php include '../includes/admin_sidebar.php'; ?>
        </nav>

        <div class="main-content">
            <?php include '../includes/header.php'; ?>

            <div class="content">
                <h2 style="margin-bottom: 1.5rem;">Leave Management</h2>

                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Staff Name</th>
                                    <th>Department</th>
                                    <th>Type</th>
                                    <th>Dates</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['department']); ?></td>
                                    <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                                    <td>
                                        <small><?php echo formatDate($row['start_date']); ?> to<br><?php echo formatDate($row['end_date']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                    <td><?php echo getStatusBadge($row['status']); ?></td>
                                    <td>
                                        <?php if($row['status'] == 'pending'): ?>
                                            <a href="manage_leave.php?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-success" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Approve</a>
                                            <a href="manage_leave.php?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Reject</a>
                                        <?php else: ?>
                                            <span style="color: #858796;">-</span>
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
