<?php
session_start();
// Security Check: If user is NOT logged in, kick them back to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* A little extra style just for the dashboard */
        .dashboard-container { width: 80%; max-width: 800px; text-align: left; }
        .nav { background: #333; padding: 15px; color: white; display: flex; justify-content: space-between; }
        .nav a { color: white; text-decoration: none; margin-left: 20px; }
    </style>
</head>
<body>
    <div class="container dashboard-container">
        <div class="nav">
            <span>Welcome, <?php echo $_SESSION['full_name']; ?></span>
            <div>
                <a href="book_parcel.php">Book Parcel</a>
                <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
                <a href="my_orders.php">My Orders</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>

        <h3>Your Dashboard</h3>
        <p>Welcome to the Instant Parcel Management System.</p>
        <p>Select an option from the menu above to get started.</p>
    </div>
</body>
</html>