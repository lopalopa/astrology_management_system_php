<?php
include '../includes/auth.php';
include '../includes/header.php';
require '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch user data securely
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='container mt-4'><h3>User not found.</h3></div>";
    include '../includes/footer.php';
    exit();
}

$user = $result->fetch_assoc();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <?php include'../includes/sidebar.php';?>
        </div>
        <div class="col-md-9">
    <h2>User Details</h2>
    <table class="table table-bordered w-50">
        <tr>
            <th>ID</th>
            <td><?= htmlspecialchars($user['id']) ?></td>
        </tr>
        <tr>
            <th>Name</th>
            <td><?= htmlspecialchars($user['name']) ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= htmlspecialchars($user['email']) ?></td>
        </tr>
        <tr>
            <th>Role</th>
            <td><?= htmlspecialchars(ucfirst($user['role'])) ?></td>
        </tr>
        <tr>
            <th>Created At</th>
            <td><?= htmlspecialchars($user['created_at']) ?></td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td><?= htmlspecialchars($user['updated_at']) ?></td>
        </tr>
    </table>
    <a href="manage_users.php" class="btn btn-secondary">Back to Manage Users</a>
</div>
</div>

<?php include '../includes/footer.php'; ?>
