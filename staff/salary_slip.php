<?php
require_once '../includes/auth_session.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
checkStaff();

$user_id = $_SESSION['user_id'];
$staff = $conn->query("SELECT id FROM staff WHERE user_id = $user_id")->fetch_assoc();
$staff_id = $staff['id'];

$history = $conn->query("SELECT * FROM salaries WHERE staff_id = $staff_id ORDER BY pay_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Salary - Rockview</title>
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
                <h2 style="margin-bottom: 1.5rem;">My Salary History</h2>

                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Base Salary</th>
                                    <th>Bonus</th>
                                    <th>Deductions</th>
                                    <th>Total Payout</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $history->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo formatDate($row['pay_date']); ?></td>
                                    <td><?php echo number_format($row['base_salary'], 2); ?></td>
                                    <td class="text-success">+<?php echo number_format($row['bonus'], 2); ?></td>
                                    <td class="text-danger">-<?php echo number_format($row['deductions'], 2); ?></td>
                                    <td style="font-weight: 700;"><?php echo number_format($row['total_salary'], 2); ?></td>
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
