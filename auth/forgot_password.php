<?php
session_start();
require_once '../config/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if ($email) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            // Normally, generate a token and send email with reset link
            // For now, just simulate success
            $success = "A password reset link has been sent to your email (simulated).";
        } else {
            $error = "Email address not found.";
        }
    } else {
        $error = "Please enter your email address.";
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<style>
    body {
        background: linear-gradient(to right, #1c1c3c, #3b0a54);
        font-family: 'Segoe UI', sans-serif;
        color: #fff;
    }
    .reset-card {
        max-width: 420px;
        margin: 80px auto;
        background: #2e2e4d;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
    }
    .reset-card h3 {
        text-align: center;
        margin-bottom: 25px;
        color: #FFD700;
    }
    .form-label {
        color: #ddd;
    }
    .btn-warning {
        background-color: #FFD700;
        border: none;
        color: #000;
        width: 100%;
    }
    .btn-warning:hover {
        background-color: #e6c200;
    }
    .alert {
        text-align: center;
    }
</style>

<div class="reset-card">
    <h3>🔐 Forgot Password</h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Enter your registered email</label>
            <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
        </div>
        <button type="submit" class="btn btn-warning">Send Reset Link</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
