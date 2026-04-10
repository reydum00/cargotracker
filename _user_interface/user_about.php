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
    <title>About Us | GNBTL</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.jpg">
    <style>
        .main-content { flex-grow: 1; background-color: #085508ff; }
        
        .hero-section { position: relative; height: 40vh; background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; text-align: center; color: #fff; background-color: #065706; }
        .hero-section::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); }
        .hero-content { position: relative; z-index: 1; }
        .hero-content h1 { font-size: 48px; margin: 0; font-weight: 700; }
        .hero-content p { font-size: 20px; margin-top: 10px; }

        .about-layout { display: flex; flex-wrap: wrap; align-items: center; padding: 60px 5%; max-width: 1200px; margin: 0 auto; gap: 40px; background-color: #085508ff; }
        .about-text { flex: 1; min-width: 300px; color: #f9f9f9; }
        .about-text h2 { font-size: 36px; margin-top: 0; }
        .about-text p { font-size: 18px; line-height: 1.6; color: #f9f9f9; }
        .about-image { flex: 1; min-width: 300px; }
        .about-image img { width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }

        .user-dropdown { position: relative; display: inline-block; padding: 0.4rem; }
        .user-dropdown button { background-color: transparent; color: inherit; border: none; padding: 10px 15px; cursor: pointer; font-size: 16px; font-weight: 500; }
        .user-dropdown-menu { display: none; position: absolute; right: 0; background-color: white; min-width: 180px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 4px; z-index: 1000; margin-top: 5px; }
        .user-dropdown:hover .user-dropdown-menu { display: block; }
        .user-dropdown-menu a, .user-dropdown-menu form { display: block; width: 100%; }
        .user-dropdown-menu a { color: #333; padding: 12px 16px; text-decoration: none; }
        .user-dropdown-menu .logout-btn { width: 100%; padding: 12px 16px; background-color: transparent; border: none; text-align: left; cursor: pointer; color: #d9534f; font-size: 16px; }
        .username-display { font-weight: 600; color: #009900; }

        .menu-toggle { display: none; }

        @media (max-width: 768px) {
            .menu-toggle { display: block; font-size: 28px; background: none; border: none; cursor: pointer; color: #009900; padding: 10px; }
            nav { justify-content: space-between !important; padding: 10px 20px !important; position: relative; }
            .navbar-div { display: none; flex-direction: column; position: absolute; top: 70px; left: 0; width: 100%; background: #f5f5f5; z-index: 100; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
            .navbar-div.active { display: flex; }
            .navbar-div a { width: 100%; text-align: center; padding: 15px !important; border-bottom: 1px solid #ddd; color: #333; }
            .user-dropdown { width: 100%; text-align: center; }
            .hero-content h1 { font-size: 32px; }
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
        <section class="hero-section">
            <div class="hero-content">
                <h1>About GNBTL</h1>
                <p>Your Partner in Reliable Trucking & Logistics</p>
            </div>
        </section>

        <section class="about-layout">
            <div class="about-text">
                <h2>Who We Are</h2>
                <p>We are a local logistics company providing reliable and efficient delivery services for businesses and individuals. With a dedicated team and well-maintained vehicles, we ensure your goods are transported safely and on time.</p>
            </div>
            <div class="about-image">
                <img src="../images/18-wheeler.png" alt="GNBTL Team">
            </div>
        </section>
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