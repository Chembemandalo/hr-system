<?php
require_once '../includes/auth_session.php';
require_once '../includes/db_connect.php';
checkAdmin();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Get user_id first to delete from users table (Cascade will handle the rest if set up, but let's be safe)
    $stmt = $conn->prepare("SELECT user_id FROM staff WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if ($user_id) {
        $conn->query("DELETE FROM users WHERE id = $user_id");
    }
}

header("Location: staff_list.php");
exit();
?>
