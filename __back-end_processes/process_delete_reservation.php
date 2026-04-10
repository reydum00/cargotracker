<?php
session_start();
include '../__back-end_processes/db_connect.php';


if (!isset($_SESSION['account_id']) || $_SESSION['role'] != 2) {
    header("Location: ../_user_interface/user_signup.php");
    exit();
}


if (isset($_GET['reservation_id']) && !empty($_GET['reservation_id'])) {
    $reservation_id = intval($_GET['reservation_id']);
    
    // Delete the reservation
    $query = "DELETE FROM reservations WHERE reservation_id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("i", $reservation_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Reservation #$reservation_id has been deleted successfully.";
        } 
        
        $stmt->close();
    } else {
        $_SESSION['error'] = "Database error occurred.";
    }
} else {
    $_SESSION['error'] = "Invalid reservation ID.";
}

$conn->close();

// Redirect back to trips page
header("Location: ../_admin_interface/admin_trips.php");
exit();
?>