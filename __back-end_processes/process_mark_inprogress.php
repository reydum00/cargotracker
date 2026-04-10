<?php
session_start();
include 'db_connect.php';

// Check if driver is logged in
if (!isset($_SESSION['account_id']) || $_SESSION['role'] != 1) {
    header("Location: ../_user_interface/user_signup.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trip_id = $_POST['trip_id'];

    // Update trip status to "in-progress"
    // No need to update is_assigned since it's already set to 1 when trip was created
    $stmt = $conn->prepare("UPDATE trips SET status = 'in-progress' WHERE trip_id = ?");
    $stmt->bind_param("i", $trip_id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../_driver_interface/driver_Assigned_Job.php?success=marked_inprogress");
    } else {
        $stmt->close();
        $conn->close();
        header("Location: ../_driver_interface/driver_Assigned_Job.php?error=update_failed");
    }
    
    exit();
}
?>