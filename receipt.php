<?php
session_start();
include 'db_connect.php';

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Get the Order ID from URL
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$booking_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// 3. Fetch Order Details (Only if it belongs to this user!)
$sql = "SELECT * FROM bookings WHERE booking_id = '$booking_id' AND user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Order not found or access denied.");
}

$order = $result->fetch_assoc();

// 4. Reconstruct the Pricing Logic (Reverse Engineering)
$base_price = 500;
$tier_price = ($order['service_tier'] == 'deluxe') ? 1000 : 0;
$storage_price = ($order['storage_required'] == 1) ? 500 : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #<?php echo $booking_id; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f0f2f5; font-family: 'Poppins', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .receipt-card { background: white; padding: 40px; border-radius: 10px; width: 400px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-top: 5px solid #2ecc71; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px dashed #eee; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #2c3e50; }
        .label { font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 1px; }
        .value { font-size: 16px; font-weight: 600; color: #333; margin-bottom: 15px; }
        
        .line-item { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; color: #555; }
        .total-row { display: flex; justify-content: space-between; margin-top: 20px; padding-top: 15px; border-top: 2px solid #eee; font-weight: bold; font-size: 18px; color: #2c3e50; }
        
        .btn-print { display: block; width: 100%; padding: 12px; background: #34495e; color: white; text-align: center; text-decoration: none; border-radius: 5px; margin-top: 25px; transition: 0.3s; }
        .btn-print:hover { background: #2c3e50; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #999; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

    <div class="receipt-card">
        <div class="header">
            <div class="logo"><i class="fas fa-check-circle" style="color:#2ecc71;"></i> Payment Receipt</div>
            <p style="color:#7f8c8d; margin-top:5px;">Order #<?php echo $order['booking_id']; ?></p>
        </div>

        <div class="label">From</div>
        <div class="value"><?php echo $order['pickup_address']; ?></div>

        <div class="label">To</div>
        <div class="value"><?php echo $order['dropoff_address']; ?></div>

        <div class="label">Payment Details</div>
        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-top: 5px;">
            <div class="line-item">
                <span>Standard Delivery Base Fare</span>
                <span>KES <?php echo number_format($base_price); ?></span>
            </div>
            
            <?php if($tier_price > 0): ?>
            <div class="line-item">
                <span>🚀 Deluxe Priority Fee</span>
                <span>KES <?php echo number_format($tier_price); ?></span>
            </div>
            <?php endif; ?>

            <?php if($storage_price > 0): ?>
            <div class="line-item">
                <span>📦 Overnight Storage</span>
                <span>KES <?php echo number_format($storage_price); ?></span>
            </div>
            <?php endif; ?>

            <div class="total-row">
                <span>TOTAL PAID</span>
                <span>KES <?php echo number_format($order['total_price']); ?></span>
            </div>
        </div>

        <a href="#" onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> Print Receipt</a>
        <a href="my_orders.php" class="back-link">Back to Orders</a>
    </div>

</body>
</html>