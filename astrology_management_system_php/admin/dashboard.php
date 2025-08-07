<?php
include '../includes/auth.php';
include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
<?php include '../includes/sidebar.php';
 ?>
    </div>
            <div class="col-md-9">

    <h2>Admin Dashboard</h2>
    <p>Welcome, <?= $_SESSION['user']['name']; ?>!</p>
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Total Users</h5>
                    <?php
                    require '../config/db.php';
                    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
                    $row = $result->fetch_assoc();
                    echo "<h3>{$row['total']}</h3>";
                    ?>
                </div>
            </div>
        </div>
        <!-- Repeat for astrologers, appointments, etc. -->
    </div>
    </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
