<?php
session_start();
include 'db_connect.php'; 

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get the announcement_id from POST data
    $announcement_id = $_POST['announcement_id'];
    
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE announcements SET deleted_at = NOW() WHERE announcement_id = ?");
    $stmt->bind_param("i", $announcement_id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../_admin_interface/admin_announcement.php?success=archived");
    } else {
        $stmt->close();
        $conn->close();
        header("Location: ../_admin_interface/admin_announcement.php?error=archive_failed");
    }
    exit();
}
?>