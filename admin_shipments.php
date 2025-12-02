<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

// 1. Handle Driver Assignment & Status Update
if (isset($_POST['assign_btn'])) {
    $b_id = $_POST['booking_id'];
    $d_id = $_POST['driver_id']; // This might be empty if they didn't select one
    $status = $_POST['status'];

    // If a driver is selected, update driver_id, otherwise keep it same (or NULL)
    // We use a smart SQL query here
    if (!empty($d_id)) {
        $sql = "UPDATE bookings SET status='$status', driver_id='$d_id' WHERE booking_id='$b_id'";
    } else {
        $sql = "UPDATE bookings SET status='$status' WHERE booking_id='$b_id'";
    }
    $conn->query($sql);
}

// 2. Fetch All Bookings + Customer Name + Driver Name
$sql = "SELECT b.*, u.full_name as customer_name, d.full_name as driver_name 
        FROM bookings b 
        JOIN users u ON b.user_id = u.user_id 
        LEFT JOIN users d ON b.driver_id = d.user_id 
        ORDER BY b.created_at DESC";
$result = $conn->query($sql);

// 3. Fetch List of Drivers for the Dropdown
$driver_sql = "SELECT * FROM users WHERE role='driver'";
$driver_res = $conn->query($driver_sql);
$drivers = [];
while($d = $driver_res->fetch_assoc()) {
    $drivers[] = $d;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Shipments</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Minimal Styles for Table */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f0f2f5; }
        .sidebar { width: 250px; background-color: #2c3e50; color: white; display: flex; flex-direction: column; padding: 20px; }
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        
        .menu a { text-decoration: none; color: #bdc3c7; display: block; padding: 15px; margin-bottom: 10px; border-radius: 8px; }
        .menu a:hover, .menu a.active { background-color: #34495e; color: white; }

        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); font-size: 14px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: #2c3e50; }
        
        select { padding: 5px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 5px 10px; background: #2980b9; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo" style="margin-bottom:20px; font-weight:bold; font-size:20px;">Admin Panel</div>
        <div class="menu">
            <a href="admin_dashboard.php">Overview</a>
            <a href="admin_dashboard.php" class="active">All Shipments</a>
            <a href="logout.php" style="color:#e74c3c;">Logout</a>
        </div>
    </div>

    <div class="main-content">
        <h2>🚚 Manage Shipments & Drivers</h2>
        <br>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Details</th>
                    <th>Assigned Driver</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $row['booking_id']; ?></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td>
                        <?php echo $row['pickup_address']; ?> ➝ <?php echo $row['dropoff_address']; ?><br>
                        <small>Type: <?php echo $row['parcel_type']; ?> | Tier: <?php echo $row['service_tier']; ?></small>
                    </td>
                    <td>
                        <span style="font-weight:bold; color: #27ae60;">
                            <?php echo $row['driver_name'] ? $row['driver_name'] : '<span style="color:red;">Unassigned</span>'; ?>
                        </span>
                    </td>
                    <form method="POST">
                        <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                        <td>
                            <select name="status">
                                <option value="pending" <?php if($row['status']=='pending') echo 'selected'; ?>>Pending</option>
                                <option value="in_transit" <?php if($row['status']=='in_transit') echo 'selected'; ?>>In Transit</option>
                                <option value="delivered" <?php if($row['status']=='delivered') echo 'selected'; ?>>Delivered</option>
                                <option value="cancelled" <?php if($row['status']=='cancelled') echo 'selected'; ?>>Cancelled</option>
                            </select>
                        </td>
                        <td>
                            <select name="driver_id">
                                <option value="">-- Assign Driver --</option>
                                <?php foreach($drivers as $d): ?>
                                    <option value="<?php echo $d['user_id']; ?>" 
                                        <?php if($row['driver_id'] == $d['user_id']) echo 'selected'; ?>>
                                        <?php echo $d['full_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" name="assign_btn">Update</button>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>