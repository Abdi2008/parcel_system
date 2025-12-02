<?php
session_start();
include 'db_connect.php';

// 1. Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'driver') {
    header("Location: login.php");
    exit();
}

$driver_id = $_SESSION['user_id'];

// 2. Fetch COMPLETED Jobs
$sql = "SELECT b.*, u.full_name as customer_name 
        FROM bookings b 
        JOIN users u ON b.user_id = u.user_id 
        WHERE b.driver_id = '$driver_id' 
        AND b.status = 'delivered'
        ORDER BY b.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery History</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f0f2f5; padding: 20px; }
        
        .container { max-width: 800px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .back-btn { background: #34495e; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; }
        
        /* Table Styles */
        .history-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: #2c3e50; }
        .badge-done { background-color: #d4edda; color: #155724; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h2>📜 Work History</h2>
            <a href="driver_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>

        <div class="history-card">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Route</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Earned (Est)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['booking_id']; ?></td>
                                <td>
                                    <?php echo $row['pickup_address']; ?> ➝ <br>
                                    <?php echo $row['dropoff_address']; ?>
                                </td>
                                <td><?php echo $row['customer_name']; ?></td>
                                <td><span class="badge-done">COMPLETED</span></td>
                                <td style="color: #27ae60; font-weight: bold;">
                                    KES <?php echo number_format($row['total_price'] * 0.20); ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align:center; color:#777; padding:20px;">No completed deliveries yet.</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>