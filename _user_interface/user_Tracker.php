<?php
session_start();
include '../__back-end_processes/db_connect.php';

// Get logged-in user's information if they are logged in
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
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" type="image/x-icon" href="../images/favicon.jpg">
    <title>Track</title>
    <style>
        /* Page-specific styles only - Nav and Footer styles are in style.css */
        /* User dropdown styling */


        /* Responsive Design */
        @media (max-width: 1024px) {
            .map-container {
                width: 90%;
                height: 500px;
            }

            .map-placeholder {
                font-size: 60px;
            }
        }

        @media (max-width: 768px) {
            .map-container {
                height: 400px;
                margin: 40px auto;
            }

            .map-placeholder {
                font-size: 40px;
            }
        }

        @media (max-width: 480px) {
            .map-container {
                height: 300px;
            }

            .map-placeholder {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>
    <nav>
        <div class="logo-container">
            <img src="../images/GNBTL logo only.png" alt="Logo">
        </div>

        <div class="navbar-div">
            <a href="user_index.php">Home</a>
            <a href="user_about.php">About Us</a>
            <a href="user_contact.php">Contact</a>
            <a href="user_rate.php">Reservation</a>

            <div class="dropdown">
                <button>Rates&#9660;</button>
                <div class="dropdown-menu">
                    <a href="user_qoute.php">Request a Quote</a>
                    <a href="user_rate.php">Rate Calculator</a>
                </div>
            </div>

            <div class="dropdown">
                <button>Cargo&#9660;</button>
                <div class="dropdown-menu">
                    <a href="user_tracker.php">Track your Delivery</a>
                    <a href="#">Contact Courier</a>
                </div>
            </div>

            <?php if ($logged_in_username): ?>
                <!-- Show username dropdown if logged in -->
                <div class="user-dropdown">
                    <button>
                        <span class="username-display"><?php echo htmlspecialchars($logged_in_username); ?></span> &#9660;
                    </button>
                    <div class="user-dropdown-menu">
                        <form action="../__back-end_processes/auth_logout.php" method="POST">
                            <button type="submit" class="logout-btn">Log Out</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <!-- Show Sign In link if not logged in -->
                <a href="../_user_interface/user_signup.php">Sign In</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="map-container">
        <iframe
            width="100%"
            height="100%"
            style="border:0"
            loading="lazy"
            allowfullscreen
            referrerpolicy="no-referrer-when-downgrade"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3856.510842562547!2d120.9842!3d14.5995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397cb1690f36473%3A0x1d1bb84f8eb1a2b9!2sManila!5e0!3m2!1sen!2sph!4v1696521535671!5m2!1sen!2sph">
        </iframe>
    </div>


    <footer>
        <div>
            <h1>GNBTL</h1>
        </div>

        <div>
            <p>Trucking Logistics</p>
        </div>

        <div class="footer-grid">
            <p>Providing reliable trucking and logistics services across the nation. Our commitment to excellence
                ensures your cargo arrives safely and on time, every time.</p>
            <p>With modern fleet management and real-time tracking, we offer transparency and efficiency in all
                our operations. Trust us for your transportation needs.</p>
            <p>Our professional team is available 24/7 to assist you with quotes, tracking, and any logistics
                inquiries. Customer satisfaction is our top priority.</p>
            <p>Contact us today to learn more about our competitive rates and comprehensive logistics solutions
                tailored to your business requirements.</p>
        </div>

        <hr>

        <div class="footer-copyright">
            <p>@GNBTL</p>
            <p>All Rights Reserved</p>
        </div>
    </footer>
</body>

</html>