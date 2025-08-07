<?php
include '../includes/navbar.php';
require '../config/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT * FROM zodiac_signs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$zodiac = $result->fetch_assoc();

if (!$zodiac) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Zodiac sign not found.</div></div>";
    include '../includes/footer.php';
    exit;
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <img src="../assets/images/zodiac/<?= htmlspecialchars($zodiac['image']) ?>" alt="<?= htmlspecialchars($zodiac['name']) ?>" class="img-fluid">
        </div>
        <div class="col-md-8">
            <h2><?= htmlspecialchars($zodiac['name']) ?></h2>
            <p><strong>Date Range:</strong> <?= htmlspecialchars($zodiac['date_range']) ?></p>
            <p><?= nl2br(htmlspecialchars($zodiac['description'])) ?></p>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
