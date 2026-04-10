<?php
include '../__back-end_processes/db_connect.php';
session_start();

// If not logged in OR not driver
if (!isset($_SESSION['account_id']) || $_SESSION['role'] != 1) {
    header("Location: ../_user_interface/user_signup.php");
    exit();
}

$logged_in_username = null;
$logged_in_fullname = null;

if (isset($_SESSION['account_id'])) {
    $account_id = $_SESSION['account_id'];
    $query = "SELECT username, fullname FROM account WHERE account_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $account_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $logged_in_username = $row['username'];
        $logged_in_fullname = $row['fullname'];
    }
    $stmt->close();
}

$query = "SELECT * FROM trips WHERE driver = ? AND status != 'completed'";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $logged_in_fullname);
$stmt->execute();
$result_Job = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Driver - Assigned Jobs</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="icon" type="image/x-icon" href="../images/favicon.jpg">
<link rel="stylesheet" href="../css/driver_style.css">

<style>
/* ===============================
   MAIN CONTENT
================================ */
.main-content {
    padding: 30px;
    background-color: #f4f6f9;
    min-height: 100vh;
}

.main-content h1 {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 25px;
    color: #1f2c3d;
}

/* ===============================
   JOB LIST
================================ */
.job-list-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(700px, 1fr));
    gap: 25px;
}

/* ===============================
   JOB CARD
================================ */
.job-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

/* ===============================
   GRID LIKE IMAGE
================================ */
.job-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

/* ===============================
   FIELD BOX
================================ */
.job-field {
    border: 2px solid #000;
    padding: 16px;
    min-height: 80px;
}

.job-field label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
}

.job-field span {
    font-size: 16px;
    font-weight: 500;
}

/* ===============================
   BUTTON
================================ */
.mark-progress-btn {
    margin-top: 20px;
    width: 100%;
    padding: 14px;
    font-size: 16px;
    font-weight: 600;
    border: none;
    border-radius: 6px;
    background-color: #0d6efd;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s;
}

.mark-progress-btn:hover {
    background-color: #0056b3;
}

.mark-complete-btn {
    margin-top: 20px;
    width: 100%;
    padding: 14px;
    font-size: 16px;
    font-weight: 600;
    border: none;
    border-radius: 6px;
    background-color: #28a745;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s;
}

.mark-complete-btn:hover {
    background-color: #218838;
}

.btn-disabled {
    background-color: #6c757d;
    cursor: not-allowed;
    opacity: 0.65;
}

.btn-disabled:hover {
    background-color: #6c757d;
}

/* ===============================
   EMPTY STATE
================================ */
.empty-state {
    background: #fff;
    border-radius: 10px;
    padding: 60px 40px;
    text-align: center;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

.empty-state svg {
    width: 80px;
    height: 80px;
    margin-bottom: 20px;
    opacity: 0.3;
}

.empty-state h2 {
    font-size: 24px;
    color: #333;
    margin-bottom: 10px;
}

.empty-state p {
    font-size: 16px;
    color: #666;
    line-height: 1.6;
}

/* ===============================
   RESPONSIVE
================================ */
@media (min-width: 768px) {
    .main-content {
        margin-left: 260px;
    }
}

@media (max-width: 768px) {
    .job-grid {
        grid-template-columns: 1fr;
    }
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
    <div class="mobile-header-title">Driver Panel</div>
</div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <button id="sidebar-close-btn">&times;</button>

    <div class="sidebar-header">
        GNBTL
        <?php if ($logged_in_username): ?>
            <div class="username-display">
                User: <?php echo htmlspecialchars($logged_in_username); ?>
            </div>
        <?php endif; ?>
    </div>

    <nav>
        <ul class="nav-links">
            <li><a href="../_driver_interface/driver_home.php">Dashboard</a></li>
            <li><a href="../_driver_interface/driver_Announcement.php">Announcement</a></li>
            <li><a href="../_driver_interface/driver_Delivery.php">Delivery</a></li>
            <li><a href="../_driver_interface/driver_Assigned_Job.php" class="active">Assigned Job</a></li>
        </ul>
    </nav>

    <div class="logout-container">
        <form action="../__back-end_processes/auth_logout.php" method="post">
            <button class="logout-btn">Log out</button>
        </form>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <h1>Assigned Jobs</h1>

    <?php if ($result_Job && $result_Job->num_rows > 0): ?>
        <div class="job-list-container">
            <?php while ($row = $result_Job->fetch_assoc()): ?>
            <div class="job-card">

                <div class="job-grid">

                    <div class="job-field">
                        <label>Trip ID</label>
                        <span><?= htmlspecialchars($row['trip_id']) ?></span>
                    </div>

                    <div class="job-field">
                        <label>Assigned Vehicle</label>
                        <span><?= htmlspecialchars($row['vehicle']) ?></span>
                    </div>

                    <div class="job-field">
                        <label>Driver</label>
                        <span><?= htmlspecialchars($row['driver']) ?></span>
                    </div>

                    <div class="job-field">
                        <label>Destination</label>
                        <span><?= htmlspecialchars($row['destination']) ?></span>
                    </div>

                    <div class="job-field">
                        <label>Status</label>
                        <span><?= htmlspecialchars($row['status']) ?></span>
                    </div>

                    <div class="job-field">
                        <label>Date</label>
                        <span><?= htmlspecialchars($row['created_at']) ?></span>
                    </div>

                </div>

                <?php if ($row['status'] == 'pending' || $row['status'] == 'Pending'): ?>
                    <form action="../__back-end_processes/process_mark_inprogress.php" method="POST">
                        <input type="hidden" name="trip_id" value="<?php echo $row['trip_id']; ?>">
                        <input type="hidden" name="driver_name" value="<?php echo htmlspecialchars($row['driver']); ?>">
                        <input type="hidden" name="vehicle_name" value="<?php echo htmlspecialchars($row['vehicle']); ?>">
                        <button class="mark-progress-btn" type="submit">Mark as In Progress</button>
                    </form>

                <?php elseif ($row['status'] == 'in-progress' || $row['status'] == 'In Progress'): ?>
                    <form action="../__back-end_processes/process_mark_complete.php" method="POST">
                        <input type="hidden" name="trip_id" value="<?php echo $row['trip_id']; ?>">
                        <input type="hidden" name="driver_name" value="<?php echo htmlspecialchars($row['driver']); ?>">
                        <input type="hidden" name="vehicle_name" value="<?php echo htmlspecialchars($row['vehicle']); ?>">
                        <button class="mark-complete-btn" type="submit">Mark as Complete</button>
                    </form>
                <?php else: ?>
                    <button class="mark-progress-btn btn-disabled" disabled>Completed</button>
                <?php endif; ?>

            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h2>No Assigned Job Yet</h2>
            <p>You currently have no jobs assigned to you. Check back later or contact your administrator for more information.</p>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const menuBtn = document.getElementById("menu-toggle-btn");
    const closeBtn = document.getElementById("sidebar-close-btn");
    const sidebar = document.getElementById("sidebar");

    menuBtn.addEventListener("click", () => sidebar.classList.add("open"));
    closeBtn.addEventListener("click", () => sidebar.classList.remove("open"));
});
</script>

</body>
</html>