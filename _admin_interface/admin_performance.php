<?php
include '../__back-end_processes\db_connect.php';
session_start();

// If not logged in OR not driver, redirect away
if (!isset($_SESSION['account_id']) || $_SESSION['role'] != 2) {
    header("Location: ../_user_interface/user_signup.php");
    exit();
}

$query = "SELECT trips_completed, fullname FROM account WHERE role = 1";
$driver_trips_complete = mysqli_query($conn, $query);

$query ="SELECT vehicle_id, vehicle_name, total_trips FROM vehicles WHERE is_archived = 0";
$vehicle_total_trips = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GNBTL Admin - Performance Analytics</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.jpg">
    <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

    <div class="mobile-header">
        <button id="menu-toggle-btn">
            <span class="menu-icon-line"></span>
            <span class="menu-icon-line"></span>
            <span class="menu-icon-line"></span>
        </button>
        <div class="mobile-header-title">GNBTL</div>
    </div>

    <div class="sidebar" id="sidebar">
        <button id="sidebar-close-btn">&times;</button>
        <div class="sidebar-header"> GNBTL </div>
        <nav>
            <ul class="nav-links">
                <li><a href="../_admin_interface/admin.php">Dashboard</a></li>
                <li><a href="../_admin_interface/admin_verify_account.php">Verify Accounts</a></li>
                <li><a href="../_admin_interface/admin_trips.php">Trips & Reservation</a></li>
                <li><a href="../_admin_interface/admin_vehicles.php">Vehicles</a></li>
                <li><a href="../_admin_interface/admin_performance.php">Performance</a></li>
                <li><a href="../_admin_interface/admin_accounts.php">Driver Accounts</a></li>
                <li><a href="../_admin_interface/admin_announcement.php">Announcement</a></li>
            </ul>
        </nav>
        <div class="logout-container">
            <form action="../__back-end_processes/auth_logout.php" method="post">
                <button class="logout-btn">Log out</button>
            </form>
        </div>
    </div>

    <div class="main-content-performance">

        <h1>Performance Analytics</h1>

        <div class="dashboard-card-performance">
            <h2>Driver Performance</h2>
            <div class="card-content-table-wrapper-performance">
                <table class="content-table-performance">
                    <thead>
                        <tr>
                            <th>Driver Name</th>
                            <th>Trips Completed</th>   
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($driver_trips_complete)): ?>
                        <tr>
                            <td><?php echo strtolower(htmlspecialchars($row['fullname'])); ?></td>
                            <td><?php echo strtolower(htmlspecialchars($row['trips_completed'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dashboard-card-performance">
            <h2>Vehicle Performance</h2>
            <div class="card-content-table-wrapper-performance">
                <table class="content-table-performance">
                    <thead>
                        <tr>
                            <th>Vehicle ID</th>
                            <th>Total Trips</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($vehicle_total_trips)): ?>
                        <tr>
                            <td><?php echo strtolower(htmlspecialchars($row['vehicle_id'])); ?></td>
                            <td><?php echo strtolower(htmlspecialchars($row['vehicle_name'])); ?></td>
                            <td><?php echo strtolower(htmlspecialchars($row['total_trips'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var menuButton = document.getElementById("menu-toggle-btn");
            var closeButton = document.getElementById("sidebar-close-btn");
            var sidebar = document.getElementById("sidebar");
            menuButton.addEventListener("click", function() {
                sidebar.classList.add("open");
            });
            closeButton.addEventListener("click", function() {
                sidebar.classList.remove("open");
            });

            const currentPage = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.nav-links a');

            navLinks.forEach(link => {
                const linkPage = link.getAttribute('href').split('/').pop();
                if (linkPage === currentPage) {
                    link.classList.add('active');
                }
            });
        });
    </script>

</body>

</html>