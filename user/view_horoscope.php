<?php
include '../includes/auth.php';
include '../includes/header.php';
include '../includes/navbar.php';
require '../config/db.php';

$zodiac = $_SESSION['user']['zodiac'] ?? '';
$type = $_GET['type'] ?? 'daily';

$horoscope = null;
$zodiac_description = '';
$horoscope_tip = '';

if ($zodiac) {
    // Fetch latest horoscope content
    $stmt = $conn->prepare("SELECT content, created_at FROM horoscopes WHERE zodiac = ? AND type = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("ss", $zodiac, $type);
    $stmt->execute();
    $result = $stmt->get_result();
    $horoscope = $result->fetch_assoc();

    // Fetch zodiac description and tip (assuming you have these in your DB or define manually)
    $desc_stmt = $conn->prepare("SELECT description, tip FROM zodiac_info WHERE zodiac = ?");
    $desc_stmt->bind_param("s", $zodiac);
    $desc_stmt->execute();
    $desc_result = $desc_stmt->get_result();
    $desc_data = $desc_result->fetch_assoc();

    if ($desc_data) {
        $zodiac_description = $desc_data['description'];
        $horoscope_tip = $desc_data['tip'];
    }
}
?>

<div class="container mt-4">
    <h2>View Horoscope</h2>
    <form method="GET" class="mb-3">
        <label for="type" class="form-label">Horoscope Type</label>
        <select name="type" id="type" class="form-control" onchange="this.form.submit()">
            <option value="daily" <?= $type === 'daily' ? 'selected' : '' ?>>Daily</option>
            <option value="weekly" <?= $type === 'weekly' ? 'selected' : '' ?>>Weekly</option>
            <option value="monthly" <?= $type === 'monthly' ? 'selected' : '' ?>>Monthly</option>
        </select>
    </form>

    <?php if ($zodiac_description): ?>
        <div class="alert alert-info mb-4">
            <h5>About <?= htmlspecialchars(ucfirst($zodiac)) ?></h5>
            <p><?= nl2br(htmlspecialchars($zodiac_description)) ?></p>
        </div>
    <?php endif; ?>

    <?php if ($horoscope): ?>
        <div class="card mb-4">
            <div class="card-header">
                <?= ucfirst($type) ?> Horoscope for <?= htmlspecialchars(ucfirst($zodiac)) ?> (<?= date('F j, Y', strtotime($horoscope['created_at'])) ?>)
            </div>
            <div class="card-body">
                <p><?= nl2br(htmlspecialchars($horoscope['content'])) ?></p>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">No horoscope available for your zodiac sign and type.</div>
    <?php endif; ?>

    <?php if ($horoscope_tip): ?>
        <div class="alert alert-success">
            <strong>Tip:</strong> <?= nl2br(htmlspecialchars($horoscope_tip)) ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
