<?php
require_once 'includes/auth_session.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - TeamTrack360</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .splash-hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, #004d4d 100%);
            color: white;
            padding: 5rem 2rem;
            text-align: center;
            border-radius: 1rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        .splash-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('assets/img/pattern.png'); /* Optional pattern */
            opacity: 0.1;
        }
        .splash-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }
        .splash-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto 2rem;
            position: relative;
            z-index: 1;
        }
        .action-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        .action-card {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            text-decoration: none;
            color: var(--dark-color);
            display: block;
        }
        .action-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .card-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .card-desc {
            color: #666;
            font-size: 0.9rem;
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
            }
            ?>
        </nav>

        <div class="main-content">
            <?php include 'includes/header.php'; ?>

<?php
            // Quotes Array
            $quotes = [
                ["text" => "Be the change that you wish to see in the world.", "author" => "Mahatma Gandhi"],
                ["text" => "The only true wisdom is in knowing you know nothing.", "author" => "Socrates"],
                ["text" => "Waste no more time arguing about what a good man should be. Be one.", "author" => "Marcus Aurelius"],
                ["text" => "He who fears death will never do anything worth of a man who is alive.", "author" => "Seneca"],
                ["text" => "Happiness and freedom begin with a clear understanding of one principle: Some things are within our control, and some things are not.", "author" => "Epictetus"],
                ["text" => "Live as if you were to die tomorrow. Learn as if you were to live forever.", "author" => "Mahatma Gandhi"],
                ["text" => "An unexamined life is not worth living.", "author" => "Socrates"],
                ["text" => "The best revenge is to be unlike him who performed the injury.", "author" => "Marcus Aurelius"]
            ];
            $daily_quote = $quotes[array_rand($quotes)];
            ?>

            <div class="content">
                <div class="splash-container">
                    <div class="splash-hero">
                        <h1 class="splash-title">Welcome to TeamTrack360</h1>
                        <p class="splash-subtitle">Your central hub for efficient management and collaboration. Explore the tools available to you below.</p>
                        <a href="<?php echo $role == 'admin' ? 'admin/dashboard.php' : 'staff/dashboard.php'; ?>" class="btn btn-light" style="color: var(--primary-color); font-weight: 700; padding: 0.75rem 2rem; border-radius: 50px; position: relative; z-index: 1;">Go to Dashboard</a>
                    </div>

                    <div class="quote-card-container">
                        <div class="quote-card">
                            <i class="fas fa-quote-left quote-icon"></i>
                            <p class="quote-text">"<?php echo $daily_quote['text']; ?>"</p>
                            <p class="quote-author">- <?php echo $daily_quote['author']; ?></p>
                        </div>
                    </div>
                </div>

                <div class="action-cards">
                    <?php if ($role == 'admin'): ?>
                        <a href="admin/staff_list.php" class="action-card">
                            <i class="fas fa-users card-icon"></i>
                            <h3 class="card-title">Manage Staff</h3>
                            <p class="card-desc">View, add, and update staff records.</p>
                        </a>
                        <a href="admin/attendance_report.php" class="action-card">
                            <i class="fas fa-clock card-icon"></i>
                            <h3 class="card-title">Attendance</h3>
                            <p class="card-desc">Monitor daily attendance and reports.</p>
                        </a>
                        <a href="admin/manage_leave.php" class="action-card">
                            <i class="fas fa-calendar-check card-icon"></i>
                            <h3 class="card-title">Leave Requests</h3>
                            <p class="card-desc">Approve or reject leave applications.</p>
                        </a>
                    <?php else: ?>
                        <a href="staff/attendance.php" class="action-card">
                            <i class="fas fa-user-clock card-icon"></i>
                            <h3 class="card-title">My Attendance</h3>
                            <p class="card-desc">Check in/out and view your history.</p>
                        </a>
                        <a href="staff/leave_apply.php" class="action-card">
                            <i class="fas fa-umbrella-beach card-icon"></i>
                            <h3 class="card-title">Apply for Leave</h3>
                            <p class="card-desc">Submit a new leave request.</p>
                        </a>
                        <a href="staff/my_tickets.php" class="action-card">
                            <i class="fas fa-tasks card-icon"></i>
                            <h3 class="card-title">My Tasks</h3>
                            <p class="card-desc">View and update your assigned tasks.</p>
                        </a>
                    <?php endif; ?>
                    
                    <a href="about.php" class="action-card">
                        <i class="fas fa-info-circle card-icon"></i>
                        <h3 class="card-title">About Us</h3>
                        <p class="card-desc">Learn more about the system.</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>
