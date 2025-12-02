<?php
session_start();
include 'db_connect.php';

// 1. Security Check: Driver Only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'driver') {
    header("Location: login.php");
    exit();
}

$driver_id = $_SESSION['user_id'];

// 2. Handle "Mark as Delivered"
if (isset($_POST['complete_btn'])) {
    $b_id = $_POST['booking_id'];
    $sql = "UPDATE bookings SET status='delivered' WHERE booking_id='$b_id' AND driver_id='$driver_id'";
    $conn->query($sql);
    echo "<script>alert('Great job! Shipment marked as Delivered.'); window.location.href='driver_dashboard.php';</script>";
}

// 3. Fetch My Assigned Jobs (Pending or In Transit)
$sql = "SELECT b.*, u.full_name as customer_name, u.phone as customer_phone
        FROM bookings b 
        JOIN users u ON b.user_id = u.user_id 
        WHERE b.driver_id = '$driver_id' 
        AND b.status != 'delivered'
        ORDER BY b.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Driver Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f0f2f5; padding: 20px; }
        
        .container { max-width: 800px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .logout-btn { background: #e74c3c; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; }
        
        .job-card { background: white; padding: 20px; border-radius: 10px; margin-bottom: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #3498db; }
        .job-card h3 { color: #2c3e50; margin-bottom: 5px; }
        .route { font-size: 18px; font-weight: bold; margin: 10px 0; }
        .details { color: #7f8c8d; font-size: 14px; margin-bottom: 15px; }
        
        .btn-complete { background: #2ecc71; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; width: 100%; }
        .btn-complete:hover { background: #27ae60; }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h2>🚛 Welcome, <?php echo $_SESSION['full_name']; ?></h2>
            <a href="driver_history.php" class="logout-btn">View History</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <h3>Your Active Jobs</h3>
        <br>

        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="job-card">
                    <div style="display:flex; justify-content:space-between;">
                        <h3>Booking #<?php echo $row['booking_id']; ?></h3>
                        <span style="background:#eee; padding:5px 10px; border-radius:4px; font-size:12px;">
                            <?php echo strtoupper($row['status']); ?>
                        </span>
                    </div>

                    <div class="route">
                        📍 <?php echo $row['pickup_address']; ?> <br>
                        ⬇ <br>
                        🏁 <?php echo $row['dropoff_address']; ?>
                    </div>

                    <div class="details">
                        <strong>Customer:</strong> <?php echo $row['customer_name']; ?> <br>
                        <strong>Phone:</strong> <?php echo $row['customer_phone']; ?> <br>
                        <strong>Package:</strong> <?php echo ucfirst($row['parcel_type']); ?> (<?php echo ucfirst($row['service_tier']); ?>)
                    </div>

                    <form method="POST">
                        <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                        <button type="submit" name="complete_btn" class="btn-complete">
                            <i class="fas fa-check-circle"></i> Mark as Delivered
                        </button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align:center; padding:50px; color:#777;">
                <i class="fas fa-mug-hot" style="font-size:50px; margin-bottom:10px;"></i>
                <p>No active jobs right now. Take a break!</p>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>