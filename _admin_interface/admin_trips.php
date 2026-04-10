<?php
include '../__back-end_processes/db_connect.php';
session_start();

// If not logged in OR not driver, redirect away
if (!isset($_SESSION['account_id']) || $_SESSION['role'] != 2) {
    header("Location: ../_user_interface/user_signup.php");
    exit();
}

$query = "SELECT fullname FROM account WHERE role = 1 AND is_assigned = 0";
$result = mysqli_query($conn, $query);

$query = "SELECT * FROM vehicles WHERE is_assigned = 0";
$result1 = mysqli_query($conn, $query);

$query = "SELECT * FROM trips WHERE status = 'pending'";
$result2 = mysqli_query($conn, $query);

// QUERY INSIDE THE MODAL
$query = "SELECT fullname FROM account WHERE role = 1 AND is_assigned = 0";
$result00 = mysqli_query($conn, $query);

$query = "SELECT * FROM vehicles WHERE is_assigned = 0";
$result01 = mysqli_query($conn, $query);

// FETCH PENDING/APPROVED RESERVATIONS
$query_reservations = "SELECT r.*, a.username 
                       FROM reservations r 
                       LEFT JOIN account a ON r.account_id = a.account_id 
                       WHERE r.status IN ('pending', 'approved')
                       ORDER BY r.reservation_date ASC";
