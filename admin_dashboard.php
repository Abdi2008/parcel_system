<?php
session_start();
// 1. Security Check: Is the user logged in? AND Is the user an Admin?
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); // Kick them out if they are not an admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - IPMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">   
    <!-- /* Font Awesome for icons */ -->
    <style>
        /* Reusing your nice dashboard styles */
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
        .status-badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .pending { background: #ffeaa7; color: #d35400; }
        .delivered { background: #55efc4; color: #00b894; }
        .main-content h2{
            color: #637c96ff;
            font-size: 23pt
        }
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
                    <h3>1</h3>
                    <p>Total Users</p>
                </div>
                <i class="fas fa-users" style="color: #3498db;"></i>
            </div>
            <div class="stat-card">
                <div>
                    <h3>0</h3>
                    <p>Active Deliveries</p>
                </div>
                <i class="fas fa-truck" style="color: #e67e22;"></i>
            </div>
            <div class="stat-card">
                <div>
                    <h3>$0</h3>
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
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Route</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#1001</td>
                    <td>Abdirahman</td>
                    <td>Nairobi -> Mombasa</td>
                    <td><span class="status-badge pending">Pending</span></td>
                    <td><button>View</button></td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>