<?php
include '../includes/auth.php';
include '../includes/header.php';
require '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$id = intval($_GET['id']);
$error = '';
$success = '';

// Fetch user data
$result = $conn->query("SELECT * FROM users WHERE id = $id");
if ($result->num_rows === 0) {
    echo "<div class='container mt-4'><h3>User not found.</h3></div>";
    include '../includes/footer.php';
    exit();
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    // Basic validation
    if (empty($name) || empty($email) || empty($role)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Update query
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $role, $id);
        if ($stmt->execute()) {
            $success = "User updated successfully.";
            // Refresh user data
            $user['name'] = $name;
            $user['email'] = $email;
            $user['role'] = $role;
        } else {
            $error = "Error updating user.";
        }
    }
}
?>

<div class="container mt-4">
    <h2>Edit User</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($user['name']) ?>">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($user['email']) ?>">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="astrologer" <?= $user['role'] === 'astrologer' ? 'selected' : '' ?>>Astrologer</option>
                <!-- Add more roles if you have -->
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="manage_users.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
