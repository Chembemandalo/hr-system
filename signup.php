<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize($conn, $_POST['name']);
    $email = sanitize($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $department = sanitize($conn, $_POST['department']);
    $position = sanitize($conn, $_POST['position']);
    $phone = sanitize($conn, $_POST['phone']);

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email exists
        $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
        if ($check->num_rows > 0) {
            $error = "Email already exists.";
        } else {
            // Create User
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'staff';
            
            $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $hashed_password, $role);
            
            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;
                $stmt->close();

                // Create Staff Profile
                $stmt = $conn->prepare("INSERT INTO staff (user_id, name, department, position, phone) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $user_id, $name, $department, $position, $phone);
                
                if ($stmt->execute()) {
                    $success = "Account created successfully! You can now login.";
                } else {
                    $error = "Error creating profile: " . $conn->error;
                }
                $stmt->close();
            } else {
                $error = "Error creating account: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Rockview Staff Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-card" style="max-width: 500px;">
        <h2 class="login-title">Create Account</h2>
        <h4 style="text-align: center; margin-bottom: 1.5rem; color: var(--secondary-color);">Join Rockview Staff</h4>
        
        <?php if($error): ?>
            <div class="alert alert-danger" style="color: var(--danger-color); text-align: center; margin-bottom: 1rem;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success" style="color: var(--success-color); text-align: center; margin-bottom: 1rem;">
                <?php echo $success; ?> <a href="index.php">Login here</a>
            </div>
        <?php endif; ?>

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
                <label class="form-label">Department</label>
                <select name="department" class="form-control" required>
                    <option value="">Select Department</option>
                    <option value="IT">IT</option>
                    <option value="HR">HR</option>
                    <option value="Finance">Finance</option>
                    <option value="Academic">Academic</option>
                    <option value="Administration">Administration</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Position</label>
                <input type="text" name="position" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Sign Up</button>
        </form>
        <div style="text-align: center; margin-top: 1rem; font-size: 0.9rem;">
            <p>Already have an account? <a href="index.php">Login</a></p>
        </div>
    </div>
</body>
</html>
