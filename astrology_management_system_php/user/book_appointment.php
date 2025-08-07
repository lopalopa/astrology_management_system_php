<?php
include '../includes/auth.php';
include '../includes/header.php';
require '../config/db.php';

$user_id = $_SESSION['user']['id'];
$success = '';
$error = '';

// ✅ Fetch astrologers from the astrologers table (joined with users to get name)
$astrologers = $conn->query("
    SELECT a.id AS astrologer_id, u.name 
    FROM astrologers a
    JOIN users u ON a.user_id = u.id
");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $astrologer_id = $_POST['astrologer_id'];
    $appointment_date = $_POST['appointment_date'];
    $message = $_POST['message'];

    // ✅ Prepare statement to avoid SQL injection
    $stmt = $conn->prepare("
        INSERT INTO appointments (user_id, astrologer_id, appointment_date, message, created_at) 
        VALUES (?, ?, ?, ?, NOW())
    ");

    if (!$stmt) {
        $error = "Prepare failed: " . $conn->error;
    } else {
        $stmt->bind_param("iiss", $user_id, $astrologer_id, $appointment_date, $message);

        if ($stmt->execute()) {
            $success = "Appointment booked successfully.";
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
<?php include '../includes/sidebar.php';
?>
        </div>
        <div class="col-md-9">
    <h2>Book Appointment</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="astrologer_id" class="form-label">Select Astrologer</label>
            <select name="astrologer_id" id="astrologer_id" class="form-control" required>
                <option value="">Choose Astrologer</option>
                <?php while ($row = $astrologers->fetch_assoc()): ?>
                    <option value="<?= $row['astrologer_id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="appointment_date" class="form-label">Appointment Date</label>
            <input type="date" name="appointment_date" id="appointment_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea name="message" id="message" class="form-control" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Book Appointment</button>
    </form>
</div>
                </div>
                </div>
<?php include '../includes/footer.php'; ?>
