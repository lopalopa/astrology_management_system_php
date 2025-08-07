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

// Horoscope messages for each zodiac sign
$horoscope_messages = [
    "Aries" => [
        "general" => "Aries, the energetic and courageous ram, you are full of enthusiasm and always ready to take on new challenges.",
        "today" => "Today, your natural leadership shines through. Trust your instincts and be decisive. Avoid impulsiveness.",
        "advice" => "Channel your energy into constructive tasks and maintain patience with others."
    ],
    "Taurus" => [
        "general" => "Taurus, reliable and patient, you value comfort and stability in life.",
        "today" => "Focus on financial matters today. A steady and practical approach will bring success.",
        "advice" => "Avoid stubbornness; be open to new ideas and flexible in your thinking."
    ],
    "Gemini" => [
        "general" => "Gemini, the curious and adaptable communicator, you thrive on variety and intellectual stimulation.",
        "today" => "An important conversation might arise. Use your charm and wit to navigate smoothly.",
        "advice" => "Stay organized to prevent scattered energy and misunderstandings."
    ],
    "Cancer" => [
        "general" => "Cancer, nurturing and intuitive, you are deeply connected to your emotions and those of others.",
        "today" => "Spend quality time with family or close friends. Emotional support will uplift you.",
        "advice" => "Guard your heart but don’t shy away from vulnerability."
    ],
    "Leo" => [
        "general" => "Leo, proud and charismatic, you enjoy being in the spotlight and inspiring others.",
        "today" => "Your creativity will be highlighted. Take the initiative on projects and express yourself boldly.",
        "advice" => "Balance confidence with humility to maintain strong relationships."
    ],
    "Virgo" => [
        "general" => "Virgo, detail-oriented and analytical, you have a keen eye for perfection.",
        "today" => "Organize your workspace or home. Attention to detail will pay off.",
        "advice" => "Avoid over-criticism and take time to appreciate small victories."
    ],
    "Libra" => [
        "general" => "Libra, the diplomatic peacemaker, you seek harmony and balance in relationships.",
        "today" => "Negotiations and partnerships look favorable. Use your tact and fairness.",
        "advice" => "Make decisions with confidence instead of overthinking."
    ],
    "Scorpio" => [
        "general" => "Scorpio, intense and passionate, you have great emotional depth and determination.",
        "today" => "Focus on personal transformation. Let go of grudges and embrace new beginnings.",
        "advice" => "Channel your energy into healing and constructive projects."
    ],
    "Sagittarius" => [
        "general" => "Sagittarius, adventurous and optimistic, you seek freedom and knowledge.",
        "today" => "A new opportunity for learning or travel may present itself. Stay open-minded.",
        "advice" => "Balance your desire for freedom with responsibilities."
    ],
    "Capricorn" => [
        "general" => "Capricorn, disciplined and ambitious, you are a natural strategist.",
        "today" => "Focus on long-term goals. Your perseverance will be rewarded.",
        "advice" => "Don’t neglect your personal life while chasing career success."
    ],
    "Aquarius" => [
        "general" => "Aquarius, innovative and humanitarian, you value individuality and progress.",
        "today" => "Collaborate on a group project or social cause. Your ideas will inspire others.",
        "advice" => "Stay grounded while thinking outside the box."
    ],
    "Pisces" => [
        "general" => "Pisces, empathetic and artistic, you are deeply intuitive and compassionate.",
        "today" => "Spend time on creative or spiritual pursuits. Your imagination is your strength.",
        "advice" => "Set healthy boundaries to avoid emotional burnout."
    ],
    "Unknown" => [
        "general" => "The stars are unclear, but trust your inner guidance.",
        "today" => "Be mindful and open to signs from the universe.",
        "advice" => "Take time for self-reflection."
    ]
];

$message = $horoscope_messages[$zodiac] ?? $horoscope_messages["Unknown"];

?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">

        </div>
        <div class="col-md-9">
    <h1 class="mb-4">Horoscope Report for <?= htmlspecialchars($user_name) ?></h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <strong>User Details</strong>
        </div>
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
        <small>Horoscopes are for entertainment and guidance purposes only. For a full personal reading, consult a professional astrologer.</small>
    </div>
</div>
</div>
</div>

<?php include '../includes/footer.php'; ?>
