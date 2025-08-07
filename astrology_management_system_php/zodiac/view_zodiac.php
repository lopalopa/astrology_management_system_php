<?php
include '../includes/navbar.php';
require '../config/db.php';

// Fetch all zodiac signs
$result = $conn->query("SELECT * FROM zodiac_signs ORDER BY id ASC");
?>

<div class="container mt-4">
    <h2>All Zodiac Signs</h2>
    <div class="row">
        <?php while ($zodiac = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <img src="../assets/images/zodiac/<?= htmlspecialchars($zodiac['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($zodiac['name']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($zodiac['name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($zodiac['date_range']) ?></p>
                        <a href="zodiac_detail.php?id=<?= $zodiac['id'] ?>" class="btn btn-primary btn-sm">View Details</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
