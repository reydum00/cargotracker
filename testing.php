<?php
session_start();
include '__back-end_processes\db_connect.php';

// Retrieve announcements
$query = "SELECT * FROM announcements WHERE deleted_at IS NULL AND status = 'active' ORDER BY date_announced DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Announcements</title>
</head>
<body>
    <h1>Announcements</h1>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="announcement <?php echo $row['priority_level']; ?>">
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo htmlspecialchars($row['announcement_message']); ?></p>
            <small><?php echo $row['date_announced']; ?></small>
        </div>
    <?php endwhile; ?>
    
</body>
</html>