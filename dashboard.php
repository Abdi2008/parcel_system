<?php
session_start();
include 'db_connect.php';

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['full_name'];

// 2. Fetch Real Stats
// Count Active (Pending or In Transit)
$active_sql = "SELECT COUNT(*) as total FROM bookings WHERE user_id='$user_id' AND status IN ('pending', 'in_transit')";
$active_res = $conn->query($active_sql);
$active_count = $active_res->fetch_assoc()['total'];

// Count Completed
$comp_sql = "SELECT COUNT(*) as total FROM bookings WHERE user_id='$user_id' AND status = 'delivered'";
$comp_res = $conn->query($comp_sql);
$comp_count = $comp_res->fetch_assoc()['total'];

// Calculate Total Spent
$spent_sql = "SELECT SUM(total_price) as total FROM bookings WHERE user_id='$user_id' AND status != 'cancelled'";
$spent_res = $conn->query($spent_sql);
$total_spent = $spent_res->fetch_assoc()['total'];
if(!$total_spent) $total_spent = 0;

// 3. Fetch Recent 3 Orders (Mini History)
$recent_sql = "SELECT * FROM bookings WHERE user_id='$user_id' ORDER BY created_at DESC LIMIT 3";
$recent_res = $conn->query($recent_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - IPMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { display: flex; height: 100vh; background-color: beige; }
        
        /* Sidebar */
        .sidebar { width: 250px; background-color: rgb(117, 117, 91); color: white; display: flex; flex-direction: column; padding: 20px; }
        .logo { font-size: 24px; font-weight: bold; margin-bottom: 40px; color: #ecf0f1; }
        .menu a { text-decoration: none; color: #bdc3c7; display: block; padding: 15px; margin-bottom: 10px; border-radius: 8px; transition: 0.3s; }
        .menu a:hover, .menu a.active { background-color: #34495e; color: white; padding-left: 20px; }
        .menu i { margin-right: 10px; width: 20px; }
        
        /* Main Content */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        
        /* Hero Section */
        .hero-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(118, 75, 162, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .hero-text h2 { font-size: 28px; margin-bottom: 5px; }
        .hero-text p { opacity: 0.9; }
        .hero-btn { background: white; color: #764ba2; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: bold; transition: 0.3s; }
        .hero-btn:hover { background: #f0f0f0; transform: translateY(-2px); }

        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; border-bottom: 4px solid transparent; }
        .stat-card:hover { transform: translateY(-3px); transition: 0.3s; }
        
        .stat-card.blue { border-bottom-color: #3498db; }
        .stat-card.green { border-bottom-color: #2ecc71; }
        .stat-card.orange { border-bottom-color: #e67e22; }

        .stat-card h3 { font-size: 32px; color: #2c3e50; }
        .stat-card p { color: #7f8c8d; font-size: 14px; text-transform: uppercase; font-weight: 600; }
        .stat-card i { font-size: 40px; opacity: 0.2; }

        /* Recent Orders Section */
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .view-all { color: #3498db; text-decoration: none; font-weight: 600; font-size: 14px; }
        
        .table-card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: #2c3e50; font-size: 13px; text-transform: uppercase; }
        tr:last-child td { border-bottom: none; }
        
        .status-dot { height: 10px; width: 10px; background-color: #bbb; border-radius: 50%; display: inline-block; margin-right: 5px; }
        .status-pending { background-color: #f1c40f; }
        .status-transit { background-color: #3498db; }
        .status-delivered { background-color: #2ecc71; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo"><i class="fas fa-box-open"></i> IPMS</div>
        <!-- <div style="text-align: center; margin-bottom: 20px;">
            <img src="https://ui-avatars.com/api/?name=<?php echo $user_name; ?>&background=random&color=fff&size=128" 
            style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid #34495e; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <p style="margin-top: 10px; font-size: 14px; color: #bdc3c7;">Customer</p>
        </div> -->
        <div class="menu">
            <a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a>
            <a href="book_parcel.php"><i class="fas fa-shipping-fast"></i> Book Parcel</a>
            <a href="my_orders.php"><i class="fas fa-list"></i> My Orders</a>
            <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
            <a href="logout.php" style="margin-top: auto; color: #a03a2fff;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        
        <div class="hero-banner">
            <div class="hero-text">
                <h2>Welcome back, <?php echo explode(' ', $user_name)[0]; ?>! 👋</h2>
                <p>Track your shipments or book a new delivery instantly.</p>
            </div>
            <a href="book_parcel.php" class="hero-btn"><i class="fas fa-plus"></i> New Shipment</a>
        </div>

        <div class="stats-grid">
            <div class="stat-card orange">
                <div>
                    <h3><?php echo $active_count; ?></h3>
                    <p>Active Orders</p>
                </div>
                <i class="fas fa-box-open card-icon" style="color: #e67e22;"></i>
            </div>
            <div class="stat-card green">
                <div>
                    <h3><?php echo $comp_count; ?></h3>
                    <p>Delivered</p>
                </div>
                <i class="fas fa-check-circle card-icon" style="color: #2ecc71;"></i>
            </div>
            <div class="stat-card blue">
                <div>
                    <h3><small style="font-size:18px;">KES</small> <?php echo number_format($total_spent); ?></h3>
                    <p>Total Spent</p>
                </div>
                <i class="fas fa-wallet card-icon" style="color: #3498db;"></i>
            </div>
        </div>

        <div class="section-header">
            <h3>Recent Shipments</h3>
            <a href="my_orders.php" class="view-all">View All</a>
        </div>

        <div class="table-card">
            <?php if ($recent_res->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Destination</th>
                            <th>Service</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $recent_res->fetch_assoc()): ?>
                            <?php 
                                // Status Logic for Dot Color
                                $status = $row['status'];
                                $dotClass = '';
                                if($status == 'pending') $dotClass = 'status-pending';
                                elseif($status == 'in_transit') $dotClass = 'status-transit';
                                elseif($status == 'delivered') $dotClass = 'status-delivered';
                            ?>
                            <tr>
                                <td>#<?php echo $row['booking_id']; ?></td>
                                <td><?php echo $row['dropoff_address']; ?></td>
                                <td><?php echo ucfirst($row['service_tier']); ?></td>
                                <td style="text-transform: capitalize;">
                                    <span class="status-dot <?php echo $dotClass; ?>"></span> 
                                    <?php echo str_replace('_', ' ', $status); ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="padding: 20px; text-align: center; color: #999;">
                    No recent activity.
                </div>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>