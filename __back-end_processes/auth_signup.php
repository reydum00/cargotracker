<?php
include 'db_connect.php';

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $fname = $_POST['fname'] ?? '';
    $cname = $_POST['cname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $hash_pwd = password_hash($password, PASSWORD_DEFAULT);
    $contact_num = $_POST['contact_num'] ?? '';
    $role = 0;
    $is_new_client = 1;


    $check_query = "SELECT email FROM account WHERE email = '$email'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        header("Location: ../_user_interface/user_signup.php?error=email_taken");
        exit();
    }
    
    //sql

    $insert = "insert into account (username, fullname, company_name, email, password, contact_num, role, is_new_client)
    values ('$name', '$fname', '$cname','$email','$hash_pwd', '$contact_num', '$role', '$is_new_client')";

    $query = mysqli_query($conn, $insert);

    header("Location: ../_user_interface/user_signup.php");
     
}



?>