$result_reservations = mysqli_query($conn, $query_reservations);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GNBTL Admin - Trip Management</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.jpg">
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 30px;
            border: 1px solid #888;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }

        .modal-header h2 {
            margin: 0;
            color: #333;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #000;
        }

        .modal-body {
            margin-bottom: 20px;
        }

        .modal-field {
            margin-bottom: 15px;
        }

        .modal-field label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        .modal-field input,
        .modal-field select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .modal-footer {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
        }

        .modal-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-save {
            background-color: #4CAF50;
            color: white;
            flex: 1;
        }

        .btn-save:hover {
            background-color: #45a049;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
            flex: 1;
        }

        .btn-delete:hover {
            background-color: #da190b;
        }

        .btn-cancel {
            background-color: #757575;
            color: white;
            flex: 1;
        }

        .btn-cancel:hover {
            background-color: #616161;
        }

        /* Reservations List Styles */
        .reservations-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .reservations-card h2 {
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 18px;
            color: #333;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }

        .reservation-item {
            background: #f9f9f9;
            border-left: 4px solid #009900;
            padding: 12px 15px;
            margin-bottom: 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .reservation-item:hover {
            background: #f0f9f0;
            box-shadow: 0 2px 6px rgba(0, 153, 0, 0.1);
        }

        .reservation-item.pending {
            border-left-color: #ffc107;
        }

        .reservation-item.approved {
            border-left-color: #28a745;
        }

        .reservation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .reservation-id {
            font-weight: 700;
            color: #009900;
            font-size: 14px;
        }

        .reservation-date {
            font-size: 12px;
            color: #666;
        }

        .reservation-company {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }

        .reservation-details {
            font-size: 13px;
            color: #555;
            line-height: 1.4;
        }

        .reservation-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 6px;
        }

        .reservation-status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .reservation-status.approved {
            background: #d4edda;
            color: #155724;
        }

        .empty-reservations {
            text-align: center;
            padding: 30px;
            color: #999;
        }

        .use-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            margin-top: 8px;
            margin-right: 6px;
            transition: background 0.2s;
        }

        .use-btn:hover {
            background: #0056b3;
        }

        .delete-reservation-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s;
        }

        .delete-reservation-btn:hover {
            background: #c82333;
        }

        .reservation-actions {
            margin-top: 8px;
        }

        .dashboard-columns-trip {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .dashboard-card-trip:nth-child(3) {
            grid-column: span 2;
            /* Make the 3rd card (All Trips) span both columns */
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

    <div class="main-content-trip">

        <h1>Trip Management</h1>

        <div class="dashboard-columns-trip">

            <!-- RESERVATIONS LIST -->
            <div class="reservations-card">
                <h2>📋 Pending Reservations</h2>
                <div style="max-height: 400px; overflow-y: auto;">
                    <?php if ($result_reservations && mysqli_num_rows($result_reservations) > 0): ?>
                        <?php while ($res = mysqli_fetch_assoc($result_reservations)): ?>
                            <div class="reservation-item <?php echo $res['status']; ?>">
                                <div class="reservation-header">
                                    <span class="reservation-id">#<?php echo htmlspecialchars($res['reservation_id']); ?></span>
                                    <span class="reservation-date"><?php echo date('M d, Y', strtotime($res['reservation_date'])); ?></span>
                                </div>
                                <div class="reservation-company"><?php echo htmlspecialchars($res['company_name']); ?></div>
                                <div class="reservation-details">
                                    <strong>Shipment:</strong> <?php echo htmlspecialchars(substr($res['shipment'], 0, 60)) . (strlen($res['shipment']) > 60 ? '...' : ''); ?><br>
                                    <strong>Destination:</strong> <?php echo htmlspecialchars(substr($res['address_destination'], 0, 60)) . (strlen($res['address_destination']) > 60 ? '...' : ''); ?><br>
                                    <strong>Contact Number:</strong> <?php echo htmlspecialchars(substr($res['contact_number'], 0, 60)) . (strlen($res['contact_number']) > 60 ? '...' : ''); ?><br>
                                    <strong>Email Address:</strong> <?php echo htmlspecialchars(substr($res['email_address'], 0, 60)) . (strlen($res['email_address']) > 60 ? '...' : ''); ?>
                                </div>
                                <span class="reservation-status <?php echo $res['status']; ?>"><?php echo ucfirst($res['status']); ?></span>
                                <div class="reservation-actions">
                                    <button class="use-btn" onclick="fillFromReservation(<?php echo htmlspecialchars(json_encode($res)); ?>)">Use for Trip</button>
                                    <button class="delete-reservation-btn" onclick="deleteReservation(<?php echo $res['reservation_id']; ?>)">Delete</button>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="empty-reservations">
                            <p>No pending reservations</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- CREATE TRIP FORM -->
            <div class="dashboard-card-trip">
                <h2>Create New Trip</h2>
                <form action="../__back-end_processes/process_add_trips.php" method="POST" id="createTripForm">
                    <input type="hidden" name="reservation_id" id="reservation_id">

                    <div class="form-group-trip">
                        <label for="trip_driver" class="form-label-trip">Assign Driver</label>
                        <select id="trip_driver" class="form-select-trip" name="assigned_Driver">
                            <option value="">Select a driver...</option>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <option value="<?php echo htmlspecialchars($row['fullname']); ?>"><?php echo htmlspecialchars($row['fullname']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group-trip">
                        <label for="trip_vehicle" class="form-label-trip">Assign Vehicle</label>
                        <select id="trip_vehicle" class="form-select-trip" name="assigned_Vehicle">
                            <option value="">Select an available vehicle...</option>
                            <?php while ($row = mysqli_fetch_assoc($result1)): ?>
                                <option value="<?php echo htmlspecialchars($row['vehicle_name']); ?>"><?php echo htmlspecialchars($row['vehicle_name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group-trip">
                        <label for="trip_client" class="form-label-trip">Assign Client</label>
                        <input type="text" id="trip_client" class="form-input-trip" placeholder="Enter client/company name" name="Client" required>
                    </div>

                    <div class="form-group-trip">
                        <label for="trip_type" class="form-label-trip">Type of Delivery</label>
                        <select id="trip_type" class="form-select-trip" name="tripType">
                            <option value="">Type of Delivery</option>
                            <option value="call-in">Call-in</option>
                            <option value="reservation">Reservation</option>
                        </select>
                    </div>

                    <div class="form-group-trip">
                        <label for="trip_destination" class="form-label-trip">Destination</label>
                        <input type="text" id="trip_destination" class="form-input-trip" placeholder="e.g., Manila Port" name="delivery_Destination" required>
                    </div>

                    <div class="form-group-trip">
                        <label for="trip_destination" class="form-label-trip">Recipient Contacts</label>
                        <input type="tel" id="trip_recipient_email" class="form-input-trip" placeholder="Contact No." name="contact_number" required>
                        <input type="email" id="trip_recipient_num" class="form-input-trip" placeholder="Email" name="email_address" required>
                    </div>

                    <button type="submit" class="form-button-trip">Create Trip</button>
                </form>
            </div>

            <!-- ALL TRIPS TABLE -->
            <div class="dashboard-card-trip">
                <h2>All Trips</h2>
                <div class="card-content-table-wrapper-trip">
                    <table class="content-table-trip">
                        <thead>
                            <tr>
                                <th>Trip ID</th>
                                <th>Driver</th>
                                <th>Vehicle</th>
                                <th>Client</th>
                                <th>Destination</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result2)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['trip_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['driver']); ?></td>
                                    <td><?php echo htmlspecialchars($row['vehicle']); ?></td>
                                    <td><?php echo htmlspecialchars($row['client']); ?></td>
                                    <td><?php echo htmlspecialchars($row['destination']); ?></td>
                                    <td><?php echo htmlspecialchars($row['trip_type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                    <td>
                                        <button class="action-btn-trip edit" onclick="openModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">View/Edit</button>
                                    </td>
                                </tr>
                            <?php endwhile ?>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>

    </div>

    <!-- Modal -->
    <div id="tripModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Trip Details</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <form id="editTripForm" action="../__back-end_processes/process_edit_trip.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="modal_trip_id" name="trip_id">

                    <div class="modal-field">
                        <label>Trip ID</label>
                        <input type="text" id="modal_trip_id_display" disabled>
                    </div>

                    <div class="modal-field">
                        <label for="trip_driver" class="form-label-trip">Assign Driver</label>
                        <select id="modal_driver" class="form-select-trip" name="driver">
                            <option value="">Select a driver...</option>
                            <?php while ($row = mysqli_fetch_assoc($result00)): ?>
                                <option value="<?php echo htmlspecialchars($row['fullname']); ?>"><?php echo htmlspecialchars($row['fullname']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="modal-field">
                        <label for="trip_vehicle" class="form-label-trip">Assign Vehicle</label>
                        <select id="modal_vehicle" class="form-select-trip" name="vehicle">
                            <option value="">Select an available vehicle...</option>
                            <?php while ($row = mysqli_fetch_assoc($result01)): ?>
                                <option value="<?php echo htmlspecialchars($row['vehicle_name']); ?>"><?php echo htmlspecialchars($row['vehicle_name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="modal-field">
                        <label for="trip_client" class="form-label-trip">Assign Client</label>
                        <input type="text" id="modal_client" class="form-input-trip" placeholder="Enter client/company name" name="client" required>
                        
                    </div>

                    <div class="modal-field">
                        <label for="modal_destination">Destination</label>
                        <input type="text" id="modal_destination" name="destination" required>
                    </div>

                    <div class="modal-field">
                        <label for="modal_trip_type">Type</label>
                        <select id="modal_trip_type" name="trip_type" required>
                            <option value="call-in">Call-in</option>
                            <option value="reservation">Reservation</option>
                        </select>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="modal-btn btn-save">Save Changes</button>
                    <button type="button" class="modal-btn btn-delete" onclick="deleteTrip()">Delete Trip</button>
                    <button type="button" class="modal-btn btn-cancel" onclick="closeModal()">Cancel</button>
                </div>
            </form>
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

        // Fill form from reservation
        function fillFromReservation(reservation) {
            document.getElementById('reservation_id').value = reservation.reservation_id;
            document.getElementById('trip_client').value = reservation.company_name;
            document.getElementById('trip_destination').value = reservation.address_destination;
            document.getElementById('trip_recipient_email').value = reservation.contact_number;
            document.getElementById('trip_recipient_num').value = reservation.email_address;
            document.getElementById('trip_type').value = 'reservation';



            // Scroll to form
            document.getElementById('createTripForm').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            // Highlight the form briefly
            const form = document.getElementById('createTripForm').parentElement;
            form.style.boxShadow = '0 0 20px rgba(0, 153, 0, 0.3)';
            setTimeout(() => {
                form.style.boxShadow = '';
            }, 2000);
        }

        // Delete reservation
        function deleteReservation(reservationId) {
            window.location.href = '../__back-end_processes/process_delete_reservation.php?reservation_id=' + reservationId;
        }

        // Modal Functions
        function openModal(tripData) {
            document.getElementById('modal_trip_id').value = tripData.trip_id;
            document.getElementById('modal_trip_id_display').value = tripData.trip_id;
            document.getElementById('modal_driver').value = tripData.driver;
            document.getElementById('modal_vehicle').value = tripData.vehicle;
            document.getElementById('modal_client').value = tripData.client;
            document.getElementById('modal_destination').value = tripData.destination;
            document.getElementById('modal_trip_type').value = tripData.trip_type;

            document.getElementById('tripModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('tripModal').style.display = 'none';
        }

        function deleteTrip() {
            const tripId = document.getElementById('modal_trip_id').value;
            window.location.href = '../__back-end_processes/process_delete_trip.php?trip_id=' + tripId;

        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('tripModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>

</body>

</html>