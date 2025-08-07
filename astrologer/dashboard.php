<?php
include '../includes/auth.php';
include '../includes/header.php';
require '../config/db.php';

$astrologer_id = $_SESSION['user']['id'];

// Fetch statistics
$appointments = $conn->query("SELECT COUNT(*) AS total FROM appointments WHERE astrologer_id = $astrologer_id")->fetch_assoc()['total'];
$horoscopes = $conn->query("SELECT COUNT(*) AS total FROM horoscopes WHERE astrologer_id = $astrologer_id")->fetch_assoc()['total'];
$feedbacks = $conn->query("SELECT COUNT(*) AS total FROM feedbacks WHERE astrologer_id = $astrologer_id")->fetch_assoc()['total'];
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <?php include '../includes/sidebar.php';
?>

        </div>
        <div class="col-md-9">
    <h2>Astrologer Dashboard</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Appointments</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $appointments ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Horoscopes</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $horoscopes ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Feedbacks</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $feedbacks ?></h5>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<?php include '../includes/footer.php'; ?>
