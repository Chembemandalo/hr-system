<?php
session_start();

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
        exit();
    }
}

function checkAdmin() {
    checkLogin();
    if ($_SESSION['role'] !== 'admin') {
        header("Location: ../staff/dashboard.php");
        exit();
    }
}

function checkStaff() {
    checkLogin();
    if ($_SESSION['role'] !== 'staff') {
        header("Location: ../admin/dashboard.php");
        exit();
    }
}
?>
