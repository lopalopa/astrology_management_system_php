<?php
include '../includes/auth.php';
include '../includes/header.php';
require '../config/db.php';

// Get user_id of logged-in astrologer
$logged_in_user_id = $_SESSION['user']['id'];

// Get astrologer's ID from the astrologers table
$astrologer_id = null;
$stmt = $conn->prepare("SELECT id FROM astrologers WHERE user_id = ?");
$stmt->bind_param("i", $logged_in_user_id);
$stmt->execute();
$stmt->bind_result($astrologer_id);
$stmt->fetch();
$stmt->close();

if (!$astrologer_id) {
    echo "<div class='alert alert-danger'>No astrologer profile found for this user.</div>";
    include '../includes/footer.php';
    exit;
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
<?php include '../includes/sidebar.php'; ?>

        </div>
        <div class="col-md-9">
    <h2>View Appointments</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>User Name</th>
                <th>Date</th>
                <th>Message</th>
                <th>Action</th> <!-- New column -->
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->prepare("
                SELECT a.id, u.id AS user_id, u.name, a.appointment_date, a.message 
                FROM appointments a 
                JOIN users u ON a.user_id = u.id 
                WHERE a.astrologer_id = ?
            ");
            $stmt->bind_param("i", $astrologer_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($appointment = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$appointment['id']}</td>
                        <td>{$appointment['name']}</td>
                        <td>{$appointment['appointment_date']}</td>
                        <td>{$appointment['message']}</td>
                        <td>
                            <a href='generate_horoscope.php?user_id={$appointment['user_id']}' class='btn btn-sm btn-primary'>
                                Generate Horoscope
                            </a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</div>
</div>

<?php include '../includes/footer.php'; ?>
