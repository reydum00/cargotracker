<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trip_id = $_POST['trip_id'];
    $new_driver = $_POST['driver'];
    $new_vehicle = $_POST['vehicle'];
    $client = $_POST['client'];
    $destination = $_POST['destination'];
    $trip_type = $_POST['trip_type'];

    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // 1. Get OLD driver and vehicle names before updating
        $get_old = "SELECT driver, vehicle FROM trips WHERE trip_id = ?";
        $stmt_old = mysqli_prepare($conn, $get_old);
        mysqli_stmt_bind_param($stmt_old, "i", $trip_id);
        mysqli_stmt_execute($stmt_old);
        $result_old = mysqli_stmt_get_result($stmt_old);
        $old_data = mysqli_fetch_assoc($result_old);
        mysqli_stmt_close($stmt_old);
        
        $old_driver = $old_data['driver'];
        $old_vehicle = $old_data['vehicle'];
        
        // 2. Update the trip
        $query = "UPDATE trips SET 
                  driver = ?, 
                  vehicle = ?, 
                  client = ?, 
                  destination = ?, 
                  trip_type = ?
                  WHERE trip_id = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssssi", $new_driver, $new_vehicle, $client, $destination, $trip_type, $trip_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        // 3. If driver changed, unassign old driver and assign new driver
        if ($old_driver !== $new_driver) {
            // Unassign old driver (set to 0)
            if (!empty($old_driver)) {
                $unassign_old_driver = "UPDATE account SET is_assigned = 0 WHERE fullname = ? AND role = 1";
                $stmt_unassign_driver = mysqli_prepare($conn, $unassign_old_driver);
                mysqli_stmt_bind_param($stmt_unassign_driver, "s", $old_driver);
                mysqli_stmt_execute($stmt_unassign_driver);
                mysqli_stmt_close($stmt_unassign_driver);
            }
            
            // Assign new driver (set to 1)
            if (!empty($new_driver)) {
                $assign_new_driver = "UPDATE account SET is_assigned = 1 WHERE fullname = ? AND role = 1";
                $stmt_assign_driver = mysqli_prepare($conn, $assign_new_driver);
                mysqli_stmt_bind_param($stmt_assign_driver, "s", $new_driver);
                mysqli_stmt_execute($stmt_assign_driver);
                mysqli_stmt_close($stmt_assign_driver);
            }
        }
        
        // 4. If vehicle changed, unassign old vehicle and assign new vehicle
        if ($old_vehicle !== $new_vehicle) {
            // Unassign old vehicle (set to 0)
            if (!empty($old_vehicle)) {
                $unassign_old_vehicle = "UPDATE vehicles SET is_assigned = 0 WHERE vehicle_name = ?";
                $stmt_unassign_vehicle = mysqli_prepare($conn, $unassign_old_vehicle);
                mysqli_stmt_bind_param($stmt_unassign_vehicle, "s", $old_vehicle);
                mysqli_stmt_execute($stmt_unassign_vehicle);
                mysqli_stmt_close($stmt_unassign_vehicle);
            }
            
            // Assign new vehicle (set to 1)
            if (!empty($new_vehicle)) {
                $assign_new_vehicle = "UPDATE vehicles SET is_assigned = 1 WHERE vehicle_name = ?";
                $stmt_assign_vehicle = mysqli_prepare($conn, $assign_new_vehicle);
                mysqli_stmt_bind_param($stmt_assign_vehicle, "s", $new_vehicle);
                mysqli_stmt_execute($stmt_assign_vehicle);
                mysqli_stmt_close($stmt_assign_vehicle);
            }
        }
        
        // Commit transaction
        mysqli_commit($conn);
        header("Location: ../_admin_interface/admin_trips.php?success=updated");
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        header("Location: ../_admin_interface/admin_trips.php?error=update_failed");
    }
    
    exit();
}

mysqli_close($conn);
?>