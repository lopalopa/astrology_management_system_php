<?php
include '../includes/auth.php';
include '../includes/header.php';
require '../config/db.php';

$user_id = $_SESSION['user']['id'];
$message = '';

// Fetch existing horoscope details for user (if any)
$stmt = $conn->prepare("SELECT dob, birth_time, birth_place, gender FROM horoscope_details WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$details = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dob = $_POST['dob'];
    $birth_time = $_POST['birth_time'];
    $birth_place = $_POST['birth_place'];
    $gender = $_POST['gender'];

    if ($details) {
        // Update existing details
        $stmt = $conn->prepare("UPDATE horoscope_details SET dob=?, birth_time=?, birth_place=?, gender=? WHERE user_id=?");
        $stmt->bind_param("ssssi", $dob, $birth_time, $birth_place, $gender, $user_id);
    } else {
        // Insert new details
        $stmt = $conn->prepare("INSERT INTO horoscope_details (user_id, dob, birth_time, birth_place, gender) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $dob, $birth_time, $birth_place, $gender);
    }

    if ($stmt->execute()) {
        $message = "Details saved successfully.";
        // Refresh details after save
        $stmt = $conn->prepare("SELECT dob, birth_time, birth_place, gender FROM horoscope_details WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $details = $result->fetch_assoc();
    } else {
        $message = "Error: " . $stmt->error;
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
    <h2><?= $details ? 'Update' : 'Add' ?> User Details</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" name="dob" id="dob" class="form-control" 
                value="<?= htmlspecialchars($details['dob'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="birth_time" class="form-label">Time of Birth</label>
            <input type="time" name="birth_time" id="birth_time" class="form-control" 
                value="<?= htmlspecialchars($details['birth_time'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="birth_place" class="form-label">Place of Birth</label>
            <input type="text" name="birth_place" id="birth_place" class="form-control" 
                value="<?= htmlspecialchars($details['birth_place'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="gender" class="form-label">Gender</label>
            <select name="gender" id="gender" class="form-control" required>
                <option value="">Choose Gender</option>
                <option value="male" <?= (isset($details['gender']) && $details['gender'] === 'male') ? 'selected' : '' ?>>Male</option>
                <option value="female" <?= (isset($details['gender']) && $details['gender'] === 'female') ? 'selected' : '' ?>>Female</option>
                <option value="other" <?= (isset($details['gender']) && $details['gender'] === 'other') ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><?= $details ? 'Update' : 'Submit' ?> Details</button>
    </form>
</div>
</div>
</div>

<?php include '../includes/footer.php'; ?>
