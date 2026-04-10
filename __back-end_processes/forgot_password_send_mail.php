<?php
session_start();
include 'db_connect.php';
require __DIR__ . '/../PHPMailer-master/src/Exception.php';
require __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        header("Location: ../_user_interface/forgot_password.php?error=Please+enter+your+email.");
        exit();
    }

    // Check if email exists in account table
    $check = mysqli_query($conn, "SELECT * FROM account WHERE email='$email' LIMIT 1");

    if (!$check || mysqli_num_rows($check) === 0) {
        // Don't reveal if email exists or not for security
        header("Location: ../_user_interface/forgot_password.php?success=If+that+email+exists,+a+reset+link+has+been+sent.");
        exit();
    }

    $user = mysqli_fetch_assoc($check);

    // Generate a secure token
    $token = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Delete any existing token for this email
    mysqli_query($conn, "DELETE FROM password_resets WHERE email='$email'");

    // Insert new token
    $insert = "INSERT INTO password_resets (email, token, expires_at) VALUES ('$email', '$token', '$expires_at')";
    mysqli_query($conn, $insert);

    // Build reset link
    $reset_link = "http://localhost/CargoTracker_REP/CargoTracker/_user_interface/user_reset_password.php?token=$token&email=" . urlencode($email);
                    
    // Send email via PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'cargotracker001@gmail.com';
        $mail->Password   = 'vdvh xvio pqfd tzqr';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('cargotracker001@gmail.com', 'GNBTL System');
        $mail->addAddress($email, $user['fullname']);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; max-width: 500px; margin: auto;'>
                <h2 style='color: #009900;'>Password Reset</h2>
                <p>Hi {$user['fullname']},</p>
                <p>We received a request to reset your password. Click the button below to proceed. This link expires in <strong>1 hour</strong>.</p>
                <a href='$reset_link' style='
                    display: inline-block;
                    background-color: #009900;
                    color: #fff;
                    padding: 12px 24px;
                    border-radius: 20px;
                    text-decoration: none;
                    font-weight: bold;
                    margin: 16px 0;
                '>Reset Password</a>
                <p>If you did not request this, you can safely ignore this email.</p>
                <hr>
                <small style='color: #888;'>GNBTL Cargo Tracker System</small>
            </div>
        ";

        $mail->send();
        header("Location: ../_user_interface/user_forgot_password.php?success=If+that+email+exists,+a+reset+link+has+been+sent.");
        exit();

    } catch (Exception $e) {
        error_log("Reset email failed: " . $mail->ErrorInfo);
        header("Location: ../_user_interface/user_forgot_password.php?error=Failed+to+send+email.+Please+try+again.");
        exit();
    }

} else {
    header("Location: ../_user_interface/user_forgot_password.php");
    exit();
}
?>