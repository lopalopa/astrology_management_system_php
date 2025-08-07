<?php
include '../includes/auth.php';
include '../includes/header.php';
require '../config/db.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3"><?php include '../includes/sidebar.php';
?></div>
        <div class="col-md-9">
    <h2>Reports</h2>
    <ul>
        <li>Total Users: 
            <?php
            $res = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='user'");
            echo $res->fetch_assoc()['total'];
            ?>
        </li>
        <li>Total Appointments: 
            <?php
            $res = $conn->query("SELECT COUNT(*) as total FROM appointments");
            echo $res->fetch_assoc()['total'];
            ?>
        </li>
        <li>Total Astrologers: 
            <?php
            $res = $conn->query("SELECT COUNT(*) as total FROM astrologers");
            echo $res->fetch_assoc()['total'];
            ?>
        </li>
    </ul>
</div>
</div>
</div>

<?php include '../includes/footer.php'; ?>
