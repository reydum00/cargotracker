<?php
include '../__back-end_processes/db_connect.php';
session_start();

$Driver = $_POST['assigned_Driver'] ?? '';
$Vehicle = $_POST['assigned_Vehicle'] ?? '';
$Client = $_POST['Client'] ?? '';
$Destination = $_POST['delivery_Destination'] ?? '';
$Trip_Type = $_POST['tripType'] ?? '';
$Contact_Num = $_POST['contact_number'] ?? '';    
$Contact_Email = $_POST['email_address'] ?? '';   

// Start transaction
mysqli_begin_transaction($conn);

try {
    // 1. Insert the trip
    $Insert_DB = "INSERT INTO trips (driver, vehicle, client, destination, trip_type, contact_number, email_address)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($Insert_DB);
   $stmt->bind_param("sssssss", $Driver, $Vehicle, $Client, $Destination, $Trip_Type, $Contact_Num, $Contact_Email);
    $stmt->execute();
    $stmt->close();

    // 2. Set driver as assigned (is_assigned = 1)
    if (!empty($Driver)) {
        $update_driver = "UPDATE account SET is_assigned = 1 WHERE fullname = ? AND role = 1";
        $stmt_driver = $conn->prepare($update_driver);
        $stmt_driver->bind_param("s", $Driver);
        $stmt_driver->execute();
        $stmt_driver->close();
    }

    // 3. Set vehicle as assigned (is_assigned = 1)
    if (!empty($Vehicle)) {
        $update_vehicle = "UPDATE vehicles SET is_assigned = 1 WHERE vehicle_name = ?";
        $stmt_vehicle = $conn->prepare($update_vehicle);
        $stmt_vehicle->bind_param("s", $Vehicle);
        $stmt_vehicle->execute();
        $stmt_vehicle->close();
    }

    // Commit transaction
    mysqli_commit($conn);
    
    $_SESSION['success'] = "Trip created successfully!";
    header("Location: ../_admin_interface/admin_trips.php");
    exit();

} catch (Exception $e) {
    // Rollback on error
    mysqli_rollback($conn);
    $_SESSION['error'] = "Failed to create trip.";
    header("Location: ../_admin_interface/admin_trips.php");
    exit();
}

$conn->close();
?>