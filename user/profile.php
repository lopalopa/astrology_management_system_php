
<?php
require_once '../includes/auth.php';     // To protect the page
require_once '../config/db.php';        // DB connection

$user_id = $_SESSION['user_id'];
$message = "";

// Fetch current user data
$query = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    // Basic validation
    if (empty($name) || empty($email)) {
        $message = "All fields are required.";
    } else {
        $update = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $update->bind_param("ssi", $name, $email, $user_id);

        if ($update->execute()) {
            $message = "Profile updated successfully.";
            $user['name'] = $name;
            $user['email'] = $email;
        } else {
            $message = "Failed to update profile.";
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-4">
    <h2>User Profile</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
