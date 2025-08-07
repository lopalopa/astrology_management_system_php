<?php
include '../includes/auth.php';
include '../includes/header.php';
require '../config/db.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <?php include '../includes/sidebar.php'; ?>
        </div>
        <div class="col-md-9">
            <h2>All Appointments</h2>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Astrologer</th>
                        <th>Appointment Date</th>
                        <th>Message</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Correct SQL: join users for both user and astrologer
                    $query = "
                        SELECT 
                            a.id, 
                            u.name AS user_name, 
                            au.name AS astrologer_name, 
                            a.appointment_date, 
                            a.message, 
                            a.status
                        FROM appointments a
                        JOIN users u ON a.user_id = u.id
                        JOIN astrologers ast ON a.astrologer_id = ast.id
                        JOIN users au ON ast.user_id = au.id
                        ORDER BY a.appointment_date DESC
                    ";
                    $result = $conn->query($query);
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>" . htmlspecialchars($row['id']) . "</td>
                                <td>" . htmlspecialchars($row['user_name']) . "</td>
                                <td>" . htmlspecialchars($row['astrologer_name']) . "</td>
                                <td>" . htmlspecialchars($row['appointment_date']) . "</td>
                                <td>" . nl2br(htmlspecialchars($row['message'])) . "</td>
                                <td>" . htmlspecialchars(ucfirst($row['status'])) . "</td>
                            </tr>";
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center">No appointments found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
