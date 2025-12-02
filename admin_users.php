<?php
session_start();
include 'db_connect.php';

// 1. Security Check: Admin Only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

// 2. Fetch All Users
$sql = "SELECT * FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Shared Admin Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f0f2f5; }
        .sidebar { width: 250px; background-color: #2c3e50; color: white; display: flex; flex-direction: column; padding: 20px; }
        .logo { font-size: 20px; font-weight: bold; margin-bottom: 30px; }
        .menu a { text-decoration: none; color: #bdc3c7; display: block; padding: 15px; margin-bottom: 10px; border-radius: 8px; }
        .menu a:hover, .menu a.active { background-color: #34495e; color: white; }
        
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }

        /* User Table Styles */
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: #2c3e50; }
        
        /* Role Badges */
        .role-badge { padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .role-admin { background: #fad390; color: #e55039; }
        .role-driver { background: #b8e994; color: #079992; }
        .role-customer { background: #dff9fb; color: #130f40; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo">Admin Panel</div>
        <div class="menu">
            <a href="admin_dashboard.php">Overview</a>
            <a href="admin_shipments.php">All Shipments</a>
            <a href="admin_users.php" class="active">Users</a> <a href="logout.php" style="color:#e74c3c;">Logout</a>
        </div>
    </div>

    <div class="main-content">
        <h2>👥 User Directory</h2>
        <br>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Joined Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['user_id']; ?></td>
                        <td>
                            <strong><?php echo $row['full_name']; ?></strong>
                        </td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td>
                            <?php 
                                $role = $row['role'];
                                $badgeClass = 'role-customer'; // Default
                                if($role == 'admin') $badgeClass = 'role-admin';
                                if($role == 'driver') $badgeClass = 'role-driver';
                            ?>
                            <span class="role-badge <?php echo $badgeClass; ?>">
                                <?php echo $role; ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>