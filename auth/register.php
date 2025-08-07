<?php
session_start();
require_once '../config/db.php';

$error = '';
$success = '';
$admin_exists = false;

// Check if an admin already exists
$admin_check = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'admin'");
if ($admin_check) {
    $row = $admin_check->fetch_assoc();
    if ($row['total'] > 0) {
        $admin_exists = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];
    $role     = $_POST['role'];

    $valid_roles = ['admin', 'user', 'astrologer'];

    if ($name && $email && $password && $confirm && $role) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } elseif (!in_array($role, $valid_roles)) {
            $error = "Invalid role selected.";
        } elseif ($password !== $confirm) {
            $error = "Passwords do not match.";
        } elseif ($role === 'admin' && $admin_exists) {
            $error = "An admin account already exists. Please register as a user or astrologer.";
        } else {
            $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            $res = $check->get_result();

            if ($res->num_rows > 0) {
                $error = "Email already registered.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
                $stmt->bind_param("ssss", $name, $email, $hash, $role);
                if ($stmt->execute()) {
                    $success = "Registered successfully. Please <a href='login.php'>login</a>.";
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@400;600&display=swap');

    body {
        background: radial-gradient(ellipse at center, #1a1a2e, #16213e 70%, #0f3460);
        font-family: 'Montserrat', sans-serif;
        color: #e0e0e0;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px 15px;
        margin: 0;
    }

    .register-container {
        background: rgba(25, 25, 50, 0.85);
        border-radius: 15px;
        box-shadow: 0 0 30px 5px rgba(138, 43, 226, 0.8);
        padding: 40px 30px;
        width: 100%;
        max-width: 420px;
        animation: glow 3s ease-in-out infinite alternate;
    }

    @keyframes glow {
        from {
            box-shadow: 0 0 15px 3px rgba(138, 43, 226, 0.7);
        }
        to {
            box-shadow: 0 0 30px 10px rgba(138, 43, 226, 1);
        }
    }

    .card-header {
        font-family: 'Great Vibes', cursive;
        font-size: 2.8rem;
        text-align: center;
        color: #e0c3fc;
        margin-bottom: 25px;
        letter-spacing: 3px;
        text-shadow: 0 0 8px #9b59b6;
    }

    form label {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 6px;
        display: block;
        color: #d3c0f9;
        text-shadow: 0 0 3px #9b59b6;
    }

    input.form-control, select.form-select {
        background-color: #1a1a2e;
        border: 1.5px solid #6a0dad;
        border-radius: 8px;
        color: #e0e0e0;
        padding: 10px 14px;
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        width: 100%;
    }

    input.form-control::placeholder {
        color: #a893c8;
    }

    input.form-control:focus, select.form-select:focus {
        outline: none;
        border-color: #9b59b6;
        box-shadow: 0 0 10px 2px #9b59b6;
        background-color: #2b2b50;
        color: #fff;
    }

    .btn-success {
        background: linear-gradient(90deg, #9b59b6 0%, #8e44ad 100%);
        border: none;
        font-weight: 700;
        padding: 12px;
        font-size: 1.2rem;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.4s ease;
        color: #fff;
        width: 100%;
        box-shadow: 0 4px 15px rgba(155, 89, 182, 0.7);
        margin-top: 15px;
    }

    .btn-success:hover {
        background: linear-gradient(90deg, #8e44ad 0%, #9b59b6 100%);
        box-shadow: 0 6px 20px rgba(155, 89, 182, 0.9);
    }

    .alert-danger {
        background-color: #ff4e50;
        color: #fff;
        padding: 10px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 600;
        box-shadow: 0 0 8px #ff4e50;
    }

    .alert-success {
        background-color: #27ae60;
        color: #fff;
        padding: 10px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 600;
        box-shadow: 0 0 8px #27ae60;
    }

    .card-footer {
        margin-top: 20px;
        text-align: center;
        font-size: 0.95rem;
        color: #d3c0f9;
    }

    .card-footer a {
        color: #9b59b6;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .card-footer a:hover {
        color: #e0c3fc;
        text-decoration: underline;
    }

    .forgot-link {
        display: block;
        margin-top: 8px;
        text-align: right;
        font-size: 0.9rem;
        color: #bfa8f9;
    }

    .forgot-link a {
        color: #bfa8f9;
    }

    .forgot-link a:hover {
        color: #e0c3fc;
        text-decoration: underline;
    }
</style>

<div class="register-container">
    <div class="card-header">✨ Astrological Site Registration ✨</div>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <label for="name">Full Name</label>
        <input type="text" name="name" class="form-control" placeholder="Enter your full name" required/>

        <label for="email">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="Enter your email" required/>

        <label for="password">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter a password" required/>

        <label for="confirm">Confirm Password</label>
        <input type="password" name="confirm" class="form-control" placeholder="Confirm your password" required/>

        <label for="role">Select Role</label>
        <select name="role" class="form-select" required>
            <option value="">-- Choose Role --</option>
            <option value="user">User</option>
            <option value="astrologer">Astrologer</option>
            <?php if (!$admin_exists): ?>
                <option value="admin">Admin</option>
            <?php endif; ?>
        </select>

        <button type="submit" class="btn-success">Register</button>
    </form>

    <div class="card-footer">
        Already registered? <a href="login.php">Login here</a><br>
        <span class="forgot-link"><a href="forgot_password.php">Forgot Password?</a></span>
    </div>
</div>
