<ul class="sidebar-nav">
    <div class="sidebar-brand">
        Rockview Admin
    </div>
    <li>
        <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            Dashboard
        </a>
    </li>
    <li>
        <a href="staff_list.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'staff_list.php' ? 'active' : ''; ?>">
            Staff Management
        </a>
    </li>
    <li>
        <a href="attendance_report.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'attendance_report.php' ? 'active' : ''; ?>">
            Attendance
        </a>
    </li>
    <li>
        <a href="manage_leave.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_leave.php' ? 'active' : ''; ?>">
            Leave Management
        </a>
    </li>
    <li>
        <a href="salary_manage.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'salary_manage.php' ? 'active' : ''; ?>">
            Payroll & Salary
        </a>
    </li>
    <li>
        <a href="tickets.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'tickets.php' ? 'active' : ''; ?>">
            Tickets & Tasks
        </a>
    </li>
    <li>
        <a href="performance_review.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'performance_review.php' ? 'active' : ''; ?>">
            Performance
        </a>
    </li>
    <li>
        <a href="announcements.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'announcements.php' ? 'active' : ''; ?>">
            Announcements
        </a>
    </li>
    <li>
        <a href="../logout.php" class="text-danger">
            Logout
        </a>
    </li>
</ul>
