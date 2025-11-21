<?php
require_once '../includes/auth_session.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
checkAdmin();

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize($conn, $_POST['name']);
    $email = sanitize($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $department = sanitize($conn, $_POST['department']);
    $position = sanitize($conn, $_POST['position']);
    $phone = sanitize($conn, $_POST['phone']);
    $base_salary = floatval($_POST['base_salary']);

    // Start Transaction
    $conn->begin_transaction();

    try {
        // Insert into Users
        $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, 'staff')");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $user_id = $conn->insert_id;
        $stmt->close();

        // Insert into Staff
        $stmt = $conn->prepare("INSERT INTO staff (user_id, name, department, position, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $name, $department, $position, $phone);
        $stmt->execute();
        $staff_id = $conn->insert_id;
        $stmt->close();

        // Insert into Salaries (Initial record)
        $stmt = $conn->prepare("INSERT INTO salaries (staff_id, base_salary, total_salary, pay_date) VALUES (?, ?, ?, CURDATE())");
        $stmt->bind_param("idd", $staff_id, $base_salary, $base_salary);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        $success = "Staff member added successfully.";
    } catch (Exception $e) {
        $conn->rollback();
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff - Rockview</title>
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
                <div style="max-width: 600px; margin: 0 auto;">
                    <h2 style="margin-bottom: 1.5rem;">Add New Staff</h2>
                    
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
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Department</label>
                                    <select name="department" class="form-control" required>
                                        <option value="">Select Department</option>
                                        <option value="Computer Science">Computer Science</option>
                                        <option value="Engineering">Engineering</option>
                                        <option value="Business">Business</option>
                                        <option value="Administration">Administration</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Position</label>
                                    <input type="text" name="position" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Base Salary</label>
                                    <input type="number" step="0.01" name="base_salary" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Staff</button>
                                <a href="staff_list.php" class="btn" style="margin-left: 10px; color: #858796;">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/script.js"></script>
</body>
</html>
