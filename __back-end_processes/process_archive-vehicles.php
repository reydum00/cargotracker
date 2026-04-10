<?php
session_start();
include 'db_connect.php'; 

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get the vehicle_name from POST data
    $vehicle_name = $_POST['vehicle_name'];
    
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE vehicles SET is_archived = 1 WHERE vehicle_name = ?");
    $stmt->bind_param("s", $vehicle_name);
    
    
    
   
    $conn->close();
    
    // Redirect back to the vehicles page
    header("Location: ../_admin_interface/admin_vehicles.php");
    exit();
}
?>