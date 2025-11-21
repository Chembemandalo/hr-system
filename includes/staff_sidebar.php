<ul class="sidebar-nav">
    <div class="sidebar-brand">
        Rockview Staff
    </div>
    <li>
        <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            Dashboard
        </a>
    </li>
    <li>
        <a href="attendance.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'attendance.php' ? 'active' : ''; ?>">
            My Attendance
        </a>
    </li>
    <li>
        <a href="leave_apply.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'leave_apply.php' ? 'active' : ''; ?>">
            Leave Application
        </a>
    </li>
    <li>
        <a href="salary_slip.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'salary_slip.php' ? 'active' : ''; ?>">
            My Salary
        </a>
    </li>
    <li>
        <a href="my_tickets.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'my_tickets.php' ? 'active' : ''; ?>">
            My Tickets
        </a>
    </li>
    <li>
        <a href="feedback.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'feedback.php' ? 'active' : ''; ?>">
            Feedback
        </a>
    </li>
    <li>
        <a href="../logout.php" class="text-danger">
            Logout
        </a>
    </li>
</ul>
