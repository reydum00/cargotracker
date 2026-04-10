<?php
include '../__back-end_processes/db_connect.php';
session_start();

// If not logged in OR not driver, redirect away
if (!isset($_SESSION['account_id']) || $_SESSION['role'] != 2) {
    header("Location: ../_user_interface/user_signup.php");
    exit();
}

// Edit Account
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_account_id'])) {
    $id = $_POST['edit_account_id'];
    $fullname = $_POST['edit_fullname'];
    $username = $_POST['edit_username'];
    $email = $_POST['edit_email'];

    $stmt = $conn->prepare("UPDATE account SET fullname = ?, username = ?, email = ? WHERE account_id = ?");
    $stmt->bind_param("sssi", $fullname, $username, $email, $id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: admin_accounts.php");
    exit();
}

// Archive Account
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['archive_account_id'])) {
    $archive_id = $_POST['archive_account_id'];

    $stmt = $conn->prepare("UPDATE account SET is_archived = 1 WHERE account_id = ?");
    $stmt->bind_param("i", $archive_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: admin_accounts.php");
    exit();
}

$query = "SELECT * FROM account WHERE role = 1 AND is_archived = 0";
$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GNBTL Admin - Driver Management</title>
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

    <div class="main-content-account">

        <h1>Driver Account Management</h1>

        <div class="dashboard-columns-account">

            <div class="dashboard-card-account">
                <h2>Create New Driver</h2>
                <form method="POST" action="../__back-end_processes/auth_signup_driver.php">
                    <div class="form-group-account">
                        <label for="full_name" class="form-label-account">Full Name</label>
                        <input name="fullname" type="text" id="full_name" class="form-input-account" placeholder="F_Name, L_Name" required>
                    </div>
                    <div class="form-group-account">
                        <label for="email" class="form-label-account">Email</label>
                        <input name="email" type="email" id="email" class="form-input-account" placeholder="driver@example.com" required>
                    </div>
                    <div class="form-group-account">
                        <label for="username" class="form-label-account">Username</label>
                        <input name="username" type="text" id="username" class="form-input-account" placeholder="e.g., John Doe" required>
                    </div>
                    <div class="form-group-account">
                        <label for="password" class="form-label-account">Password</label>
                        <input name="password" type="password" id="password" class="form-input-account" placeholder="Set a temporary password" required>
                    </div>
                    <button type="submit" class="form-button-account">Create Driver Account</button>
                </form>
            </div>

            <div class="dashboard-card-account">
                <h2>Driver List</h2>
                <div class="card-content-table-wrapper-account">
                    <table class="content-table-account">
                        <thead>
                            <tr>
                                <th>Driver Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <button type="button" class="action-btn-account edit" onclick="openEditModal(<?php echo $row['account_id']; ?>, '<?php echo htmlspecialchars(addslashes($row['fullname'])); ?>', '<?php echo htmlspecialchars(addslashes($row['username'])); ?>', '<?php echo htmlspecialchars(addslashes($row['email'])); ?>')" style="display:inline-block; margin-right: 5px;">Edit</button>
                                        
                                        <form method="POST" action="" style="display:inline-block;">
                                            <input type="hidden" name="archive_account_id" value="<?php echo $row['account_id']; ?>">
                                            <button type="submit" class="action-btn-account archive" onclick="return confirm('Are you sure you want to archive this account?');">Archive</button>
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

    <div id="editAccountModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeEditModal()">&times;</span>
            <h2 style="margin-bottom: 15px;">Edit Driver Account</h2>
            <form method="POST" action="">
                <input type="hidden" name="edit_account_id" id="edit_account_id">
                
                <div class="form-group-account">
                    <label for="edit_fullname" class="form-label-account">Full Name</label>
                    <input name="edit_fullname" type="text" id="edit_fullname" class="form-input-account" required>
                </div>

                <div class="form-group-account" style="margin-top: 15px;">
                    <label for="edit_username" class="form-label-account">Username</label>
                    <input name="edit_username" type="text" id="edit_username" class="form-input-account" required>
                </div>

                <div class="form-group-account" style="margin-top: 15px;">
                    <label for="edit_email" class="form-label-account">Email</label>
                    <input name="edit_email" type="email" id="edit_email" class="form-input-account" required>
                </div>

                <button type="submit" class="form-button-account" style="margin-top: 20px;">Save Changes</button>
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

        // Modal Logic
        const modal = document.getElementById("editAccountModal");

        function openEditModal(id, fullname, username, email) {
            document.getElementById("edit_account_id").value = id;
            document.getElementById("edit_fullname").value = fullname;
            document.getElementById("edit_username").value = username;
            document.getElementById("edit_email").value = email;
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