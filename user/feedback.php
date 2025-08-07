<?php
include '../includes/auth.php';
include '../includes/header.php';
include '../includes/navbar.php';
require '../config/db.php';

$user_id = $_SESSION['user']['id'];
$message = '';

// Fetch astrologers
$astrologers = $conn->query("SELECT id, name FROM users WHERE role = 'astrologer'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $astrologer_id = $_POST['astrologer_id'];
    $rating = $_POST['rating'];
    $comments = $_POST['comments'];

    $stmt = $conn->prepare("INSERT INTO feedbacks (user_id, astrologer_id, rating, comments, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiis", $user_id, $astrologer_id, $rating, $comments);
    if ($stmt->execute()) {
        $message = "Feedback submitted successfully.";
    } else {
        $message = "Error submitting feedback.";
    }
}
?>

<div class="container mt-4">
    <h2>Give Feedback</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="astrologer_id" class="form-label">Select Astrologer</label>
            <select name="astrologer_id" id="astrologer_id" class="form-control" required>
                <option value="">Choose Astrologer</option>
                <?php while ($row = $astrologers->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="rating" class="form-label">Rating (1-5)</label>
            <input type="number" name="rating" id="rating" class="form-control" min="1" max="5" required>
        </div>
        <div class="mb-3">
            <label for="comments" class="form-label">Comments</label>
            <textarea name="comments" id="comments" class="form-control" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Feedback</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
