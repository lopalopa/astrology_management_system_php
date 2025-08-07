<?php
include '../includes/auth.php';
include '../includes/header.php';
require '../config/db.php';

$msg = '';

// Delete astrologer profile
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM astrologers WHERE id = $id");
    header("Location: manage_astrologers.php");
    exit();
}

// Fetch astrologers joined with user data
$query = "
    SELECT a.id, a.user_id, u.name, u.email, a.bio, a.expertise, a.profile_image, a.created_at
    FROM astrologers a
    JOIN users u ON a.user_id = u.id
    ORDER BY a.created_at DESC
";
$astros = $conn->query($query);
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <?php include '../includes/sidebar.php';?>
        </div>
        <div class="col-md-9">
    <h2>Manage Astrologers</h2>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name (User)</th>
                <th>Email</th>
                <th>Expertise</th>
                <th>Bio</th>
                <th>Profile Image</th>
                <th>Joined On</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($astros->num_rows > 0): ?>
                <?php while ($row = $astros->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['expertise']) ?></td>
                        <td style="max-width: 200px; white-space: pre-wrap;"><?= htmlspecialchars($row['bio']) ?></td>
                        <td>
                            <?php if ($row['profile_image']): ?>
                                <img src="../uploads/<?= htmlspecialchars($row['profile_image']) ?>" alt="Profile Image" width="70" />
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td>
                            <a href="edit_astrologer.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mb-1">Edit</a>
                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this astrologer?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">No astrologers found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</div>

<?php include '../includes/footer.php'; ?>
