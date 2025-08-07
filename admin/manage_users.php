<?php
include '../includes/auth.php';
include '../includes/header.php';
require '../config/db.php';

// Handle deletion securely
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // sanitize input
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: manage_users.php");
    exit();
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <?php include '../includes/sidebar.php'; ?>
        </div>
        <div class="col-md-9">
            <h2>Manage Users</h2>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Email</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $users = $conn->query("SELECT * FROM users");
                    while ($row = $users->fetch_assoc()) {
                        $userId = $row['id'];
                        $name = htmlspecialchars($row['name']);
                        $email = htmlspecialchars($row['email']);
                        
                        echo "<tr>
                                <td>{$userId}</td>
                                <td>{$name}</td>
                                <td>{$email}</td>
                                <td>
                                    <a href='view_user.php?id={$userId}' class='btn btn-info btn-sm me-1'>View</a>
                                    <a href='edit_user.php?id={$userId}' class='btn btn-warning btn-sm me-1'>Edit</a>
                                    <a href='?delete={$userId}' onclick=\"return confirm('Are you sure you want to delete this user?');\" class='btn btn-danger btn-sm'>Delete</a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
