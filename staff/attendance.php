<?php
require_once '../includes/auth_session.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
checkStaff();

$user_id = $_SESSION['user_id'];
$staff = $conn->query("SELECT id FROM staff WHERE user_id = $user_id")->fetch_assoc();
$staff_id = $staff['id'];
$today = date('Y-m-d');
$message = '';

// Handle Attendance
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $time = date('H:i:s');

    // Check if record exists for today
    $check = $conn->query("SELECT * FROM attendance WHERE staff_id = $staff_id AND date = '$today'");
    
    if ($check->num_rows == 0) {
        if ($action == 'clock_in') {
            $stmt = $conn->prepare("INSERT INTO attendance (staff_id, date, status, clock_in) VALUES (?, ?, 'present', ?)");
            $stmt->bind_param("iss", $staff_id, $today, $time);
            $stmt->execute();
            $message = "Clocked in successfully at $time.";
        }
    } else {
        $row = $check->fetch_assoc();
        if ($action == 'clock_out' && $row['clock_out'] == NULL) {
            $stmt = $conn->prepare("UPDATE attendance SET clock_out = ? WHERE id = ?");
            $stmt->bind_param("si", $time, $row['id']);
            $stmt->execute();
            $message = "Clocked out successfully at $time.";
        } else {
            $message = "Action already performed or invalid.";
        }
    }
}

// Fetch today's status
$today_record = $conn->query("SELECT * FROM attendance WHERE staff_id = $staff_id AND date = '$today'")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Attendance - Rockview</title>
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
                <h2 style="margin-bottom: 1.5rem;">My Attendance</h2>

                <?php if($message): ?>
                    <div class="alert alert-success" style="color: green; margin-bottom: 1rem;"><?php echo $message; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body" style="text-align: center; padding: 3rem;">
                        <h3 style="margin-bottom: 1rem;"><?php echo date('l, F j, Y'); ?></h3>
                        <div style="font-size: 3rem; font-weight: 700; color: var(--primary-color); margin-bottom: 2rem;">
                            <?php echo date('H:i'); ?>
                        </div>

                        <form method="POST" action="">
                            <?php if (!$today_record): ?>
                                <button type="submit" name="action" value="clock_in" class="btn btn-success" style="font-size: 1.2rem; padding: 1rem 2rem;">Clock In</button>
                            <?php elseif ($today_record['clock_in'] && !$today_record['clock_out']): ?>
                                <p style="margin-bottom: 1rem;">Clocked In at: <strong><?php echo $today_record['clock_in']; ?></strong></p>
                                <button type="submit" name="action" value="clock_out" class="btn btn-danger" style="font-size: 1.2rem; padding: 1rem 2rem;">Clock Out</button>
                            <?php else: ?>
                                <div class="alert alert-info" style="color: var(--info-color);">
                                    Attendance completed for today.<br>
                                    In: <?php echo $today_record['clock_in']; ?> | Out: <?php echo $today_record['clock_out']; ?>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Recent History</h3>
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $history = $conn->query("SELECT * FROM attendance WHERE staff_id = $staff_id ORDER BY date DESC LIMIT 5");
                                while ($row = $history->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?php echo formatDate($row['date']); ?></td>
                                    <td><?php echo getStatusBadge($row['status']); ?></td>
                                    <td><?php echo $row['clock_in'] ? $row['clock_in'] : '-'; ?></td>
                                    <td><?php echo $row['clock_out'] ? $row['clock_out'] : '-'; ?></td>
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
