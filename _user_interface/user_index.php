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
    <title>Home | GNBTL</title>
    <style>
        /* User dropdown styling */
        .user-dropdown { 
            position: relative; 
            display: inline-block; 
            padding: 0.4rem; 
        }

        .user-dropdown button { 
            background-color: transparent; 
            color: inherit; 
            border: none; 
            padding: 10px 15px;
             cursor: pointer; 
             font-size: 16px; 
             font-weight: 500; 
            }

        .user-dropdown-menu { 
            display: none; 
            position: absolute; 
            right: 0; 
            background-color: white;
             min-width: 180px; 
             box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
             border-radius: 4px; 
             z-index: 1000; 
             margin-top: 5px; 
            }

        .user-dropdown:hover .user-dropdown-menu { 
            display: block; 
        }

        .user-dropdown-menu a, .user-dropdown-menu form { 
            display: block; 
            width: 100%; 
        }

        .user-dropdown-menu a { 
            color: #333; 
            padding: 12px 16px; 
            text-decoration: none; 
        }

        .user-dropdown-menu .logout-btn { 
            width: 100%; 
            padding: 12px 16px; 
            background-color: transparent; 
            border: none; 
            text-align: left; 
            cursor: pointer; 
            color: #d9534f; 
            font-size: 16px; 
        }

        .username-display { 
            font-weight: 600; 
            color: #009900; 
        }

        /* HEADER RESPONSIVENESS */
        .menu-toggle { display: none; }

        @media (max-width: 768px) {
            .menu-toggle { 
                display: block; 
                font-size: 28px; 
                background: none; 
                border: none; 
                cursor: pointer; 
                color: #009900; 
                padding: 10px; 
            }

            nav { 
                justify-content: space-between !important; 
                padding: 10px 20px !important; 
                position: relative; 
            }

            .navbar-div { 
                display: none; 
                flex-direction: column; 
                position: absolute; 
                top: 70px; 
                left: 0; 
                width: 100%; 
                background: #f5f5f5; 
                z-index: 100; 
                box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
            }
            
            .navbar-div.active { 
                display: flex; 
            }

            .navbar-div a { 
                width: 100%; 
                text-align: center; 
                padding: 15px !important; 
                border-bottom: 1px solid #ddd; 
                color: #333;
             }

            .user-dropdown { 
                width: 100%; 
                text-align: center; 
            }
        }
    </style>
</head>

<body>
    <div class="hero">
        <video autoplay muted loop playsinline class="hero-video">
            <source src="../test-vid.mp4" type="video/mp4">
        </video>
        <div class="overlay"></div>
        <h1>GNBTL LOGISTICS</h1>
        <h2>Corporation</h2>
    </div>

    <nav>
        <div class="logo-container">
            <img src="../images/GNBTL logo only.png" alt="Logo">
        </div>

        <button class="menu-toggle" onclick="toggleMenu()">☰</button>

        <div class="navbar-div">
            <a href="user_index.php">Home</a>
            <a href="user_about.php">About Us</a>
            <a href="user_contact.php">Contact</a>
            <a href="user_rate.php">Reservation</a>

            <?php if ($logged_in_username): ?>
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
                <a href="../_user_interface/user_signup.php">Sign In</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="content-overlay">
            <div class="image-text-section">
                <div class="image-container"><img src="../images/test.png" alt="Truck"></div>
                <div class="text-container text-with-title">
                    <h2>Reliable Transportation Services</h2>
                    <p>We provide comprehensive trucking logistics solutions tailored to meet your business needs. Our fleet of modern vehicles ensures safe and timely delivery of your cargo across the nation.</p>
                </div>
            </div>

            <div class="image-text-section">
                <div class="text-container text-with-title">
                    <h2>Real-Time Tracking</h2>
                    <p>Stay informed about your shipment's location with our advanced GPS tracking system. Monitor your cargo in real-time and receive instant updates throughout the delivery process.</p>
                </div>
                <div class="image-container"><img src="../images/highway.png" alt="Warehouse"></div>
            </div>
        </div>
    </div>

    <footer>
        <div>
            <h1>GNBTL</h1>
        </div>

        <div>
            <p>Trucking Logistics</p>
        </div>

        <div class="footer-grid">
            <p>Providing reliable trucking and logistics services across the nation. Our commitment to excellence ensures your cargo arrives safely and on time, every time.</p>
            <p>With modern fleet management and real-time tracking, we offer transparency and efficiency in all our operations. Trust us for your transportation needs.</p>
            <p>Our professional team is available 24/7 to assist you with quotes, tracking, and any logistics inquiries. Customer satisfaction is our top priority.</p>
            <p>Contact us today to learn more about our competitive rates and comprehensive logistics solutions tailored to your business requirements.</p>
        </div>

        <hr>

        <div class="footer-copyright">
            <p>@GNBTL</p>
            <p>All Rights Reserved</p>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            document.querySelector('.navbar-div').classList.toggle('active');
        }
    </script>
</body>

</html>