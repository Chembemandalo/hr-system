<?php
require_once 'includes/auth_session.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Determine which sidebar to show based on role
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - TeamTrack360</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .about-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #006666 100%);
            color: white;
            padding: 3rem 2rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        .about-content {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        .team-member {
            text-align: center;
        }
        .team-member img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
            border: 4px solid #f8f9fc;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <nav class="sidebar">
            <?php 
            if ($role == 'admin') {
                include 'includes/admin_sidebar.php';
            } elseif ($role == 'staff') {
                include 'includes/staff_sidebar.php';
            } else {
                // Fallback or redirect if not logged in, though auth_session should handle it
                echo '<div style="padding: 1rem;">Please login to view sidebar.</div>';
            }
            ?>
        </nav>

        <div class="main-content">
            <?php include 'includes/header.php'; ?>

            <div class="content">
                <div class="about-header">
                    <h1>About TeamTrack360</h1>
                    <p>Empowering organizations with seamless staff management solutions.</p>
                </div>

                <div class="about-content">
                    <h2 style="color: var(--primary-color); margin-bottom: 1rem;">Our Mission</h2>
                    <p style="margin-bottom: 2rem;">
                        At TeamTrack360, our mission is to simplify human resource management for educational institutions and businesses. 
                        We believe that efficient staff management is the backbone of any successful organization. 
                        Our platform is designed to streamline attendance, leave management, payroll, and performance reviews, 
                        allowing you to focus on what matters most - your people.
                    </p>

                    <h2 style="color: var(--primary-color); margin-bottom: 1rem;">Key Features</h2>
                    <ul style="list-style-type: none; padding: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                        <li><i class="fas fa-check-circle text-success" style="margin-right: 10px;"></i> Comprehensive Staff Database</li>
                        <li><i class="fas fa-check-circle text-success" style="margin-right: 10px;"></i> Real-time Attendance Tracking</li>
                        <li><i class="fas fa-check-circle text-success" style="margin-right: 10px;"></i> Automated Payroll Processing</li>
                        <li><i class="fas fa-check-circle text-success" style="margin-right: 10px;"></i> Performance Evaluation Tools</li>
                        <li><i class="fas fa-check-circle text-success" style="margin-right: 10px;"></i> Secure Document Management</li>
                        <li><i class="fas fa-check-circle text-success" style="margin-right: 10px;"></i> Internal Ticketing System</li>
                    </ul>

                    <div style="margin-top: 3rem; text-align: center;">
                        <h2 style="color: var(--dark-color); margin-bottom: 2rem;">Contact Us</h2>
                        <p>Have questions or need support? Reach out to our team.</p>
                        <a href="mailto:support@teamtrack360.com" class="btn btn-primary" style="margin-top: 1rem;">
                            <i class="fas fa-envelope" style="margin-right: 8px;"></i> Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>
