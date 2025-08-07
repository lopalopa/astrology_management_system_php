<?php
include '../includes/auth.php';
include '../includes/header.php';
include '../includes/sidebar.php';
require '../config/db.php';

// Get the astrologer ID from query
$astrologer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch astrologer data
$query = "
    SELECT a.*, u.name AS user_name, u.email 
    FROM astrologers a 
    JOIN users u ON a.user_id = u.id 
    WHERE a.id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $astrologer_id);
$stmt->execute();
$result = $stmt->get_result();
$astrologer = $result->fetch_assoc();

if (!$astrologer) {
    echo "<div class='alert alert-danger'>Astrologer not found.</div>";
    exit;
}

// Handle update submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bio = $_POST['bio'];
    $expertise = $_POST['expertise'];
    
    // Handle profile image upload
    if (!empty($_FILES['profile_image']['name'])) {
        $targetDir = "../uploads/";
        $filename = time() . '_' . basename($_FILES["profile_image"]["name"]);
        $targetFile = $targetDir . $filename;
        move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile);
        $profile_image = $filename;

        $update = $conn->prepare("UPDATE astrologers SET bio = ?, expertise = ?, profile_image = ? WHERE id = ?");
        $update->bind_param("sssi", $bio, $expertise, $profile_image, $astrologer_id);
    } else {
        $update = $conn->prepare("UPDATE astrologers SET bio = ?, expertise = ? WHERE id = ?");
        $update->bind_param("ssi", $bio, $expertise, $astrologer_id);
    }

    if ($update->execute()) {
        echo "<div class='alert alert-success'>Astrologer updated successfully.</div>";
        // Refresh astrologer data
        header("Location: edit_astrologer.php?id=" . $astrologer_id);
        exit;
    } else {
        echo "<div class='alert alert-danger'>Update failed.</div>";
    }
}
?>

<div class="container mt-4">
    <h2>Edit Astrologer</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>User</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($astrologer['user_name']) ?> (<?= htmlspecialchars($astrologer['email']) ?>)" readonly>
        </div>
        <div class="mb-3">
            <label>Expertise</label>
            <input type="text" name="expertise" class="form-control" value="<?= htmlspecialchars($astrologer['expertise']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Bio</label>
            <textarea name="bio" class="form-control" required><?= htmlspecialchars($astrologer['bio']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Profile Image</label><br>
            <?php if (!empty($astrologer['profile_image'])): ?>
                <img src="../uploads/<?= htmlspecialchars($astrologer['profile_image']) ?>" alt="Profile Image" width="100"><br><br>
            <?php endif; ?>
            <input type="file" name="profile_image" class="form-control">
        </div>
        <button class="btn btn-primary">Update</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
