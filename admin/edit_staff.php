<?php
require_once '../includes/auth_session.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
checkAdmin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    header("Location: staff_list.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize($conn, $_POST['name']);
    $department = sanitize($conn, $_POST['department']);
    $position = sanitize($conn, $_POST['position']);
    $phone = sanitize($conn, $_POST['phone']);
    $status = sanitize($conn, $_POST['status']);

    $stmt = $conn->prepare("UPDATE staff SET name=?, department=?, position=?, phone=?, status=? WHERE id=?");
    $stmt->bind_param("sssssi", $name, $department, $position, $phone, $status, $id);

    if ($stmt->execute()) {
        $success = "Staff updated successfully.";
    } else {
        $error = "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

$stmt = $conn->prepare("SELECT * FROM staff WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();
$stmt->close();

if (!$staff) {
    header("Location: staff_list.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff - Rockview</title>
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
                    <h2 style="margin-bottom: 1.5rem;">Edit Staff</h2>
                    
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
                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($staff['name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Department</label>
                                    <select name="department" class="form-control" required>
                                        <option value="Computer Science" <?php if($staff['department'] == 'Computer Science') echo 'selected'; ?>>Computer Science</option>
                                        <option value="Engineering" <?php if($staff['department'] == 'Engineering') echo 'selected'; ?>>Engineering</option>
                                        <option value="Business" <?php if($staff['department'] == 'Business') echo 'selected'; ?>>Business</option>
                                        <option value="Administration" <?php if($staff['department'] == 'Administration') echo 'selected'; ?>>Administration</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Position</label>
                                    <input type="text" name="position" class="form-control" value="<?php echo htmlspecialchars($staff['position']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($staff['phone']); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" <?php if($staff['status'] == 'active') echo 'selected'; ?>>Active</option>
                                        <option value="inactive" <?php if($staff['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Staff</button>
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
