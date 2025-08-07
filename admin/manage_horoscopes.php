<?php
include '../includes/auth.php';
include '../includes/header.php';
require '../config/db.php';

$msg = '';
$error = '';

// Handle add form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $date_range = $_POST['date_range'];
    $element = $_POST['element'];
    $modality = $_POST['modality'];
    $description = $_POST['description'];

    // Handle image upload
    $image_name = null;
    if (!empty($_FILES['image']['name'])) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $target_dir = '../uploads/';
        $target_file = $target_dir . $image_name;

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed_types)) {
            if (move_uploaded_file($image_tmp, $target_file)) {
                // Uploaded successfully
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid image format. Allowed: jpg, jpeg, png, gif.";
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("INSERT INTO zodiac_signs (name, date_range, element, modality, description, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $date_range, $element, $modality, $description, $image_name);
        if ($stmt->execute()) {
            $msg = "Zodiac sign added successfully.";
        } else {
            $error = "Database error: " . $stmt->error;
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Delete image file before deleting row
    $res = $conn->query("SELECT image FROM zodiac_signs WHERE id = $id");
    if ($res && $res->num_rows) {
        $row = $res->fetch_assoc();
        if ($row['image'] && file_exists('../uploads/' . $row['image'])) {
            unlink('../uploads/' . $row['image']);
        }
    }

    $conn->query("DELETE FROM zodiac_signs WHERE id = $id");
    header("Location: manage_zodiac_signs.php");
    exit();
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <?php include '../includes/sidebar.php';?></div>
<div class="col-md-9">    <h2>Manage Zodiac Signs</h2>

    <?php if ($msg): ?>
        <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <input type="text" name="name" placeholder="Name" class="form-control mb-2" required>
        <input type="text" name="date_range" placeholder="Date Range (e.g. Mar 21 - Apr 19)" class="form-control mb-2" required>
        <input type="text" name="element" placeholder="Element (e.g. Fire)" class="form-control mb-2">
        <input type="text" name="modality" placeholder="Modality (e.g. Cardinal)" class="form-control mb-2">
        <textarea name="description" placeholder="Description" class="form-control mb-2" rows="3"></textarea>
        <input type="file" name="image" class="form-control mb-2" accept="image/*">
        <button class="btn btn-primary">Add Zodiac Sign</button>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Date Range</th>
                <th>Element</th>
                <th>Modality</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM zodiac_signs ORDER BY id DESC");
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['date_range']) ?></td>
                <td><?= htmlspecialchars($row['element']) ?></td>
                <td><?= htmlspecialchars($row['modality']) ?></td>
                <td style="max-width:250px; white-space: pre-wrap;"><?= htmlspecialchars($row['description']) ?></td>
                <td>
                    <?php if ($row['image'] && file_exists('../uploads/' . $row['image'])): ?>
                        <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="Image" width="70" />
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_zodiac_sign.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mb-1">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this zodiac sign?')">Delete</a>
                </td>
            </tr>
            <?php
                endwhile;
            else:
            ?>
            <tr><td colspan="8" class="text-center">No zodiac signs found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</div>
</div>

<?php include '../includes/footer.php'; ?>
