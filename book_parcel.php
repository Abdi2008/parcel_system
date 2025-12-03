<?php
session_start();
include 'db_connect.php';

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// 2. Handle Form Submission (PHP Logic)
if (isset($_POST['book_btn'])) {
    $user_id = $_SESSION['user_id'];
    $pickup = $_POST['pickup_address'];
    $dropoff = $_POST['dropoff_address'];
    $p_type = $_POST['parcel_type'];
    $s_tier = $_POST['service_tier'];
    // Checkbox returns '1' if checked, or nothing if unchecked
    $storage = isset($_POST['storage_required']) ? 1 : 0;
    
    // Recalculate price on server side for security
    $base_price = 500;
    $tier_price = ($s_tier == 'deluxe') ? 1000 : 0;
    $storage_price = ($storage == 1) ? 500 : 0;
    $total = $base_price + $tier_price + $storage_price;

    $sql = "INSERT INTO bookings (user_id, pickup_address, dropoff_address, parcel_type, service_tier, storage_required, total_price) 
            VALUES ('$user_id', '$pickup', '$dropoff', '$p_type', '$s_tier', '$storage', '$total')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Booking Successful!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book a Parcel - IPMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Specific styles for the booking form */
        body { background: beige; height: 100vh; display: flex; justify-content: center; align-items: center; }
        .booking-container { background: white; padding: 40px; border-radius: 10px; width: 100%; max-width: 500px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; color: #2c3e50; }
        label { font-weight: 600; font-size: 14px; display: block; margin-top: 15px; color: #34495e; }
        select, input[type="text"] { width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ddd; border-radius: 6px; }
        .price-box { background: #e1f5fe; padding: 15px; border-radius: 6px; margin-top: 20px; text-align: center; font-size: 20px; font-weight: bold; color: #0277bd; }
        .checkbox-group { margin-top: 15px; display: flex; align-items: center; }
        .checkbox-group input { width: auto; margin-right: 10px; }
        .btn-back { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #7f8c8d; }
    </style>
</head>
<body>

    <div class="booking-container">
        <h2> New Shipment</h2>
        <form method="POST">
            
            <label>Pickup Address (Point A)</label>
            <input type="text" name="pickup_address" placeholder="e.g., Westlands, Nairobi" required>

            <label>Dropoff Address (Point B)</label>
            <input type="text" name="dropoff_address" placeholder="e.g., Mombasa Road" required>

            <div style="display: flex; gap: 10px;">
                <div style="flex: 1;">
                    <label>Parcel Type</label>
                    <select name="parcel_type">
                        <option value="standard">Standard</option>
                        <option value="perishable"> Perishable</option>
                        <option value="fragile"> Fragile</option>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label>Service Tier</label>
                    <select name="service_tier" id="service_tier" onchange="calculateTotal()">
                        <option value="standard">Standard (Slow)</option>
                        <option value="deluxe"> Deluxe (Fast)</option>
                    </select>
                </div>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" name="storage_required" id="storage_required" value="1" onchange="calculateTotal()">
                <label for="storage_required" style="margin:0; cursor:pointer;">Request Overnight Storage (+500 KES)</label>
            </div>

            <div class="price-box">
                Total: KES <span id="total_display">500</span>
            </div>

            <button type="submit" name="book_btn" style="margin-top: 20px;">Confirm Booking</button>
            <a href="dashboard.php" class="btn-back">Cancel</a>
        </form>
    </div>

    <script>
        function calculateTotal() {
            // Get selected values
            let tier = document.getElementById("service_tier").value;
            let storage = document.getElementById("storage_required").checked;

            // Define Prices
            let basePrice = 500;
            let deluxeFee = 1000;
            let storageFee = 500;

            let total = basePrice;

            // Add Logic
            if (tier === "deluxe") {
                total += deluxeFee;
            }
            if (storage === true) {
                total += storageFee;
            }

            // Update HTML
            document.getElementById("total_display").innerText = total;
        }
    </script>

</body>
</html>