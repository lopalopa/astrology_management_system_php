<!-- includes/sidebar.php -->
<?php
if (!isset($_SESSION['user'])) {
    // Redirect if not logged in
    header("Location: /auth/login.php");
    exit();
}

$role = $_SESSION['user']['role']; // Assumes user session contains ['role']
?>

<div class="d-flex flex-column p-3 bg-light" style="width: 250px; height: 100vh;">
    <h4 class="text-center">
        <?php
        if ($role === 'admin') echo 'Admin Panel';
        elseif ($role === 'astrologer') echo 'Astrologer Panel';
        else echo 'User Panel';
        ?>
    </h4>

    <ul class="nav nav-pills flex-column mb-auto">
        <?php if ($role === 'admin'): ?>
            <li class="nav-item">
                <a href="../admin/dashboard.php" class="nav-link">Dashboard</a>
            </li>
            <li>
                <a href="../admin/manage_users.php" class="nav-link">Manage Users</a>
            </li>
            <li>
                <a href="../admin/manage_astrologers.php" class="nav-link">Manage Astrologers</a>
            </li>
            <li>
                <a href="../admin/manage_horoscopes.php" class="nav-link">Manage Horoscopes</a>
            </li>
            <li>
                <a href="../admin/view_appointments.php" class="nav-link">Appointments</a>
            </li>
            <li>
                <a href="../admin/reports.php" class="nav-link">Reports</a>
            </li>

        <?php elseif ($role === 'astrologer'): ?>
            <li>
                <a href="../astrologer/dashboard.php" class="nav-link">Dashboard</a>
            </li>
              <li>
                <a href="../astrologer/add_astrologer_details.php" class="nav-link">Add Details(Astrolegers)</a>
            </li>
            <li>
                <a href="../astrologer/view_appointments.php" class="nav-link">My Appointments</a>
            </li>
            <li>
                <a href="../astrologer/horoscope_editor.php" class="nav-link">Edit Horoscopes</a>
            </li>

        <?php elseif ($role === 'user'): ?>
            <li>
                <a href="../user/dashboard.php" class="nav-link">Dashboard</a>
            </li>
                        <li>
                <a href="../user/add_details_user.php" class="nav-link">Add details(User) for Horoscope</a>
            </li>

            <li>
                <a href="../user/book_appointment.php" class="nav-link">Book Appointment</a>
            </li>
            <li>
                <a href="../user/view_horoscope_user.php" class="nav-link">View Horoscope</a>
            </li>

            
        <?php endif; ?>

        <li>
            <a href="../auth/logout.php" class="nav-link text-danger">Logout</a>
        </li>
    </ul>
</div>
