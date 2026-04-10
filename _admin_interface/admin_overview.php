<?php
session_start();

// If not logged in OR not driver, redirect away
if (!isset($_SESSION['account_id']) || $_SESSION['role'] != 2) {
    header("Location: ../_user_interface/user_signup.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GNBTL Admin - Overview Metrics</title>
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

    <div class="main-content-overview">

        <h1>Overview Metrics</h1>

        <div class="metrics-grid-overview">

            <div class="metric-card-overview">
                <div class="icon-overview revenue"> </div>
                <div class="info-overview">
                    <div class="value-overview">₱28,120</div>
                    <div class="label-overview">Revenue (This Month)</div>
                </div>
            </div>

            <div class="metric-card-overview">
                <div class="icon-overview trips"> </div>
                <div class="info-overview">
                    <div class="value-overview">142</div>
                    <div class="label-overview">Trips (This Month)</div>
                </div>
            </div>

            <div class="metric-card-overview">
                <div class="icon-overview distance"> </div>
                <div class="info-overview">
                    <div class="value-overview">8,920 km</div>
                    <div class="label-overview">Total Distance</div>
                </div>
            </div>

            <div class="metric-card-overview">
                <div class="icon-overview issues"> </div>
                <div class="info-overview">
                    <div class="value-overview">8</div>
                    <div class="label-overview">All Pending Issues</div>
                </div>
            </div>

            <div class="metric-card-overview">
                <div class="icon-overview vehicles"> </div>
                <div class="info-overview">
                    <div class="value-overview">5</div>
                    <div class="label-overview">Vehicles on Road</div>
                </div>
            </div>

            <div class="metric-card-overview">
                <div class="icon-overview vehicles"> </div>
                <div class="info-overview">
                    <div class="value-overview">3</div>
                    <div class="label-overview">Vehicles in Maintenance</div>
                </div>
            </div>

            <div class="metric-card-overview">
                <div class="icon-overview clients"> </div>
                <div class="info-overview">
                    <div class="value-overview">24</div>
                    <div class="label-overview">Active Clients</div>
                </div>
            </div>

            <div class="metric-card-overview">
                <div class="icon-overview revenue"> </div>
                <div class="info-overview">
                    <div class="value-overview">2.5 hrs</div>
                    <div class="label-overview">Avg. Trip Duration</div>
                </div>
            </div>

        </div>

        <div class="dashboard-columns-overview">
            <div class="dashboard-card-overview" style="grid-column: 1 / -1;">
                <h2>Revenue vs. Trips (Last 30 Days)</h2>
                <div class="card-content-scrollable-overview" style="display: flex; align-items: center; justify-content: center; min-height: 300px; color: #999; background-color: #fafafa; border-radius: 8px;">
                    [Chart Area Placeholder]
                </div>
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