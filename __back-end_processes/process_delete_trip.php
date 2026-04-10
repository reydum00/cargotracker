<?php
include 'db_connect.php';
session_start();

if (isset($_GET['trip_id'])) {
    $trip_id = $_GET['trip_id'];
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // 1. Get driver and vehicle names before deleting
        $get_trip = "SELECT driver, vehicle FROM trips WHERE trip_id = ?";
        $stmt_get = mysqli_prepare($conn, $get_trip);
        mysqli_stmt_bind_param($stmt_get, "i", $trip_id);
        mysqli_stmt_execute($stmt_get);
        $result = mysqli_stmt_get_result($stmt_get);
        $trip_data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt_get);
        
        if ($trip_data) {
            $driver_name = $trip_data['driver'];
            $vehicle_name = $trip_data['vehicle'];
            
            // 2. Delete the trip
            $query = "DELETE FROM trips WHERE trip_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $trip_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            
            // 3. Unassign driver (set is_assigned = 0)
            if (!empty($driver_name)) {
                $update_driver = "UPDATE account SET is_assigned = 0 WHERE fullname = ? AND role = 1";
                $stmt_driver = mysqli_prepare($conn, $update_driver);
                mysqli_stmt_bind_param($stmt_driver, "s", $driver_name);
                mysqli_stmt_execute($stmt_driver);
                mysqli_stmt_close($stmt_driver);
            }
            
            // 4. Unassign vehicle (set is_assigned = 0)
            if (!empty($vehicle_name)) {
                $update_vehicle = "UPDATE vehicles SET is_assigned = 0 WHERE vehicle_name = ?";
                $stmt_vehicle = mysqli_prepare($conn, $update_vehicle);
                mysqli_stmt_bind_param($stmt_vehicle, "s", $vehicle_name);
                mysqli_stmt_execute($stmt_vehicle);
                mysqli_stmt_close($stmt_vehicle);
            }
            
            // Commit transaction
            mysqli_commit($conn);
            $_SESSION['success'] = "Trip deleted successfully!";
            header("Location: ../_admin_interface/admin_trips.php?success=deleted");
        } else {
            mysqli_rollback($conn);
            $_SESSION['error'] = "Trip not found.";
            header("Location: ../_admin_interface/admin_trips.php?error=trip_not_found");
        }
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['error'] = "Failed to delete trip.";
        header("Location: ../_admin_interface/admin_trips.php?error=delete_failed");
    }
    
    exit();
}

mysqli_close($conn);
?>