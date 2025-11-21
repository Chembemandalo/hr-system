<?php
require_once '../includes/auth_session.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
checkStaff();

$user_id = $_SESSION['user_id'];
$staff = $conn->query("SELECT id FROM staff WHERE user_id = $user_id")->fetch_assoc();
$staff_id = $staff['id'];
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = intval($_POST['rating']);
    $message = sanitize($conn, $_POST['message']);

    $stmt = $conn->prepare("INSERT INTO feedback (staff_id, rating, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $staff_id, $rating, $message);
    
    if ($stmt->execute()) {
        $success = "Thank you for your feedback!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Feedback - Rockview</title>
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
                <h2 style="margin-bottom: 1.5rem;">System Feedback</h2>

                <?php if($success): ?>
                    <div class="alert alert-success" style="color: green; margin-bottom: 1rem;"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <p style="margin-bottom: 1.5rem;">We value your feedback. Please rate the Staff Management System and let us know how we can improve.</p>
                        
                        <form method="POST" action="">
                            <div class="form-group">
                                <label class="form-label">Rating (1-5)</label>
                                <select name="rating" class="form-control" required>
                                    <option value="5">5 - Excellent</option>
                                    <option value="4">4 - Very Good</option>
                                    <option value="3">3 - Good</option>
                                    <option value="2">2 - Fair</option>
                                    <option value="1">1 - Poor</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Comments / Suggestions</label>
                                <textarea name="message" class="form-control" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Feedback</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/script.js"></script>
</body>
</html>
