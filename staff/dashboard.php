<?php
require_once '../includes/auth_session.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
checkStaff();

$user_id = $_SESSION['user_id'];
$staff = $conn->query("SELECT * FROM staff WHERE user_id = $user_id")->fetch_assoc();
$staff_id = $staff['id'];

// Fetch Stats
$attendance_days = $conn->query("SELECT COUNT(*) FROM attendance WHERE staff_id = $staff_id AND status = 'present'")->fetch_row()[0];
$pending_leaves = $conn->query("SELECT COUNT(*) FROM leaves WHERE staff_id = $staff_id AND status = 'pending'")->fetch_row()[0];
$open_tickets = $conn->query("SELECT COUNT(*) FROM tickets WHERE staff_id = $staff_id AND status != 'done'")->fetch_row()[0];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Rockview</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-left: 4px solid var(--primary-color);
        }
        .stat-card.success { border-left-color: var(--success-color); }
        .stat-card.warning { border-left-color: var(--warning-color); }
        .stat-card.danger { border-left-color: var(--danger-color); }
        
        .stat-title {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }
        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <nav class="sidebar">
            <?php include '../includes/staff_sidebar.php'; ?>
        </nav>

        <div class="main-content">
            <?php include '../includes/header.php'; ?>

            <div class="content">
                <h2 style="margin-bottom: 0.5rem; color: var(--dark-color);">Welcome, <?php echo htmlspecialchars($staff['name']); ?></h2>
                <p style="margin-bottom: 1.5rem; color: var(--secondary-color);"><?php echo htmlspecialchars($staff['position']); ?> - <?php echo htmlspecialchars($staff['department']); ?></p>

                <div class="stats-grid">
                    <div class="stat-card success">
                        <div class="stat-title text-success">Days Present</div>
                        <div class="stat-value"><?php echo $attendance_days; ?></div>
                    </div>
                    <div class="stat-card warning">
                        <div class="stat-title text-warning">Pending Leaves</div>
                        <div class="stat-value"><?php echo $pending_leaves; ?></div>
                    </div>
                    <div class="stat-card danger">
                        <div class="stat-title text-danger">My Tasks</div>
                        <div class="stat-value"><?php echo $open_tickets; ?></div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        Quick Actions
                    </div>
                    <div class="card-body">
                        <a href="attendance.php" class="btn btn-primary" style="margin-right: 10px;">Mark Attendance</a>
                        <a href="leave_apply.php" class="btn btn-warning">Apply for Leave</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/script.js"></script>
</body>
</html>
