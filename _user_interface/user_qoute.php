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
    <title>Request a Quote | GNBTL</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.jpg">
    <style>
        .main-content { flex-grow: 1; 
        padding: 40px 0 60px; 
    }

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
        }

        .form-group input, .form-group textarea { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            box-sizing: border-box; 
        }

        .form-row {
             display: flex; 
             flex-wrap: wrap; 
             gap: 20px; 
            }

        .form-row .form-group { 
            flex: 1; 
            min-width: 200px; 
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

        /* USER DROPDOWN */
        .user-dropdown { 
            position: relative; 
            display: inline-block; 
            padding: 0.4rem; 
        }

        .user-dropdown button { 
            background: transparent; 
            border: none; 
            cursor: pointer; 
            font-size: 16px; 
            font-weight: 500; 
            color: inherit; 
        }

        .user-dropdown-menu { 
            display: none; 
            position: absolute; 
            right: 0; 
            background: white; 
            min-width: 180px; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
            border-radius: 4px; 
            z-index: 1000; 
        }

        .user-dropdown:hover .user-dropdown-menu { 
            display: block; 
        }

        .logout-btn { 
            width: 100%; 
            padding: 12px 16px; 
            border: none; 
            background: transparent; 
            color: #d9534f; 
            text-align: left; 
            cursor: pointer; }

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
                <h1>Request a Quote</h1>
                <form action="../__back-end_processes/process_reservation.php" method="POST" class="quote-form">
                    <fieldset>
                        <legend>1. Contact Information</legend>
                        <div class="form-row">
                            <div class="form-group"><label>First Name</label><input type="text" name="first_name" required></div>
                            <div class="form-group"><label>Last Name</label><input type="text" name="last_name" required></div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>2. Shipment Details</legend>
                        <div class="form-group"><label>Pickup Address</label><textarea name="pickup_address" rows="3" required></textarea></div>
                        <div class="form-group"><label>Destination Address</label><textarea name="destination_address" rows="3" required></textarea></div>
                        <div class="form-group"><label>Pickup Date</label><input type="date" name="pickup_date" required></div>
                    </fieldset>
                    <button type="submit" class="submit-button">Get My Quote</button>
                </form>
            </div>

            <div class="quote-sidebar">
                <div class="sidebar-widget">
                    <h3>Why Ship with GNBTL?</h3>
                    <ul>
                        <li>Reliable, On-Time Delivery</li>
                        <li>Real-Time GPS Tracking</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-copyright">
            <p>@GNBTL | All Rights Reserved</p>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            document.querySelector('.navbar-div').classList.toggle('active');
        }
    </script>
</body>
</html>