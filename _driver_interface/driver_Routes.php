<?php
include '../__back-end_processes/db_connect.php';
session_start();

// If not logged in OR not driver, redirect away
if (!isset($_SESSION['account_id']) || $_SESSION['role'] != 1) {
    header("Location: ../_user_interface/user_signup.php");
    exit();
}

$logged_in_username = null;
if (isset($_SESSION['account_id'])) {
    $account_id = $_SESSION['account_id'];
    $query = "SELECT username FROM account WHERE account_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $account_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $logged_in_username = $row['username'];
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#0047ab]">
    <div class="side-nav flex flex-col space-y-10 gap-y-4 text-[18px]">
        <button class="hover:bg-gray-400 rounded-md" onclick="window.location.href='driver_home.php'">Dashboard</button>
        <button class="hover:bg-gray-400 rounded-md" onclick="window.location.href='driver_Announcement.php'">Announcement</button>
        <button class="hover:bg-gray-400 rounded-md" onclick="window.location.href='driver_Delivery.php'">Delivery</button>
        <button class="hover:bg-gray-400 rounded-md" onclick="window.location.href='driver_Assigned_Job.php'">Assigned Job</button>
        <button class="hover:bg-gray-400 rounded-md" onclick="window.location.href='#'">Weekly Records</button>
        <button class="hover:bg-gray-400 rounded-md" onclick="window.location.href='#'">Logs</button>
        <!-- <button class="hover:bg-gray-400 rounded-md" onclick="window.location.href='#'">Profile</button> -->
        <button class="bg-red-700 w-40 mx-auto text-white rounded-md">Log out ⍈</button>
    </div>

    <div>
        hello
    </div>




</body>

</html>