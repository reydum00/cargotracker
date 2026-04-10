<?php
session_start();
include '../__back-end_processes/db_connect.php';

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

if (!isset($_SESSION['account_id']) || $_SESSION['role'] != 0 || $_SESSION['is_new_client'] != 0) {
    header("Location: ../_user_interface/user_signup.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Reservation | GNBTL</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.jpg">
    <style>
        .main-content { 
            flex-grow: 1; 
            padding: 40px 0 60px; 
        }
        
        /* Dropdown */
        .user-dropdown { 
            position: relative; 
            display: inline-block; 
            padding: 0.4rem; 
        }

        .user-dropdown button { 
            background-color: transparent; 
            color: inherit; border: 
            none; padding: 10px 15px; 
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
            border: none; text-align: 
            left; cursor: 
            pointer; color: #d9534f; 
            font-size: 16px; 
        }

        .username-display { 
            font-weight: 600; 
            color: #009900;
         }


        /* Reservation Layout */
        .quote-page-layout { 
            display: flex; 
            flex-wrap: wrap; 
            max-width: 1200px; 
            margin: 0 auto; 
            gap: 40px; 
            padding: 0 5%; 
        }

        .quote-form-container { 
            flex: 2; 
            min-width: 300px; 
            background: #fff; 
            padding: 40px; 
            border-radius: 8px; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); 
        }

        .quote-form-container h1 { 
            font-size: 36px; 
            color: #333; 
            margin: 0 0 10px; 
        }

        .quote-form fieldset { 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            padding: 20px; 
            margin-bottom: 25px; 
        }

        .quote-form legend { 
            font-size: 20px; 
            font-weight: 600; 
            color: #009900; 
            padding: 0 10px; 
        }

        .form-group { 
            margin-bottom: 20px; 
        }

        .form-group label { 
            display: block; 
            font-weight: 600; 
            margin-bottom: 8px; 
            color: #333; 
        }

        .form-group input, .form-group textarea { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            box-sizing: border-box; 
        }

        .submit-button { 
            width: 100%; 
            padding: 15px; 
            font-size: 18px; 
            font-weight: 700; 
            color: #fff; 
            background-color: #009900; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            transition: 0.3s; 
        }

        .quote-sidebar { 
            flex: 1; 
            min-width: 300px; 
        }

        .sidebar-widget { 
            background: #fff; 
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); 
            margin-bottom: 30px; 
        }

        /* --- Header Responsiveness --- */
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
            
            .quote-page-layout { 
                flex-direction: column-reverse; 
            }
        }
    </style>
</head>
<body>
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

    <div class="main-content">
        <div class="quote-page-layout">
            <div class="quote-form-container">
                <h1>Book Your Shipment</h1>
                <p style="color: #555; margin-bottom: 30px;">Secure your delivery schedule with GNBTL.</p>
                
                <form action="../__back-end_processes/process_reservation.php" method="POST" class="quote-form">
                    <fieldset>
                        <legend>Reservation Details</legend>
                        <div class="form-group">
                            <label>Date of Reservation</label>
                            <input type="date" id="reservation-date" name="reservation_date" required>
                        </div>
                        <div class="form-group">
                            <label>Company Name</label>
                            <input type="text" name="company_name" required>
                        </div>

                        
                        <div class="form-group">
                            <label for="contact-number">Contact Number</label>
                            <input type="tel" id="contact-number" name="contact_number" required>
                        </div>

                        <div class="form-group">
                            <label for="email-address">Email Address</label>
                            <input type="email" id="email-address" name="email_address" required>
                        </div>

                        <div class="form-group">
                            <label>Shipment Information</label>
                            <textarea name="shipment" rows="4" placeholder="Describe items, weight, etc." required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Address/Destination</label>
                            <textarea name="address_destination" rows="4" required></textarea>
                        </div>
                    </fieldset>
                    <button type="submit" class="submit-button">Confirm Reservation</button>
                </form>
            </div>
            
            <div class="quote-sidebar">
                <div class="sidebar-widget">
                    <h3>Reservation Benefits</h3>
                    <p>✓ Guaranteed Pickup Time</p>
                    <p>✓ Priority Scheduling</p>
                    <p>✓ Real-Time Tracking</p>
                </div>
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
        document.getElementById('reservation-date').setAttribute('min', new Date().toISOString().split('T')[0]);
    </script>
</body>
</html>