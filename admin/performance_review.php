<?php
require_once '../includes/auth_session.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
checkAdmin();

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_id = intval($_POST['staff_id']);
    $rating = intval($_POST['rating']);
    $comments = sanitize($conn, $_POST['comments']);
    $review_date = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO performance_reviews (staff_id, rating, comments, review_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $staff_id, $rating, $comments, $review_date);

    if ($stmt->execute()) {
        $success = "Performance review added successfully.";
    } else {
        $error = "Error adding review.";
    }
    $stmt->close();
}

$staff_list = $conn->query("SELECT id, name FROM staff ORDER BY name ASC");
$reviews = $conn->query("SELECT p.*, s.name, s.department FROM performance_reviews p JOIN staff s ON p.staff_id = s.id ORDER BY p.review_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Reviews - Rockview</title>
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
                <h2 style="margin-bottom: 1.5rem;">Performance Reviews</h2>

                <?php if($success): ?>
                    <div class="alert alert-success" style="color: green; margin-bottom: 1rem;"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">Add New Review</div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label class="form-label">Staff Member</label>
                                <select name="staff_id" class="form-control" required>
                                    <option value="">Select Staff</option>
                                    <?php while ($row = $staff_list->fetch_assoc()): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
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
                                <label class="form-label">Comments</label>
                                <textarea name="comments" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </form>
                    </div>
                </div>

                <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Review History</h3>
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Staff Name</th>
                                    <th>Department</th>
                                    <th>Rating</th>
                                    <th>Comments</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $reviews->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['department']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $row['rating'] >= 4 ? 'success' : ($row['rating'] >= 3 ? 'warning' : 'danger'); ?>">
                                            <?php echo $row['rating']; ?> / 5
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['comments']); ?></td>
                                    <td><?php echo formatDate($row['review_date']); ?></td>
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
