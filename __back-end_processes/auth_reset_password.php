<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token   = trim($_POST['token'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic checks
    if (empty($token) || empty($email) || empty($password) || empty($confirm_password)) {
        header("Location: ../_user_interface/user_reset_password.php?token=$token&email=" . urlencode($email) . "&error=All+fields+are+required.");
        exit();
    }

    // Password rules
    if (strlen($password) < 8 || strlen($password) > 16 || !preg_match('/[0-9]/', $password)) {
        header("Location: ../_user_interface/user_reset_password.php?token=$token&email=" . urlencode($email) . "&error=Password+does+not+meet+requirements.");
        exit();
    }

    // Passwords match
    if ($password !== $confirm_password) {
        header("Location: ../_user_interface/user_reset_password.php?token=$token&email=" . urlencode($email) . "&error=Passwords+do+not+match.");
        exit();
    }

    // Validate token — check it exists and hasn't expired
    $check = mysqli_query($conn, "SELECT * FROM password_resets WHERE token='$token' AND email='$email' LIMIT 1");

    if (!$check || mysqli_num_rows($check) === 0) {
        header("Location: ../_user_interface/user_forgot_password.php?error=Invalid+or+expired+reset+link.+Please+try+again.");
        exit();
    }

    $reset = mysqli_fetch_assoc($check);

    // Check expiry
    if (strtotime($reset['expires_at']) < time()) {
        mysqli_query($conn, "DELETE FROM password_resets WHERE token='$token'");
        header("Location: ../_user_interface/user_forgot_password.php?error=Reset+link+has+expired.+Please+request+a+new+one.");
        exit();
    }

    // Update the password
    $hash_pwd = password_hash($password, PASSWORD_DEFAULT);
    $update = mysqli_query($conn, "UPDATE account SET password='$hash_pwd' WHERE email='$email'");

    if ($update) {
        // Delete used token
        mysqli_query($conn, "DELETE FROM password_resets WHERE token='$token'");
        header("Location: ../_user_interface/user_signup.php?reset=success");
        exit();
    } else {
        header("Location: ../_user_interface/reset_password.php?token=$token&email=" . urlencode($email) . "&error=Something+went+wrong.+Please+try+again.");
        exit();
    }

} else {
    header("Location: ../_user_interface/forgot_password.php");
    exit();
}
?>