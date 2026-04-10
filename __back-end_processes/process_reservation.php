<?php
session_start();
include '../__back-end_processes/db_connect.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get form data and sanitize
    $reservation_date = trim($_POST['reservation_date']);
    $company_name = trim($_POST['company_name']);
    $contact_number = trim($_POST["contact_number"]);
    $email_address = trim($_POST["email_address"]);
    $shipment = trim($_POST['shipment']);
    $address_destination = trim($_POST['address_destination']);

    // Validate required fields
    if (empty($reservation_date) || empty($company_name) || empty($shipment) || empty($address_destination)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../_user_interface/user_rate.php");
        exit();
    }

    // Validate date is not in the past
    $today = date('Y-m-d');
    if ($reservation_date < $today) {
        $_SESSION['error'] = "Reservation date cannot be in the past.";
        header("Location: ../_user_interface/user_rate.php");
        exit();
    }

    // Get account_id if user is logged in (optional)
    $account_id = isset($_SESSION['account_id']) ? $_SESSION['account_id'] : null;

    // Prepare SQL statement
    $sql = "INSERT INTO reservations (account_id, reservation_date, company_name, contact_number, email_address, shipment, address_destination, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // "i" for account_id, "s" for all the rest
        $stmt->bind_param("issssss", $account_id, $reservation_date, $company_name, $contact_number, $email_address, $shipment, $address_destination);

        if ($stmt->execute()) {
            $reservation_id = $stmt->insert_id;
            $_SESSION['success'] = "Reservation submitted successfully! Your reservation ID is #" . $reservation_id . ". We'll contact you within one business hour.";

            // Optional: Send email notification here
            // sendReservationEmail($company_name, $reservation_date, $reservation_id);

            header("Location: ../_user_interface/user_rate.php");
            exit();
        } else {
            $_SESSION['error'] = "Error submitting reservation. Please try again.";
            header("Location: ../_user_interface/user_rate.php");
            exit();
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Database error. Please try again later.";
        header("Location: ../_user_interface/user_rate.php");
        exit();
    }

    $conn->close();
} else {
    // If accessed directly without POST, redirect back
    header("Location: ../_user_interface/user_rate.php");
    exit();
}
