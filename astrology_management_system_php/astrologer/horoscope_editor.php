<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in and has the astrologer role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'astrologer') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$error = '';
$success = '';

// Fetch existing horoscope data for this astrologer (if any)
$stmt = $conn->prepare("SELECT * FROM horoscopes WHERE astrologer_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$horoscope = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $daily = trim($_POST['daily']);
    $weekly = trim($_POST['weekly']);
    $monthly = trim($_POST['monthly']);

    if (empty($daily) && empty($weekly) && empty($monthly)) {
        $error = "Please enter at least one horoscope (daily, weekly, or monthly).";
    } else {
        if ($horoscope) {
            // Update existing horoscope
            $stmt = $conn->prepare("UPDATE horoscopes SET daily = ?, weekly = ?, monthly = ?, updated_at = NOW() WHERE astrologer_id = ?");
            $stmt->bind_param("sssi", $daily, $weekly, $monthly, $user_id);
            if ($stmt->execute()) {
                $success = "Horoscope updated successfully.";
            } else {
                $error = "Error updating horoscope.";
            }
        } else {
            // Insert new horoscope
            $stmt = $conn->prepare("INSERT INTO horoscopes (astrologer_id, daily, weekly, monthly, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param("isss", $user_id, $daily, $weekly, $monthly);
            if ($stmt->execute()) {
                $success = "Horoscope saved successfully.";
            } else {
                $error = "Error saving horoscope.";
            }
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<style>
    body {
        background: #1a1a2e;
        color: #f0e6d2;
        font-family: 'Georgia', serif;
    }
    .card {
        background: rgba(30, 30, 60, 0.85);
        border-radius: 15px;
        border: 1px solid #f0e6d2;
    }
    .card-header {
        background: #6a0dad;
        font-weight: bold;
        font-size: 1.5rem;
        letter-spacing: 2px;
        color: #f0e6d2;
    }
    label {
        font-weight: bold;
        font-size: 1.1rem;
    }
    textarea.form-control {
        background:rgb(181, 181, 216);
        color: #f0e6d2;
        border: 1px solid #6a0dad;
        border-radius: 8px;
        font-family: 'Georgia', serif;
    }
    .btn-save {
        background-color: #8e44ad;
        border: none;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 8px;
        color: #f0e6d2;
        transition: background-color 0.3s ease;
    }
    .btn-save:hover {
        background-color: #a156d1;
        color: white;
    }
    .alert {
        border-radius: 8px;
        font-weight: bold;
        font-family: 'Georgia', serif;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm p-4">
                <div class="card-header mb-3">
                    ✨ Edit Your Horoscope ✨
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php elseif ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="daily">Daily Horoscope</label>
                        <textarea id="daily" name="daily" class="form-control" rows="4" placeholder="Write your daily horoscope here..."><?= htmlspecialchars($horoscope['daily'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="weekly">Weekly Horoscope</label>
                        <textarea id="weekly" name="weekly" class="form-control" rows="5" placeholder="Write your weekly horoscope here..."><?= htmlspecialchars($horoscope['weekly'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="monthly">Monthly Horoscope</label>
                        <textarea id="monthly" name="monthly" class="form-control" rows="6" placeholder="Write your monthly horoscope here..."><?= htmlspecialchars($horoscope['monthly'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-save">Save Horoscope</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
