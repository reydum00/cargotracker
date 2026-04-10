<?php
session_start();
include 'db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $T_Announcement = $_POST['announcement_title'] ?? '';
    $P_Level = $_POST['priority_lvl'] ?? '';  
    $M_announcement = $_POST['announcement_msg'] ?? '';

    // Insert Announcement sa DB
    $Insert_DB = "INSERT INTO announcements (title, priority_level, announcement_message)
    VALUES ('$T_Announcement','$P_Level','$M_announcement')";

    // Run query command
    $query = mysqli_query($conn, $Insert_DB);

    if($query){
    header("Location: ../_admin_interface/admin_announcement.php");
    exit();
    }
}


?>