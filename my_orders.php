<?php
session_start();
include 'db_connect.php';

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Fetch User's Orders
$sql = "SELECT * FROM bookings WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders - IPMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Reusing Sidebar Layout */
        body { display: flex; height: 100vh; background-color: #f0f2f5; margin: 0; }
        .sidebar { width: 250px; background-color: #2c3e50; color: white; display: flex; flex-direction: column; padding: 20px; }
        .logo { font-size: 24px; font-weight: bold; margin-bottom: 40px; color: #ecf0f1; }
        .menu a { text-decoration: none; color: #bdc3c7; display: block; padding: 15px; margin-bottom: 10px; border-radius: 8px; transition: 0.3s; }
        .menu a:hover, .menu a.active { background-color: #34495e; color: white; padding-left: 20px; }
        .menu i { margin-right: 10px; width: 20px; }
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }

        /* Table Styles */
        .table-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: #2c3e50; font-weight: 600; }
        tr:hover { background-color: #f9f9f9; }

        /* Status Badges */
        .badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .badge-pending { background-color: #fff3cd; color: #856404; }
        .badge-transit { background-color: #cce5ff; color: #004085; }
        .badge-delivered { background-color: #d4edda; color: #155724; }
        .badge-cancelled { background-color: #f8d7da; color: #721c24; }
        /* .main-content h2{
            color: #637c96ff;
            background-color: aquamarine;
        } */
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo"><i class="fas fa-box-open"></i> IPMS</div>
        <div class="menu">
            <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
            <a href="book_parcel.php"><i class="fas fa-shipping-fast"></i> Book Parcel</a>
            <a href="#" class="active"><i class="fas fa-list"></i> My Orders</a>
            <a href="logout.php" style="margin-top: auto; color: #e74c3c;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <h2>📦 My Order History</h2>
        <br>

        <div class="table-container">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Route</th>
                            <th>Type</th>
                            <th>Tier</th>
                            <th>Cost</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['booking_id']; ?></td>
                                <td>
                                    <strong>From:</strong> <?php echo $row['pickup_address']; ?><br>
                                    <small style="color:#777;">To: <?php echo $row['dropoff_address']; ?></small>
                                </td>
                                <td><?php echo ucfirst($row['parcel_type']); ?></td>
                                <td>
                                    <?php if($row['service_tier'] == 'deluxe') { echo '🚀 Deluxe'; } else { echo 'Standard'; } ?>
                                </td>
                                <td>KES <?php echo number_format($row['total_price']); ?></td>
                                <td>
                                    <?php 
                                        $status = $row['status'];
                                        $badgeClass = 'badge-pending';
                                        if($status == 'in_transit') $badgeClass = 'badge-transit';
                                        if($status == 'delivered') $badgeClass = 'badge-delivered';
                                        if($status == 'cancelled') $badgeClass = 'badge-cancelled';
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>"><?php echo ucfirst(str_replace('_', ' ', $status)); ?></span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align:center; padding: 20px; color: #777;">You haven't booked any parcels yet.</p>
                <div style="text-align:center;">
                    <a href="book_parcel.php" style="padding:10px 20px; background:#2980b9; color:white; text-decoration:none; border-radius:5px;">Book Your First Parcel</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>