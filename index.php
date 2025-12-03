<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT id, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $role;
            $_SESSION['email'] = $email;

            if ($role == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: staff/dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found with that email.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamTrack360 - Staff Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fc;
            overflow-x: hidden;
        }
        
        /* Hero Section */
        .hero {
            position: relative;
            background: linear-gradient(135deg, var(--primary-color) 0%, #006666 100%);
            color: white;
            padding: 4rem 2rem;
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: space-between;
            overflow: hidden;
        }
        
        .hero-content {
            max-width: 600px;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        /* Login Form - Top Right */
        .login-container {
            background: white;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            color: #333;
            z-index: 10;
            position: absolute;
            top: 4rem;
            right: 4rem;
        }

        @media (max-width: 992px) {
            .hero {
                flex-direction: column;
                text-align: center;
                padding-top: 6rem;
            }
            .login-container {
                position: relative;
                top: 0;
                right: 0;
                margin-top: 2rem;
            }
            .hero-content {
                margin-bottom: 2rem;
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h3 {
            margin: 0;
            color: var(--primary-color);
            font-weight: 700;
        }
        
        /* Features Section */
        .features-section {
            padding: 5rem 2rem;
            background: white;
            text-align: center;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 3rem;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .feature-card {
            padding: 2rem;
            border-radius: 1rem;
            background: #f8f9fc;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
        
        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }
        
        .feature-desc {
            color: #666;
            line-height: 1.6;
        }

        /* Demo Video Section */
        .video-section {
            padding: 5rem 2rem;
            background: #f1f3f9;
            text-align: center;
        }
        
        .video-container {
            max-width: 1000px;
            margin: 0 auto;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
            background: #000;
            aspect-ratio: 16/9;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-placeholder {
            color: white;
            font-size: 1.2rem;
        }
        
        .video-container img, .video-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Footer */
        footer {
            background: #333;
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        /* Form Styles Override */
        .form-control {
            background-color: #f8f9fc;
            border: 1px solid #e3e6f0;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(0, 128, 128, 0.25);
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">TeamTrack360</h1>
            <p class="hero-subtitle">The ultimate solution for modern staff management. Streamline operations, boost productivity, and empower your team.</p>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <button onclick="toggleLogin()" class="btn btn-light btn-lg" style="color: var(--primary-color); font-weight: 600; border-radius: 50px; padding: 0.75rem 2rem; border: none;">Login</button>
                <a href="signup.php" class="btn btn-lg" style="background-color: rgba(255,255,255,0.2); color: white; font-weight: 600; border-radius: 50px; padding: 0.75rem 2rem; border: 1px solid rgba(255,255,255,0.5);">Sign Up</a>
                <a href="about.php" class="btn btn-lg" style="background-color: rgba(255,255,255,0.15); color: white; font-weight: 600; border-radius: 50px; padding: 0.75rem 2rem; border: 1px solid rgba(255,255,255,0.5);">
                    <i class="fas fa-info-circle"></i> About Us
                </a>
            </div>
        </div>

        <!-- Login Form -->
        <div class="login-container" id="loginForm" style="display: none;">
            <div class="login-header">
                <h3>Login</h3>
                <p style="color: #858796; font-size: 0.9rem; margin-top: 0.5rem;">Access your dashboard</p>
            </div>
            
            <?php if($error): ?>
                <div class="alert alert-danger" style="color: #e74a3b; background-color: #fdf2f2; border: 1px solid #e74a3b; padding: 0.75rem; border-radius: 0.35rem; margin-bottom: 1rem; text-align: center; font-size: 0.9rem;">
                    <?php echo $error; ?>
                </div>
                <script>
                    // Keep form open if there's an error
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('loginForm').style.display = 'block';
                    });
                </script>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label" style="font-size: 0.9rem; font-weight: 600;">Email Address</label>
                    <input type="email" name="email" class="form-control" required placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size: 0.9rem; font-weight: 600;">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Enter your password">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem; font-weight: 600; border-radius: 0.35rem; margin-top: 1rem;">Sign In</button>
            </form>
            
            <div style="text-align: center; margin-top: 1.5rem; font-size: 0.85rem; color: #858796;">
                <p style="margin-bottom: 0.5rem;">Default Admin: admin@gmail.com / admin123</p>
                <p>Don't have an account? <a href="signup.php" style="color: var(--primary-color); font-weight: 600;">Sign Up</a></p>
            </div>
        </div>
    </section>

    <script>
        function toggleLogin() {
            const form = document.getElementById('loginForm');
            if (form.style.display === 'none') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <h2 class="section-title">Why Choose TeamTrack360?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-users-cog feature-icon"></i>
                <h3 class="feature-title">Staff Management</h3>
                <p class="feature-desc">Effortlessly manage employee profiles, roles, and departments in one centralized database.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-clock feature-icon"></i>
                <h3 class="feature-title">Attendance Tracking</h3>
                <p class="feature-desc">Real-time clock-in/out system with detailed reports and geolocation support.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-calendar-check feature-icon"></i>
                <h3 class="feature-title">Leave Management</h3>
                <p class="feature-desc">Streamlined leave application and approval workflow with balance tracking.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-money-check-alt feature-icon"></i>
                <h3 class="feature-title">Payroll & Salary</h3>
                <p class="feature-desc">Automated salary calculations, payslip generation, and compensation management.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-tasks feature-icon"></i>
                <h3 class="feature-title">Task & Ticketing</h3>
                <p class="feature-desc">Assign tasks, track progress, and manage internal support tickets efficiently.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-chart-line feature-icon"></i>
                <h3 class="feature-title">Performance Reviews</h3>
                <p class="feature-desc">Conduct self-evaluations and manager reviews to foster professional growth.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-bullhorn feature-icon"></i>
                <h3 class="feature-title">Announcements</h3>
                <p class="feature-desc">Keep your team informed with company-wide news and updates.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-file-contract feature-icon"></i>
                <h3 class="feature-title">Document Management</h3>
                <p class="feature-desc">Securely store and share important files, HR letters, and policies.</p>
            </div>
        </div>
    </section>

    <!-- Demo Video Section -->
    <section class="video-section">
        <h2 class="section-title">See It In Action</h2>
        <div class="video-container">
            <!-- Video Placeholder - User to replace src with actual video file -->
            <img src="assets/video/demo.webp" alt="TeamTrack360 Demo" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            <div class="video-placeholder" style="display: none; padding: 4rem;">
                <i class="fas fa-play-circle" style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.7;"></i>
                <p>Demo Video Coming Soon</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> TeamTrack360. All rights reserved.</p>
    </footer>

</body>
</html>
