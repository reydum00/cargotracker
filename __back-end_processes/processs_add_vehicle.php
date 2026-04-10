<?php
session_start();

include 'db_connect.php'; 



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $V_name = $_POST['vehicle_name'] ?? '';
    $V_type = $_POST['vehicle_type'] ?? '';


    $Insert_DB = "INSERT INTO vehicles (vehicle_name, vehicle_type)
    VALUES('$V_name','$V_type')";

    $query = mysqli_query($conn, $Insert_DB);

        if($query){
        header("Location: ../_admin_interface/admin_vehicles.php");
        exit();
        }

    
    exit();
    
}
?>