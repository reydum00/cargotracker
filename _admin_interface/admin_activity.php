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
    <title>GNBTL Admin - Recent Activity</title>
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

    <div class="main-content-activity">

        <h1>Recent Activity</h1>

        <div class="dashboard-card-activity">
            <h2>Activity Log</h2>
            <div class="card-content-scrollable-activity">
                <ul class="activity-log-activity">
                    <li class="activity-item-activity">
                        <div class="activity-icon-activity">
                        </div>
                        <div class="activity-details-activity">
                            <div class="activity-description-activity">
                                <strong>Trip T-1025</strong> was created and assigned to <strong>Neil Jason Flores</strong>.
                            </div>
                            <div class="activity-timestamp-activity">5 minutes ago</div>
                        </div>
                    </li>

                    <li class="activity-item-activity">
                        <div class="activity-icon-activity">
                        </div>
                        <div class="activity-details-activity">
                            <div class="activity-description-activity">
                                New issue reported for <strong>TRUCK-001</strong>: "Engine Overheating".
                            </div>
                            <div class="activity-timestamp-activity">1 hour ago</div>
                        </div>
                    </li>

                    <li class="activity-item-activity">
                        <div class="activity-icon-activity">
                        </div>
                        <div class="activity-details-activity">
                            <div class="activity-description-activity">
                                <strong>Trip T-1024</strong> (Driver: Driefen Alfonso) was marked as <strong>Completed</strong>.
                            </div>
                            <div class="activity-timestamp-activity">3 hours ago</div>
                        </div>
                    </li>

                    <li class="activity-item-activity">
                        <div class="activity-icon-activity">
                        </div>
                        <div class="activity-details-activity">
                            <div class="activity-description-activity">
                                <strong>TRUCK-003</strong> status changed to <strong>"Under Maintenance"</strong>.
                            </div>
                            <div class="activity-timestamp-activity">8 hours ago</div>
                        </div>
                    </li>

                    <li class="activity-item-activity">
                        <div class="activity-icon-activity">
                        </div>
                        <div class="activity-details-activity">
                            <div class="activity-description-activity">
                                <strong>Admin</strong> logged in.
                            </div>
                            <div class="activity-timestamp-activity">Yesterday at 8:00 AM</div>
                        </div>
                    </li>
                </ul>
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