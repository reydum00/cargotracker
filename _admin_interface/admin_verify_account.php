<?php
include '../__back-end_processes/db_connect.php';
session_start();

// Dummy session check
if (!isset($_SESSION['account_id']) || $_SESSION['role'] != 2) {
    header("Location: ../_user_interface/user_signup.php");
    exit();
}





$query = "SELECT * FROM account WHERE role =  0 AND is_new_client  = 1";
$new_account_verify = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GNBTL Admin - Verify Accounts</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.jpg">
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        /* Header */
        .main-content-dashboard h1 {
            color: #111827;
            /* dark text for white card */
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        /* Card styling */
        .dashboard-card-dashboard {
            background-color: #ffffff;
            /* white card */
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        /* Table styling */
        .verification-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            color: #111827;
            /* dark text for table */
        }

        .verification-table th,
        .verification-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            /* light gray line */
        }

        .verification-table th {
            background-color: #f3f4f6;
            /* light gray header */
            font-weight: 600;
            font-size: 0.95rem;
        }

        .verification-table tr:hover {
            background-color: #f9fafb;
            /* light hover */
            transition: 0.2s;
        }

        /* Buttons */
        .approve-btn,
        .reject-btn {
            padding: 6px 14px;
            border: none;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            margin-right: 5px;
            font-weight: 500;
        }

        .approve-btn {
            background-color: #10b981;
            /* green */
            color: white;
        }

        .approve-btn:hover {
            background-color: #059669;
        }

        .reject-btn {
            background-color: #ef4444;
            /* red */
            color: white;
        }

        .reject-btn:hover {
            background-color: #b91c1c;
        }

        /* Scrollable card */
        .card-content-scrollable-dashboard {
            max-height: 400px;
            overflow-y: auto;
        }

        /* Scrollbar styling */
        .card-content-scrollable-dashboard::-webkit-scrollbar {
            width: 8px;
        }

        .card-content-scrollable-dashboard::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
            border-radius: 4px;
        }

        .card-content-scrollable-dashboard::-webkit-scrollbar-track {
            background-color: #f9fafb;
        }
    </style>
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
        <div class="sidebar-header">GNBTL</div>
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

    <div class="main-content-dashboard">
        <h1>Pending Account Verifications</h1>

        <div class="dashboard-card-dashboard">
            <div class="card-content-scrollable-dashboard">
                <table class="verification-table">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Company Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Registered On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($new_account_verify)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['contact_num']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_date']); ?></td>
                                <td>
                                    <form action="../__back-end_processes/process_verify_account.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="account_id" value="<?php echo $row['account_id']; ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="approve-btn">Approve</button>
                                    </form>
                                    <form action="../__back-end_processes/process_verify_account.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="account_id" value="<?php echo $row['account_id']; ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="reject-btn">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
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
</html>