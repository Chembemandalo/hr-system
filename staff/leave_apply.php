<?php
require_once '../includes/auth_session.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
checkStaff();

$user_id = $_SESSION['user_id'];
$staff = $conn->query("SELECT id FROM staff WHERE user_id = $user_id")->fetch_assoc();
$staff_id = $staff['id'];
$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leave_type = sanitize($conn, $_POST['leave_type']);
    $start_date = sanitize($conn, $_POST['start_date']);
    $end_date = sanitize($conn, $_POST['end_date']);
    $reason = sanitize($conn, $_POST['reason']);

    if ($start_date > $end_date) {
        $error = "Start date cannot be after end date.";
    } else {
        $stmt = $conn->prepare("INSERT INTO leaves (staff_id, leave_type, start_date, end_date, reason) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $staff_id, $leave_type, $start_date, $end_date, $reason);
        
        if ($stmt->execute()) {
            $success = "Leave application submitted successfully.";
        } else {
            $error = "Error submitting application.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Leave - Rockview</title>
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
                <h2 style="margin-bottom: 1.5rem;">Apply for Leave</h2>

                <?php if($error): ?>
                    <div class="alert alert-danger" style="color: red; margin-bottom: 1rem;"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if($success): ?>
                    <div class="alert alert-success" style="color: green; margin-bottom: 1rem;"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label class="form-label">Leave Type</label>
                                <select name="leave_type" class="form-control" required>
                                    <option value="Sick Leave">Sick Leave</option>
                                    <option value="Casual Leave">Casual Leave</option>
                                    <option value="Annual Leave">Annual Leave</option>
                                    <option value="Maternity/Paternity">Maternity/Paternity</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Reason</label>
                                <textarea name="reason" class="form-control" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Application</button>
                        </form>
                    </div>
                </div>

                <h3 style="margin-top: 2rem; margin-bottom: 1rem;">My Applications</h3>
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Dates</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $history = $conn->query("SELECT * FROM leaves WHERE staff_id = $staff_id ORDER BY created_at DESC");
                                while ($row = $history->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                                    <td><?php echo formatDate($row['start_date']) . ' to ' . formatDate($row['end_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                    <td><?php echo getStatusBadge($row['status']); ?></td>
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
