<?php
session_start();
include 'db_connect.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Step 1: Fetch user by email
    $retreive_account = "SELECT * FROM account WHERE email='$email' LIMIT 1";
    $query = mysqli_query($conn, $retreive_account);

    if ($query && mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);

        // Step 2: Verify password
        if (password_verify($password, $user['password'])) {

            // ✅ Store session data    
            $_SESSION['account_id'] = $user['account_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['is_new_client'] = $user['is_new_client']; // ✅ ADD THIS LINE

            // Step 3: Redirect based on role
            if ($user['role'] == 2) {
                header("Location: ../_admin_interface/admin.php");
                exit();
            } elseif ($user['role'] == 1) {
                header("Location: ../_driver_interface/driver_home.php");
                exit();
            } else {
                header("Location: ../_user_interface/user_index.php");
                exit();
            }
        } else {
            // X Wrong password
            $_SESSION['error'] = "Please check your credentials.";
            header("Location: ../_user_interface/user_signup.php");
            exit();
        }
    } else {
        // X Email not found
        $_SESSION['error'] = "Please check your credentials.";
        header("Location: ../_user_interface/user_signup.php");
        exit();
    }
}