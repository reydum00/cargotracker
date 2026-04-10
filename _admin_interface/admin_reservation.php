<?php
session_start();
include '../__back-end_processes/db_connect.php';

// Admin only
if (!isset($_SESSION['account_id']) || $_SESSION['role'] != 2) {
    header("Location: ../_user_interface/user_signup.php");
    exit();
}

// Fetch all reservations
$query = "SELECT r.*, a.username 
          FROM reservations r 
          LEFT JOIN account a ON r.account_id = a.account_id 
          ORDER BY r.created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>GNBTL | Reservations</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Base Admin CSS -->
    <link rel="stylesheet" href="../css/admin_style.css">

    <!-- PAGE-SPECIFIC DESIGN -->
    <style>
        /* ===== CARD ===== */
        .dashboard-card-dashboard {
            background: #ffffff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
        }

        .dashboard-card-dashboard h2 {
            margin-bottom: 16px;
            font-size: 18px;
            font-weight: 600;
            color: #222;
        }

        /* ===== ALERTS ===== */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        /* ===== TABLE ===== */
        .table-responsive-dashboard {
            overflow-x: auto;
        }

        .admin-table-dashboard {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
            font-size: 14px;
        }

        .admin-table-dashboard thead {
            background: #f6f8fb;
        }

        .admin-table-dashboard th {
            padding: 14px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 1px solid #e5e7eb;
        }

        .admin-table-dashboard td {
            padding: 14px;
            color: #444;
            border-bottom: 1px solid #f0f0f0;
        }

        .admin-table-dashboard tbody tr:hover {
            background: #f9fafb;
        }

        /* ===== STATUS ===== */
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.approved {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.completed {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-badge.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        /* ===== BUTTONS ===== */
        .btn-dashboard {
            padding: 6px 14px;
            font-size: 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            margin-right: 6px;
            transition: 0.2s ease;
        }

        .btn-dashboard.approve {
            background: #28a745;
            color: #fff;
        }

        .btn-dashboard.reject {
            background: #dc3545;
            color: #fff;
        }

        .btn-dashboard.view {
            background: #007bff;
            color: #fff;
        }

        .btn-dashboard.complete {
            background: #17a2b8;
            color: #fff;
        }

        .btn-dashboard:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-dashboard:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 16px;
            opacity: 0.3;
        }

        /* ===== TRUNCATE TEXT ===== */
        .truncate {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body>

    <!-- MOBILE HEADER -->
    <div class="mobile-header">
        <button id="menu-toggle-btn">
            <span class="menu-icon-line"></span>
            <span class="menu-icon-line"></span>
            <span class="menu-icon-line"></span>
        </button>
        <div class="mobile-header-title">GNBTL</div>
    </div>

    <!-- SIDEBAR -->
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

    <!-- MAIN CONTENT -->
    <div class="main-content-dashboard">

        <h1>Reservations</h1>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                echo htmlspecialchars($_SESSION['success']); 
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php 
                echo htmlspecialchars($_SESSION['error']); 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="dashboard-card-dashboard">
            <h2>Delivery & Cargo Requests</h2>

            <div class="table-responsive-dashboard">
                <table class="admin-table-dashboard">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Company</th>
                            <th>Shipment</th>
                            <th>Destination</th>
                            <th>Requested By</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($row['reservation_id']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['reservation_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                                    <td>
                                        <div class="truncate" title="<?php echo htmlspecialchars($row['shipment']); ?>">
                                            <?php echo htmlspecialchars($row['shipment']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="truncate" title="<?php echo htmlspecialchars($row['address_destination']); ?>">
                                            <?php echo htmlspecialchars($row['address_destination']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo $row['username'] ? htmlspecialchars($row['username']) : 'Guest'; ?></td>
                                    <td>
                                        <span class="status-badge <?php echo strtolower($row['status']); ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] == 'pending'): ?>
                                            <form method="POST" action="process_reservation_action.php" style="display: inline;">
                                                <input type="hidden" name="reservation_id" value="<?php echo $row['reservation_id']; ?>">
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="btn-dashboard approve">Approve</button>
                                            </form>
                                            <form method="POST" action="process_reservation_action.php" style="display: inline;">
                                                <input type="hidden" name="reservation_id" value="<?php echo $row['reservation_id']; ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" class="btn-dashboard reject">Reject</button>
                                            </form>
                                        <?php elseif ($row['status'] == 'approved'): ?>
                                            <button class="btn-dashboard view" onclick="viewDetails(<?php echo $row['reservation_id']; ?>)">View</button>
                                            <form method="POST" action="process_reservation_action.php" style="display: inline;">
                                                <input type="hidden" name="reservation_id" value="<?php echo $row['reservation_id']; ?>">
                                                <input type="hidden" name="action" value="complete">
                                                <button type="submit" class="btn-dashboard complete">Complete</button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn-dashboard view" onclick="viewDetails(<?php echo $row['reservation_id']; ?>)">View</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p>No reservations found</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- JS -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuBtn = document.getElementById("menu-toggle-btn");
            const closeBtn = document.getElementById("sidebar-close-btn");
            const sidebar = document.getElementById("sidebar");

            menuBtn.addEventListener("click", () => sidebar.classList.add("open"));
            closeBtn.addEventListener("click", () => sidebar.classList.remove("open"));
        });

        function viewDetails(reservationId) {
            // You can implement a modal or redirect to a details page
            window.location.href = 'view_reservation.php?id=' + reservationId;
        }
    </script>

</body>

</html>