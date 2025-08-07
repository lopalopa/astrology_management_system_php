<?php
include '../includes/auth.php';
include '../includes/header.php';
require '../config/db.php';

// Validate user_id from URL

$user_id = $_SESSION['user']['id'];

// Fetch user, horoscope details, and latest generated horoscope using JOIN
$sql = "
SELECT 
    u.name, u.email,
    h.dob, h.birth_time, h.birth_place, h.gender,
    g.zodiac, g.general_message, g.today_message, g.advice_message, g.created_at AS generated_on
FROM 
    users u
LEFT JOIN 
    horoscope_details h ON u.id = h.user_id
LEFT JOIN 
    (
        SELECT * FROM generated_horoscope 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 1
    ) g ON u.id = g.user_id
WHERE 
    u.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='alert alert-warning mt-4'>No user or horoscope found for this user.</div>";
    include '../includes/footer.php';
    exit;
}

$row = $result->fetch_assoc();
?>

<div class="container mt-4">
    <h2 class="mb-4">Horoscope Details for <?= htmlspecialchars($row['name']) ?></h2>

    <!-- User & Horoscope Info -->
    <div class="card mb-4">
        <div class="card-header"><strong>User & Birth Details</strong></div>
        <div class="card-body">
            <p><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
            <p><strong>Date of Birth:</strong> <?= $row['dob'] ? htmlspecialchars($row['dob']) : 'Not provided' ?></p>
            <p><strong>Birth Time:</strong> <?= $row['birth_time'] ? htmlspecialchars($row['birth_time']) : 'Not provided' ?></p>
            <p><strong>Birth Place:</strong> <?= $row['birth_place'] ? htmlspecialchars($row['birth_place']) : 'Not provided' ?></p>
            <p><strong>Gender:</strong> <?= $row['gender'] ? htmlspecialchars(ucfirst($row['gender'])) : 'Not specified' ?></p>
        </div>
    </div>

    <!-- Horoscope Info -->
    <?php if (!empty($row['zodiac'])): ?>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4>Horoscope (<?= htmlspecialchars($row['zodiac']) ?>)</h4>
            </div>
            <div class="card-body">
                <p><strong>Generated On:</strong> <?= htmlspecialchars($row['generated_on']) ?></p>

                <div class="mb-3">
                    <h5>General Message</h5>
                    <p><?= nl2br(htmlspecialchars($row['general_message'])) ?></p>
                </div>

                <div class="mb-3">
                    <h5>Today's Message</h5>
                    <p><?= nl2br(htmlspecialchars($row['today_message'])) ?></p>
                </div>

                <div>
                    <h5>Advice</h5>
                    <p><?= nl2br(htmlspecialchars($row['advice_message'])) ?></p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            No horoscope has been generated yet for this user.
            <br><a href="generate_horoscope.php?user_id=<?= $user_id ?>" class="btn btn-sm btn-primary mt-2">Generate Now</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
