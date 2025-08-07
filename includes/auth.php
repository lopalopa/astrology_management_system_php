<?php
// includes/auth.php
session_start();

if (!isset($_SESSION['user'])) {
    // Not logged in
    header("Location: /auth/login.php");
    exit();
}

// Optional: Role check
function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

function isAstrologer() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'astrologer';
}

function isUser() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'user';
}
?>
