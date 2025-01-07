<?php
session_start();
include 'db_connect.php';

// Ensure the user is logged in as a psychologist
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT p_id, profile_picture FROM psychologist WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($psychologist_id, $profile_picture);
$stmt->fetch();
$stmt->close();

if (!$psychologist_id) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Fetch appointments for the psychologist
$appointments = $conn->query("
    SELECT a.*, u.username AS user_name 
    FROM appointments a 
    JOIN users u ON a.user_id = u.user_id
    WHERE a.p_id = '$psychologist_id' 
");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Psychologist Dashboard</title>
    <link rel="stylesheet" href="psychologist_dashboard.css">
</head>

<body>
    <div class="dashboard-container">
        <header>
            <div class="header-content">
                <h1>Psychologist Dashboard</h1>
                <div class="profile-links">
                    <a href="manage_psychologist_profile.php">Manage Profile</a>
                    <a href="logout.php" class="logout-link">Logout</a>
                    <div class="profile-picture">
                        <?php if (!empty($profile_picture)): ?>
                            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
                        <?php else: ?>
                            <img src="images/default-profile.jpg" alt="Default Profile Picture">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>

        <div class="welcome-message">
            <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        </div>

        <div class="appointments">
            <h2>Your Appointments</h2>
            <table>
                <thead>
                    <tr>
                        <th>User's Name</th>
                        <th>Appointment Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($appointment = $appointments->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($appointment['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                            <td>
                                <!-- Form to update status -->
                                <form action="update_status.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="appointment_id" value="<?php echo $appointment['appointment_id']; ?>">
                                    <select name="status">
                                        <option value="pending" <?php echo $appointment['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="approved" <?php echo $appointment['status'] == 'approved' ? 'selected' : ''; ?>>Approved</option>
                                        <option value="rejected" <?php echo $appointment['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                    </select>
                                    <button type="submit" class="update-button">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>