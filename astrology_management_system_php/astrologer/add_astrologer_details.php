<?php
session_start();
require_once '../config/db.php';

// Ensure astrologer is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'astrologer') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$error = '';
$success = '';

// Fetch existing astrologer profile if any
$stmt_check = $conn->prepare("SELECT * FROM astrologers WHERE user_id = ?");
$stmt_check->bind_param("i", $user_id);
$stmt_check->execute();
$result = $stmt_check->get_result();
$astrologer_data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = trim($_POST['bio']);
    $expertise = trim($_POST['expertise']);

    // Handle file upload
    $profile_image = $astrologer_data['profile_image'] ?? null;
    if (!empty($_FILES['profile_image']['name'])) {
        $image_name = time() . '_' . basename($_FILES['profile_image']['name']);
        $target_dir = "../uploads/";
        $target_file = $target_dir . $image_name;

        // Check file type
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_type, $allowed)) {
            $error = "Only JPG, PNG, and GIF files are allowed.";
        } elseif (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            $error = "Failed to upload image.";
        } else {
            $profile_image = $image_name;
        }
    }

    if (!$error) {
        if ($astrologer_data) {
            // Update
            $stmt = $conn->prepare("UPDATE astrologers SET bio = ?, expertise = ?, profile_image = ?, updated_at = NOW() WHERE user_id = ?");
            $stmt->bind_param("sssi", $bio, $expertise, $profile_image, $user_id);
            if ($stmt->execute()) {
                $success = "Profile updated successfully.";
            } else {
                $error = "Error updating profile.";
            }
        } else {
            // Insert
            $stmt = $conn->prepare("INSERT INTO astrologers (user_id, bio, expertise, profile_image, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param("isss", $user_id, $bio, $expertise, $profile_image);
            if ($stmt->execute()) {
                $success = "Profile saved successfully.";
            } else {
                $error = "Error saving profile.";
            }
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<style>
  body {
    background: radial-gradient(circle at top left, #2c003e, #0a0a0a);
    color: #f0e6f6;
    font-family: 'Poppins', sans-serif;
  }
  .profile-container {
    max-width: 600px;
    margin: 3rem auto 5rem;
    background: rgba(44, 0, 62, 0.9);
    border-radius: 15px;
    padding: 2rem 2.5rem;
    box-shadow: 0 0 15px 3px #a86eff99;
  }
  h3 {
    text-align: center;
    margin-bottom: 1.5rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    color: #d1a7ff;
    text-shadow: 0 0 8px #b16effbb;
  }
  label {
    font-weight: 600;
    color: #b9a1ff;
  }
  input[type="text"],
  textarea,
  input[type="file"] {
    width: 100%;
    background: #3b1a5a;
    border: none;
    border-radius: 8px;
    padding: 0.7rem 1rem;
    color: #eee;
    font-size: 1rem;
    box-shadow: inset 0 0 6px #a27bff88;
    transition: box-shadow 0.3s ease;
  }
  input[type="text"]:focus,
  textarea:focus,
  input[type="file"]:focus {
    outline: none;
    box-shadow: 0 0 10px 2px #b87dffcc;
    background: #4e2a7a;
  }
  textarea {
    resize: vertical;
  }
  .btn-primary {
    width: 100%;
    padding: 0.75rem;
    font-weight: 700;
    font-size: 1.1rem;
    background: linear-gradient(45deg, #af7fff, #6d2ce1);
    border: none;
    border-radius: 50px;
    cursor: pointer;
    color: #fff;
    box-shadow: 0 0 15px #a86effcc;
    transition: background 0.3s ease, box-shadow 0.3s ease;
  }
  .btn-primary:hover {
    background: linear-gradient(45deg, #c28fff, #8433f5);
    box-shadow: 0 0 20px #c28fffdd;
  }
  .alert {
    border-radius: 10px;
    padding: 0.75rem 1rem;
    margin-bottom: 1.25rem;
    font-weight: 600;
    text-align: center;
    text-shadow: 0 0 4px #00000088;
  }
  .alert-danger {
    background: #b33737;
    color: #fff;
  }
  .alert-success {
    background: #3bb273;
    color: #fff;
  }
  img.profile-preview {
    display: block;
    margin: 1rem auto 0;
    border-radius: 12px;
    box-shadow: 0 0 15px #a86effcc;
    max-width: 140px;
    max-height: 140px;
    object-fit: cover;
    border: 3px solid #a87fff;
  }
</style>

<div class="profile-container">
    <h3>Astrologer Profile</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" novalidate>
        <div class="mb-3">
            <label for="expertise">Expertise</label>
            <input
                type="text"
                name="expertise"
                id="expertise"
                value="<?= htmlspecialchars($astrologer_data['expertise'] ?? '') ?>"
                placeholder="E.g., Vedic Astrology, Numerology..."
                required
            >
        </div>

        <div class="mb-3">
            <label for="bio">Bio</label>
            <textarea
                name="bio"
                id="bio"
                rows="5"
                placeholder="Tell us about yourself..."
                required><?= htmlspecialchars($astrologer_data['bio'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label for="profile_image">Profile Image</label>
            <input type="file" name="profile_image" id="profile_image" accept=".jpg,.jpeg,.png,.gif">
            <?php if (!empty($astrologer_data['profile_image'])): ?>
                <img
                    src="../uploads/<?= htmlspecialchars($astrologer_data['profile_image']) ?>"
                    alt="Profile Image"
                    class="profile-preview"
                >
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-primary">Save Profile</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
