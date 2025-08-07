<?php
include 'includes/navbar.php';
?>

<div class="container mt-5">
    <h1>Welcome to Astrology Management System</h1>
    <p>Your trusted platform for astrology services including horoscope readings, astrologer bookings, and personalized astrology reports.</p>
    <hr>
    <h3>Get Started</h3>
    <p>
        <a href="auth/login.php" class="btn btn-primary">Login</a>
        <a href="auth/register.php" class="btn btn-secondary">Register</a>
    </p>

    <div class="row mt-4">
        <div class="col-md-6">
            <h4>Explore Zodiac Signs</h4>
            <p>Discover your zodiac sign's traits, dates, and horoscope.</p>
            <a href="zodiac/view_zodiac.php" class="btn btn-info">View Zodiac Signs</a>
        </div>
        <div class="col-md-6">
            <h4>Book Astrologer</h4>
            <p>Schedule a session with a professional astrologer.</p>
            <a href="auth/login.php" class="btn btn-warning">Book Now</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
