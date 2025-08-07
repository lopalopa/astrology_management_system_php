<?php
include 'includes/header.php';
include 'includes/navbar.php';

require 'config/db.php';

$name = $email = $message = '';
$name_err = $email_err = $message_err = $success_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate Name
    if (empty(trim($_POST['name']))) {
        $name_err = 'Please enter your name.';
    } else {
        $name = trim($_POST['name']);
    }

    // Validate Email
    if (empty(trim($_POST['email']))) {
        $email_err = 'Please enter your email.';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email_err = 'Please enter a valid email.';
    } else {
        $email = trim($_POST['email']);
    }

    // Validate Message
    if (empty(trim($_POST['message']))) {
        $message_err = 'Please enter your message.';
    } else {
        $message = trim($_POST['message']);
    }

    // If no errors, save to DB or send email
    if (empty($name_err) && empty($email_err) && empty($message_err)) {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            $success_msg = "Thank you for contacting us. We'll get back to you soon.";
            // Clear inputs
            $name = $email = $message = '';
        } else {
            echo "<div class='alert alert-danger'>Error submitting the form. Please try again.</div>";
        }
        $stmt->close();
    }
}
?>

<div class="container mt-5">
    <h1>Contact Us</h1>

    <?php if ($success_msg): ?>
        <div class="alert alert-success"><?= $success_msg ?></div>
    <?php endif; ?>

    <form action="contact.php" method="POST" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control <?= $name_err ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= htmlspecialchars($name) ?>">
            <div class="invalid-feedback"><?= $name_err ?></div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control <?= $email_err ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
            <div class="invalid-feedback"><?= $email_err ?></div>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control <?= $message_err ? 'is-invalid' : '' ?>" id="message" name="message" rows="5"><?= htmlspecialchars($message) ?></textarea>
            <div class="invalid-feedback"><?= $message_err ?></div>
        </div>

        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
