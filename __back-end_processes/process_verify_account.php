<?php
session_start();
include 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['account_id']) || $_SESSION['role'] != 2) {
    header("Location: ../_user_interface/user_signup.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_id = $_POST['account_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        // Approve: Set is_new_client to 0
        $stmt = $conn->prepare("UPDATE account SET is_new_client = 0 WHERE account_id = ?");
        $stmt->bind_param("i", $account_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: ../_admin_interface/admin_verify_account.php?success=approved");
        } else {
            $stmt->close();
            $conn->close();
            header("Location: ../_admin_interface/admin_verify_account.php?error=approve_failed");
        }
    } 
    elseif ($action === 'reject') {
        // Reject: Delete the account or set a rejected flag
        $stmt = $conn->prepare("DELETE FROM account WHERE account_id = ?");
        $stmt->bind_param("i", $account_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: ../_admin_interface/admin_verify_account.php?success=rejected");
        } else {
            $stmt->close();
            $conn->close();
            header("Location: ../_admin_interface/admin_verify_account.php?error=reject_failed");
        }
    }
    exit();
}
?>