<?php
include 'db_connect.php';

if (isset($_GET['p_id'])) {
    $id = $_GET['p_id'];

    $stmt = $conn->prepare("DELETE FROM psychologist WHERE p_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Psychologist removed successfully');</script>";
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
