<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in and has the astrologer role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'astrologer') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch user details from session
$user = $_SESSION['user'];
?>

<?php include '../includes/header.php'; ?>

<style>
    /* Astrological theme styling */
    body {
        background: linear-gradient(135deg, #2c3e50, #3498db);
        color: #f0e6d2;
        font-family: 'Georgia', serif;
    }
    .card {
        background: rgba(210, 199, 199, 0.6);
        border-radius: 15px;
        border: 1px solid #f0e6d2;
    }
    .card-header {
        background:rgb(233, 225, 237);
        font-weight: bold;
        font-size: 1.5rem;
        letter-spacing: 2px;
    }
    .card-body p {
        font-size: 1.1rem;
        margin-bottom: 0.8rem;
    }
    .btn-logout {
        background: #c0392b;
        border: none;
        transition: background-color 0.3s ease;
    }
    .btn-logout:hover {
        background: #e74c3c;
        color: white;
    }
    .astro-icon {
        font-size: 3rem;
        color: #f39c12;
        margin-right: 10px;
        vertical-align: middle;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm p-4">
                <div class="card-header d-flex align-items-center">
                    <span class="astro-icon">🔮</span> Astrologer Profile
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                    <p><strong>Role:</strong> <?= htmlspecialchars(ucfirst($user['role'])) ?></p>
                    <p><strong>Joined On:</strong> <?= date('F j, Y', strtotime($user['created_at'])) ?></p>
                </div>
                <div class="card-footer text-end">
                    <a href="../auth/logout.php" class="btn btn-logout">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
