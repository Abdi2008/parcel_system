<?php
session_start();
include 'db_connect.php';

// 1. Security Check: Admin Only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// 2. Fetch Stats
// Count Total Users
$user_query = "SELECT COUNT(*) as total FROM users WHERE role='customer'";
$user_res = $conn->query($user_query);
$total_users = $user_res->fetch_assoc()['total'];

// Count Active Deliveries (Pending or In Transit)
$active_query = "SELECT COUNT(*) as total FROM bookings WHERE status IN ('pending', 'in_transit')";
$active_res = $conn->query($active_query);
$active_deliveries = $active_res->fetch_assoc()['total'];

// Calculate Total Revenue
$rev_query = "SELECT SUM(total_price) as total FROM bookings";
$rev_res = $conn->query($rev_query);
$total_revenue = $rev_res->fetch_assoc()['total'];

// 3. Fetch All Bookings (Joined with Users table to get customer names)
$sql = "SELECT bookings.*, users.full_name 
        FROM bookings 
        JOIN users ON bookings.user_id = users.user_id 
        ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - IPMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Keeping your existing styles */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f0f2f5; }
        
        .sidebar { width: 250px; background-color: #2c3e50; color: white; display: flex; flex-direction: column; padding: 20px; }
        .logo { font-size: 24px; font-weight: bold; margin-bottom: 40px; }
        .menu a { text-decoration: none; color: #bdc3c7; display: block; padding: 15px; margin-bottom: 10px; border-radius: 8px; transition: 0.3s; }
        .menu a:hover, .menu a.active { background-color: #34495e; color: white; padding-left: 20px; }
        .menu i { margin-right: 10px; }
        
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        
        .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .stat-card h3 { font-size: 36px; color: #2c3e50; }
        .stat-card p { color: #7f8c8d; }
        .stat-card i { font-size: 40px; opacity: 0.2; }
        
        /* Table Styles */
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; font-weight: 600; color: #2c3e50; }
        
        /* Action Form Styles */
        .status-select { padding: 5px; border-radius: 4px; border: 1px solid #ddd; }
        .btn-update { padding: 5px 10px; background: #2980b9; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-update:hover { background: #3498db; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo">Admin Panel</div>
        <div class="menu">
            <a href="#" class="active"><i class="fas fa-chart-line"></i> Overview</a>
            <a href="#"><i class="fas fa-boxes"></i> All Shipments</a>
            <a href="#"><i class="fas fa-users"></i> Users</a>
            <a href="logout.php" style="margin-top: auto; color: #e74c3c;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <h2>Administrator Overview</h2>
        <br>

        <div class="card-grid">
            <div class="stat-card">
                <div>
                    <h3><?php echo $total_users; ?></h3>
                    <p>Total Customers</p>
                </div>
                <i class="fas fa-users" style="color: #3498db;"></i>
            </div>
            <div class="stat-card">
                <div>
                    <h3><?php echo $active_deliveries; ?></h3>
                    <p>Active Deliveries</p>
                </div>
                <i class="fas fa-truck" style="color: #e67e22;"></i>
            </div>
            <div class="stat-card">
                <div>
                    <h3>KES <?php echo number_format($total_revenue); ?></h3>
                    <p>Total Revenue</p>
                </div>
                <i class="fas fa-wallet" style="color: #2ecc71;"></i>
            </div>
        </div>

        <h3>Recent Bookings</h3>
        <br>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Route</th>
                    <th>Current Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['booking_id']; ?></td>
                        <td>
                            <strong><?php echo $row['full_name']; ?></strong><br>
                            <small>Tier: <?php echo ucfirst($row['service_tier']); ?></small>
                        </td>
                        <td>
                            <?php echo $row['pickup_address']; ?> ➝ <br>
                            <?php echo $row['dropoff_address']; ?>
                        </td>
                        <td style="font-weight:bold; color: #555;">
                            <?php echo strtoupper(str_replace('_', ' ', $row['status'])); ?>
                        </td>
                        <td>
                            <form action="update_status.php" method="POST" style="display:flex; gap:5px;">
                                <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                <select name="status" class="status-select">
                                    <option value="pending" <?php if($row['status']=='pending') echo 'selected'; ?>>Pending</option>
                                    <option value="in_transit" <?php if($row['status']=='in_transit') echo 'selected'; ?>>In Transit</option>
                                    <option value="delivered" <?php if($row['status']=='delivered') echo 'selected'; ?>>Delivered</option>
                                    <option value="cancelled" <?php if($row['status']=='cancelled') echo 'selected'; ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_btn" class="btn-update">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>