<?php
require_once '../includes/auth_session.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
checkAdmin();

$success = '';
$error = '';

// Update Salary Details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_salary'])) {
    $staff_id = intval($_POST['staff_id']);
    $base_salary = floatval($_POST['base_salary']);
    $bonus = floatval($_POST['bonus']);
    $deductions = floatval($_POST['deductions']);
    
    $total_salary = $base_salary + $bonus - $deductions;
    $pay_date = date('Y-m-d');

    // Check if record exists for this month
    $current_month = date('Y-m');
    $check = $conn->query("SELECT id FROM salaries WHERE staff_id = $staff_id AND DATE_FORMAT(pay_date, '%Y-%m') = '$current_month'");

    if ($check->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE salaries SET base_salary=?, bonus=?, deductions=?, total_salary=?, pay_date=? WHERE staff_id=? AND DATE_FORMAT(pay_date, '%Y-%m') = '$current_month'");
        $stmt->bind_param("ddddsi", $base_salary, $bonus, $deductions, $total_salary, $pay_date, $staff_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO salaries (staff_id, base_salary, bonus, deductions, total_salary, pay_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idddds", $staff_id, $base_salary, $bonus, $deductions, $total_salary, $pay_date);
    }

    if ($stmt->execute()) {
        $success = "Salary updated successfully.";
    } else {
        $error = "Error updating salary.";
    }
    $stmt->close();
}

$staff_list = $conn->query("SELECT s.id, s.name, s.department, sal.base_salary, sal.bonus, sal.deductions, sal.total_salary FROM staff s LEFT JOIN salaries sal ON s.id = sal.staff_id AND sal.id = (SELECT MAX(id) FROM salaries WHERE staff_id = s.id) ORDER BY s.name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Management - Rockview</title>
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
                <h2 style="margin-bottom: 1.5rem;">Payroll Management</h2>

                <?php if($success): ?>
                    <div class="alert alert-success" style="color: green; margin-bottom: 1rem;"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Staff Name</th>
                                    <th>Department</th>
                                    <th>Days Present (This Month)</th>
                                    <th>Base Salary</th>
                                    <th>Bonus</th>
                                    <th>Deductions</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $staff_list->fetch_assoc()): 
                                    // Fetch attendance for this month
                                    $sid = $row['id'];
                                    $current_month_start = date('Y-m-01');
                                    $current_month_end = date('Y-m-t');
                                    $attendance_count = $conn->query("SELECT COUNT(*) FROM attendance WHERE staff_id = $sid AND status = 'present' AND date BETWEEN '$current_month_start' AND '$current_month_end'")->fetch_row()[0];
                                ?>
                                <tr>
                                    <form method="POST" action="">
                                        <input type="hidden" name="staff_id" value="<?php echo $row['id']; ?>">
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['department']); ?></td>
                                        <td style="text-align: center;">
                                            <span class="badge bg-info"><?php echo $attendance_count; ?> days</span>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="base_salary" class="form-control" value="<?php echo $row['base_salary'] ? $row['base_salary'] : '0.00'; ?>" style="width: 100px;">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="bonus" class="form-control" value="<?php echo $row['bonus'] ? $row['bonus'] : '0.00'; ?>" style="width: 80px;">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="deductions" class="form-control" value="<?php echo $row['deductions'] ? $row['deductions'] : '0.00'; ?>" style="width: 80px;">
                                        </td>
                                        <td>
                                            <strong><?php echo number_format($row['total_salary'], 2); ?></strong>
                                        </td>
                                        <td>
                                            <button type="submit" name="update_salary" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Update</button>
                                        </td>
                                    </form>
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
