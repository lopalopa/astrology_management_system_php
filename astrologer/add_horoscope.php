<?php
include '../includes/auth.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
require '../config/db.php';

$astrologer_id = $_SESSION['user']['id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $zodiac = $_POST['zodiac'];
    $type = $_POST['type'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO horoscopes (astrologer_id, zodiac, type, content, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isss", $astrologer_id, $zodiac, $type, $content);
    if ($stmt->execute()) {
        $message = "Horoscope added successfully.";
    } else {
        $message = "Error adding horoscope.";
    }
}
?>

<div class="container mt-4">
    <h2>Add Horoscope</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="zodiac" class="form-label">Zodiac Sign</label>
            <select name="zodiac" id="zodiac" class="form-control" required>
                <option value="">Select Zodiac</option>
                <?php
                $zodiacs = ['Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo',
                            'Libra', 'Scorpio', 'Sagittarius', 'Capricorn', 'Aquarius', 'Pisces'];
                foreach ($zodiacs as $sign) {
                    echo "<option value=\"$sign\">$sign</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Horoscope Type</label>
            <select name="type" id="type" class="form-control" required>
                <option value="">Select Type</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Horoscope Content</label>
            <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Horoscope</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
