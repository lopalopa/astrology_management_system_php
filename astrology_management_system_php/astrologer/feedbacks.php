<?php
include '../includes/auth.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
require '../config/db.php';

$astrologer_id = $_SESSION['user']['id'];
?>

<div class="container mt-4">
    <h2>Feedbacks</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Feedback ID</th>
                <th>User Name</th>
                <th>Rating</th>
                <th>Comments</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->prepare("SELECT f.id, u.name, f.rating, f.comments, f.created_at FROM feedbacks f JOIN users u ON f.user_id = u.id WHERE f.astrologer_id = ?");
            $stmt->bind_param("i", $astrologer_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($feedback = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$feedback['id']}</td>
                        <td>{$feedback['name']}</td>
                        <td>{$feedback['rating']}</td>
                        <td>{$feedback['comments']}</td>
                        <td>{$feedback['created_at']}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
