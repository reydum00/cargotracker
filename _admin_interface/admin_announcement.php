<?php
include '../__back-end_processes\db_connect.php';
session_start();

// If not logged in OR not driver, redirect away
if (!isset($_SESSION['account_id']) || $_SESSION['role'] != 2) {
    header("Location: ../_user_interface/user_signup.php");
    exit();
}

$query = "SELECT * FROM announcements WHERE deleted_at IS NULL AND status = 'active' ORDER BY date_announced DESC";
$result = mysqli_query($conn, $query);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GNBTL Admin - Announcements</title>
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

    <div class="main-content-announcement">

        <h1>Announcements</h1>

        <div class="dashboard-columns-announcement">

            <div class="dashboard-card-announcement">
                <h2>Create New Announcement</h2>
                <form action="../__back-end_processes/process_announcement.php" method="POST">
                    <div class="form-group-announcement">
                        <label for="ann-title" class="form-label-announcement">Title</label>
                        <input type="text" id="ann-title" class="form-input-announcement" placeholder="e.g., Schedule" name="announcement_title">
                    </div>

                    <div class="form-group-announcement">
                        <label for="ann-priority" class="form-label-announcement">Priority</label>
                        <select id="ann-priority" class="form-select-announcement" name="priority_lvl">
                            <option value="low">Standard (Blue)</option>
                            <option value="medium">Normal (Green)</option>
                            <option value="high">Warning (Yellow)</option>
                            <option value="urgent">Urgent (Red)</option>
                        </select>
                    </div>

                    <div class="form-group-announcement">
                        <label for="ann-message" class="form-label-announcement">Message</label>
                        <textarea id="ann-message" class="form-textarea-announcement" placeholder="Write message to all drivers and staff..." name="announcement_msg"></textarea>
                    </div>

                    <button type="submit" class="form-button-announcement">Post Announcement</button>
                </form>
            </div>

            <div class="dashboard-card-announcement">
                <h2>Recent Announcements</h2>
                <div class="card-content-scrollable-announcement">
                    <form action="../__back-end_processes/process_archive_announcement.php" method="POST"></form>
                    <ul class="announcement-list-announcement">
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <li class="announcement-item-announcement priority-red">
                                <div class="notification-announcement <?php echo strtolower(htmlspecialchars($row['priority_level'])); ?>">
                                    <div class="announcement-title-announcement">
                                        <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                                    </div>
                                </div>
                                
                                <p><?php echo htmlspecialchars($row['announcement_message']); ?></p>
                                <form action="../__back-end_processes/process_archive_announcement.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="announcement_id" value="<?php echo $row['announcement_id']; ?>">
                                    <button class="announcement-delete-btn-announcement" type="submit">Archive</button>
                                </form>
                            </li>
                        <?php endwhile; ?>
                    </ul>
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