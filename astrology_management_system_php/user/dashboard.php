<?php
include '../includes/auth.php';
include '../includes/header.php';
require '../config/db.php';

$user_id = $_SESSION['user']['id'];

// Fetch user statistics
$appointments = $conn->query("SELECT COUNT(*) AS total FROM appointments WHERE user_id = $user_id")->fetch_assoc()['total'];
$feedbacks = $conn->query("SELECT COUNT(*) AS total FROM feedbacks WHERE user_id = $user_id")->fetch_assoc()['total'];
?>

<div class="container mt-4">
    <div class="row">

        <div class="col-md-3">
           <?php include '../includes/sidebar.php'; ?>

</div>
<div class="col-md-9">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Appointments</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $appointments ?></h5>
                </div>
            </div>
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Feedbacks</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $feedbacks ?></h5>
                </div>
            </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
