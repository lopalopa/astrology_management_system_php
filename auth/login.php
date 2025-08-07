<?php
session_start();
require_once '../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email && $password) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;

                switch ($user['role']) {
                    case 'admin':
                        header("Location: ../admin/dashboard.php");
                        break;
                    case 'astrologer':
                        header("Location: ../astrologer/dashboard.php");
                        break;
                    default:
                        header("Location: ../user/dashboard.php");
                        break;
                }
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Email not registered.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<!-- Custom Styling -->
<style>
    body {
        background: linear-gradient(to right, #1c1c3c, #3b0a54);
        font-family: 'Segoe UI', sans-serif;
        color: #fff;
    }
    .login-card {
        max-width: 400px;
        margin: 60px auto;
        background: #2e2e4d;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
    }
    .login-card h3 {
        text-align: center;
        margin-bottom: 25px;
        color: #FFD700;
    }
    .form-label {
        color: #ddd;
    }
    .btn-primary {
        background-color: #FFD700;
        border: none;
        color: #000;
        width: 100%;
    }
    .btn-primary:hover {
        background-color: #e6c200;
    }
    .btn-link, .forgot-link {
        display: block;
        text-align: center;
        color: #FFD700;
        margin-top: 10px;
        text-decoration: none;
    }
    .btn-link:hover, .forgot-link:hover {
        text-decoration: underline;
    }
    .alert {
        text-align: center;
    }
</style>

<div class="login-card">
    <h3>🔮 Login to Your Astrology Portal</h3>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required/>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter your password" required/>
        </div>
        <button type="submit" class="btn btn-primary">✨ Login</button>
        <a href="forgot_password.php" class="forgot-link">Forgot Password?</a>
        <a href="register.php" class="btn-link">Don’t have an account? Sign up</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
