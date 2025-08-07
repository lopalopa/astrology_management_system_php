<?php
include '../includes/auth.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
require '../config/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch zodiac data
$stmt = $conn->prepare("SELECT * FROM zodiac_signs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$zodiac = $result->fetch_assoc();

if (!$zodiac) {
    echo "<div class='container mt-4 alert alert-danger'>Zodiac sign not found.</div>";
    include '../includes/footer.php';
    exit;
}

$msg = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $date_range = $_POST['date_range'];
    $element = $_POST['element'];
    $modality = $_POST['modality'];
    $description = $_POST['description'];
    $image = $zodiac['image']; // default to existing image

    // Image upload (optional)
    if (!empty($_FILES['image']['name'])) {
        $image_name = basename($_FILES['image']['name']);
        $target_path = "../uploads/zodiac/" . $image_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image = $image_name;
        }
    }

    // Update the zodiac sign
    $update = $conn->prepare("UPDATE zodiac_signs SET name=?, date_range=?, element=?, modality=?, description=?, image=? WHERE id=?");
    $update->bind_param("ssssssi", $name, $date_range, $element, $modality, $description, $image, $id);
    $update->execute();

    $msg = "Zodiac sign updated successfully.";

    // Refresh data
    $stmt = $conn->prepare("SELECT * FROM zodiac_signs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $zodiac = $stmt->get_result()->fetch_assoc();
}
?>

<div class="container mt-4">
    <h2>Edit Zodiac Sign</h2>

    <?php if ($msg): ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-2">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($zodiac['name']) ?>" required>
        </div>

        <div class="mb-2">
            <label>Date Range</label>
            <input type="text" name="date_range" class="form-control" value="<?= htmlspecialchars($zodiac['date_range']) ?>" required>
        </div>

        <div class="mb-2">
            <label>Element</label>
            <input type="text" name="element" class="form-control" value="<?= htmlspecialchars($zodiac['element']) ?>">
        </div>

        <div class="mb-2">
            <label>Modality</label>
            <input type="text" name="modality" class="form-control" value="<?= htmlspecialchars($zodiac['modality']) ?>">
        </div>

        <div class="mb-2">
            <label>Description</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($zodiac['description']) ?></textarea>
        </div>

        <div class="mb-2">
            <label>Current Image</label><br>
            <?php if (!empty($zodiac['image'])): ?>
                <img src="../uploads/zodiac/<?= htmlspecialchars($zodiac['image']) ?>" width="100" alt="Zodiac Image"><br>
            <?php else: ?>
                <p>No image uploaded.</p>
            <?php endif; ?>
        </div>

        <div class="mb-2">
            <label>Change Image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-success">Update Zodiac</button>
        <a href="manage_zodiacs.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
