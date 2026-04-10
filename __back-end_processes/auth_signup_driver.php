<?php
include 'db_connect.php';

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $F_name = $_POST['fullname'] ?? '';
    $name = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Password validation
    if (strlen($password) < 8 || strlen($password) > 16 || !preg_match('/[0-9]/', $password)) {
        header("Location: ../user_signup.php?error=invalid_password");
        exit();
    }

    $hash_pwd = password_hash($password, PASSWORD_DEFAULT);
    $role = 1;

    // sql
    $insert = "insert into account (fullname, username, email, password, role)
    values ('$F_name','$name', '$email','$hash_pwd', '$role')";

    $query = mysqli_query($conn, $insert);
   
    header("Location: ../_admin_interface/admin_accounts.php");
    
    
}




?>