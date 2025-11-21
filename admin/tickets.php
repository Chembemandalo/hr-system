<?php
require_once '../includes/auth_session.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
checkAdmin();

$success = '';
$error = '';

// Handle Create Ticket
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_id = intval($_POST['staff_id']);
    $title = sanitize($conn, $_POST['title']);
    $description = sanitize($conn, $_POST['description']);
    $priority = sanitize($conn, $_POST['priority']);
    $created_by = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO tickets (staff_id, title, description, priority, created_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $staff_id, $title, $description, $priority, $created_by);

    if ($stmt->execute()) {
        $success = "Ticket assigned successfully.";
    } else {
        $error = "Error assigning ticket.";
    }
    $stmt->close();
}

$staff_list = $conn->query("SELECT id, name FROM staff ORDER BY name ASC");
$tickets = $conn->query("SELECT t.*, s.name FROM tickets t JOIN staff s ON t.staff_id = s.id ORDER BY t.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets & Tasks - Rockview</title>
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
                <h2 style="margin-bottom: 1.5rem;">Tickets & Tasks</h2>

                <?php if($success): ?>
                    <div class="alert alert-success" style="color: green; margin-bottom: 1rem;"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">Assign New Task</div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label class="form-label">Assign To</label>
                                <select name="staff_id" class="form-control" required>
                                    <option value="">Select Staff</option>
                                    <?php while ($row = $staff_list->fetch_assoc()): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Task Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Priority</label>
                                <select name="priority" class="form-control">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Assign Task</button>
                        </form>
                    </div>
                </div>

                <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Task List</h3>
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Assigned To</th>
                                    <th>Title</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Date Assigned</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $tickets->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $row['priority'] == 'high' ? 'danger' : ($row['priority'] == 'medium' ? 'warning' : 'success'); ?>">
                                            <?php echo ucfirst($row['priority']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo getStatusBadge($row['status']); ?></td>
                                    <td><?php echo formatDate($row['created_at']); ?></td>
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
