<?php
include '../__back-end_processes/db_connect.php';
session_start();

// If not logged in OR not driver, redirect away
if (!isset($_SESSION['account_id']) || $_SESSION['role'] != 2) {
    header("Location: ../_user_interface/user_signup.php");
    exit();
}

// Edit Vehicle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_vehicle_name'])) {
    $old_name = $_POST['old_vehicle_name'];
    $new_name = $_POST['new_vehicle_name'];
    $type = $_POST['vehicle_type'];

    $stmt = $conn->prepare("UPDATE vehicles SET vehicle_name = ?, vehicle_type = ? WHERE vehicle_name = ?");
    $stmt->bind_param("sss", $new_name, $type, $old_name);
    $stmt->execute();
    $stmt->close();
    
    header("Location: admin_vehicles.php");
    exit();
}

// Archive Vehicle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['archive_vehicle_name'])) {
    $archive_name = $_POST['archive_vehicle_name'];

    $stmt = $conn->prepare("UPDATE vehicles SET is_archived = 1 WHERE vehicle_name = ?");
    $stmt->bind_param("s", $archive_name);
    $stmt->execute();
    $stmt->close();
    
    header("Location: admin_vehicles.php");
    exit();
}

$query = "SELECT * FROM vehicles WHERE is_archived = 0";
$result = mysqli_query($conn, $query);
//2nd re-query for the 2nd loop
$result1 = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial  -scale=1.0">
    <title>GNBTL Admin - Vehicle Management</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.jpg">
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        /* Basic Modal Styles */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: #fff; margin: 10% auto; padding: 20px; border-radius: 8px; width: 90%; max-width: 400px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .close-btn { color: #aaa; float: right; font-size: 24px; font-weight: bold; cursor: pointer; }
        .close-btn:hover { color: #000; }
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

    <div class="main-content-vehicle">

        <h1>Vehicle Management</h1>

        <div class="dashboard-columns-vehicle">

            <div class="dashboard-card-vehicle">
                <h2>Add New Vehicle</h2>
                <form method="POST" action="../__back-end_processes\processs_add_vehicle.php">
                    <div class="form-group-vehicle">
                        <label for="vehicle_name" class="form-label-vehicle">Vehicle Name</label>
                        <input name="vehicle_name" type="text" id="vehicle_name" class="form-input-vehicle" placeholder="e.g., truck-1" required>
                    </div>

                    <div class="form-group-vehicle">
                        <label for="vehicle_class" class="form-label-vehicle">Class</label>
                        <select id="vehicle_class" class="form-select-vehicle" name="vehicle_type">
                            <option value="rigid">Rigid</option>
                            <option value="trailer">Trailer</option>
                        </select>
                    </div>

                    <button type="submit" class="form-button-vehicle">Add Vehicle</button>
                </form>
            </div>

            <div class="dashboard-card-vehicle">
                <h2>Vehicle Status</h2>
                <div class="card-content-scrollable-vehicle">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="status-info-vehicle">
                            <span class="status-info-name-vehicle"><?php echo htmlspecialchars($row['vehicle_name']); ?></span>
                            <span class="status-badge-vehicle available"><?php echo htmlspecialchars($row['status']); ?></span>
                        </div>
                    <?php endwhile; ?>

                </div>
            </div>

            <div class="dashboard-card-vehicle" style="grid-column: 1 / -1;">
                <h2>Vehicle List</h2>
                <div class="card-content-table-wrapper-vehicle">
                    <table class="content-table-vehicle">
                        <thead>
                            <tr>
                                <th>Vehicle Name</th>
                                <th>Class</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result1)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['vehicle_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['vehicle_type']); ?></td>
                                    <td>
                                        <button type="button" class="action-btn-vehicle edit" onclick="openEditModal('<?php echo htmlspecialchars($row['vehicle_name']); ?>', '<?php echo htmlspecialchars($row['vehicle_type']); ?>')" style="display:inline-block; margin-right: 5px;">Edit</button>
                                        
                                        <form method="POST" action="" style="display:inline-block;">
                                            <input type="hidden" name="archive_vehicle_name" value="<?php echo htmlspecialchars($row['vehicle_name']); ?>">
                                            <button type="submit" class="action-btn-vehicle archive">Archive</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <div id="editVehicleModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeEditModal()">&times;</span>
            <h2 style="margin-bottom: 15px;">Edit Vehicle</h2>
            <form method="POST" action="">
                <input type="hidden" name="old_vehicle_name" id="edit_old_vehicle_name">
                
                <div class="form-group-vehicle">
                    <label for="edit_vehicle_name" class="form-label-vehicle">Vehicle Name</label>
                    <input name="new_vehicle_name" type="text" id="edit_vehicle_name" class="form-input-vehicle" required>
                </div>

                <div class="form-group-vehicle" style="margin-top: 15px;">
                    <label for="edit_vehicle_class" class="form-label-vehicle">Class</label>
                    <select id="edit_vehicle_class" class="form-select-vehicle" name="vehicle_type">
                        <option value="rigid">Rigid</option>
                        <option value="trailer">Trailer</option>
                        <option value="Rigid">Rigid</option>
                        <option value="Trailer">Trailer</option>
                    </select>
                </div>

                <button type="submit" class="form-button-vehicle" style="margin-top: 20px;">Save Changes</button>
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

        // Modal
        const modal = document.getElementById("editVehicleModal");

        function openEditModal(vehicleName, vehicleType) {
            document.getElementById("edit_old_vehicle_name").value = vehicleName;
            document.getElementById("edit_vehicle_name").value = vehicleName;
            
            // Set dropdown value (case sensitivity)
            const select = document.getElementById("edit_vehicle_class");
            for(let i=0; i < select.options.length; i++) {
                if(select.options[i].value.toLowerCase() === vehicleType.toLowerCase()) {
                    select.selectedIndex = i;
                    break;
                }
            }
            
            modal.style.display = "block";
        }

        function closeEditModal() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                closeEditModal();
            }
        }
    </script>

</body>

</html>