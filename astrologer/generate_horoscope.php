<?php
include '../includes/auth.php';
include '../includes/header.php';
require '../config/db.php';

// Validate user_id parameter
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    echo "<div class='alert alert-danger mt-4'>Invalid or missing user ID.</div>";
    include '../includes/footer.php';
    exit;
}

$user_id = intval($_GET['user_id']);
$appointment_id = isset($_GET['appointment_id']) ? intval($_GET['appointment_id']) : null;

// ✅ Update appointment status from 'pending' to 'confirmed' if appointment_id is provided
if ($appointment_id) {
    $stmtUpdate = $conn->prepare("UPDATE appointments SET status = 'confirmed' WHERE id = ? AND user_id = ? AND status = 'pending'");
    $stmtUpdate->bind_param("ii", $appointment_id, $user_id);
    $stmtUpdate->execute();
    $stmtUpdate->close();
}

// Fetch user information
$stmtUser = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmtUser->bind_param("i", $user_id);
$stmtUser->execute();
$stmtUser->bind_result($user_name, $user_email);
if (!$stmtUser->fetch()) {
    echo "<div class='alert alert-danger mt-4'>User not found.</div>";
    include '../includes/footer.php';
    exit;
}
$stmtUser->close();

// Fetch horoscope details for the user
$stmt = $conn->prepare("SELECT dob, birth_time, birth_place, gender FROM horoscope_details WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($dob, $birth_time, $birth_place, $gender);

if (!$stmt->fetch()) {
    echo "<div class='alert alert-warning mt-4'>Horoscope details not found for user <strong>" . htmlspecialchars($user_name) . "</strong>. Please add horoscope details to generate the horoscope.</div>";
    include '../includes/footer.php';
    exit;
}
$stmt->close();

// Function to calculate zodiac sign based on DOB
function getZodiacSign($dob) {
    $timestamp = strtotime($dob);
    if (!$timestamp) return "Unknown";

    $day = date('d', $timestamp);
    $month = date('m', $timestamp);

    if (($month == 1 && $day <= 19) || ($month == 12 && $day >= 22)) return "Capricorn";
    if (($month == 1 && $day >= 20) || ($month == 2 && $day <= 18)) return "Aquarius";
    if (($month == 2 && $day >= 19) || ($month == 3 && $day <= 20)) return "Pisces";
    if (($month == 3 && $day >= 21) || ($month == 4 && $day <= 19)) return "Aries";
    if (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) return "Taurus";
    if (($month == 5 && $day >= 21) || ($month == 6 && $day <= 20)) return "Gemini";
    if (($month == 6 && $day >= 21) || ($month == 7 && $day <= 22)) return "Cancer";
    if (($month == 7 && $day >= 23) || ($month == 8 && $day <= 22)) return "Leo";
    if (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) return "Virgo";
    if (($month == 9 && $day >= 23) || ($month == 10 && $day <= 22)) return "Libra";
    if (($month == 10 && $day >= 23) || ($month == 11 && $day <= 21)) return "Scorpio";
    if (($month == 11 && $day >= 22) || ($month == 12 && $day <= 21)) return "Sagittarius";

    return "Unknown";
}

$zodiac = getZodiacSign($dob);

// Horoscope messages
$horoscope_messages = [
    "Aries" => [
        "general" => "Aries, energetic and courageous, you're always ready for new challenges.",
        "today" => "Your leadership shines today. Be bold, but avoid impulsiveness.",
        "advice" => "Stay focused and patient with others."
    ],
    "Taurus" => [
        "general" => "Taurus, grounded and dependable, you enjoy stability.",
        "today" => "Good day to handle finances. Be patient.",
        "advice" => "Avoid being too rigid in thoughts."
    ],
    "Gemini" => [
        "general" => "Gemini, curious and witty, you adapt quickly to changes.",
        "today" => "Expect lively conversations and new ideas.",
        "advice" => "Stay focused and don’t get distracted."
    ],
    "Cancer" => [
        "general" => "Cancer, nurturing and intuitive, home is your strength.",
        "today" => "Emotional connections are strong today.",
        "advice" => "Take care of yourself as you care for others."
    ],
    "Leo" => [
        "general" => "Leo, bold and charismatic, you love being in the spotlight.",
        "today" => "Your confidence draws people toward you.",
        "advice" => "Balance pride with humility."
    ],
    "Virgo" => [
        "general" => "Virgo, practical and analytical, you seek perfection.",
        "today" => "Great day to organize and declutter.",
        "advice" => "Don’t be too critical of yourself or others."
    ],
    "Libra" => [
        "general" => "Libra, diplomatic and charming, you value balance.",
        "today" => "Good time for socializing and resolving conflicts.",
        "advice" => "Trust your inner voice in making decisions."
    ],
    "Scorpio" => [
        "general" => "Scorpio, passionate and determined, you're deeply intuitive.",
        "today" => "Trust your instincts, but guard your emotions.",
        "advice" => "Let go of past grudges to move forward."
    ],
    "Sagittarius" => [
        "general" => "Sagittarius, adventurous and optimistic, you crave knowledge.",
        "today" => "Explore new ideas or places.",
        "advice" => "Stay grounded and think before acting."
    ],
    "Capricorn" => [
        "general" => "Capricorn, ambitious and disciplined, you work hard for success.",
        "today" => "A good day to focus on long-term goals.",
        "advice" => "Allow yourself some rest too."
    ],
    "Aquarius" => [
        "general" => "Aquarius, independent and visionary, you think outside the box.",
        "today" => "Innovative solutions may come your way.",
        "advice" => "Don’t isolate yourself emotionally."
    ],
    "Pisces" => [
        "general" => "Pisces, intuitive and compassionate, your imagination is a strength.",
        "today" => "Focus on creative tasks or helping others.",
        "advice" => "Set boundaries to avoid emotional overload."
    ],
    "Unknown" => [
        "general" => "We could not determine your zodiac sign. Please check your birth date.",
        "today" => "No horoscope available due to incomplete or invalid date.",
        "advice" => "Please verify your details and try again."
    ]
];
// Get messages
$message = $horoscope_messages[$zodiac];

// Insert into generated_horoscope table
$stmtInsert = $conn->prepare("INSERT INTO generated_horoscope (user_id, zodiac, general_message, today_message, advice_message) VALUES (?, ?, ?, ?, ?)");
$stmtInsert->bind_param("issss", $user_id, $zodiac, $message['general'], $message['today'], $message['advice']);
$stmtInsert->execute();
$stmtInsert->close();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-9">
            <h1 class="mb-4">Horoscope Report for <?= htmlspecialchars($user_name) ?></h1>

            <div class="card mb-4">
                <div class="card-header"><strong>User Details</strong></div>
                <div class="card-body">
                    <p><strong>Name:</strong> <?= htmlspecialchars($user_name) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user_email) ?></p>
                    <p><strong>Date of Birth:</strong> <?= htmlspecialchars($dob) ?></p>
                    <p><strong>Birth Time:</strong> <?= htmlspecialchars($birth_time) ?></p>
                    <p><strong>Birth Place:</strong> <?= htmlspecialchars($birth_place) ?></p>
                    <p><strong>Gender:</strong> <?= htmlspecialchars(ucfirst($gender)) ?></p>
                    <p><strong>Zodiac Sign:</strong> <span class="badge bg-primary"><?= $zodiac ?></span></p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h4>General Horoscope Overview</h4>
                </div>
                <div class="card-body">
                    <p><?= $message['general'] ?></p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h4>Today's Prediction</h4>
                </div>
                <div class="card-body">
                    <p><?= $message['today'] ?></p>
                </div>
            </div>

            <div class="card mb-5">
                <div class="card-header bg-success text-white">
                    <h4>Advice for You</h4>
                </div>
                <div class="card-body">
                    <p><?= $message['advice'] ?></p>
                </div>
            </div>

            <div class="alert alert-secondary">
                <small>Horoscopes are for entertainment and guidance purposes only.</small>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
