<?php


// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cargotracker";

// Create connection using MySQLi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}