<?php
session_start();
include 'db_connect.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

if (isset($_POST['update_btn'])) {
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['status'];

    $sql = "UPDATE bookings SET status = '$new_status' WHERE booking_id = '$booking_id'";

    if ($conn->query($sql) === TRUE) {
        // Success: Go back to admin dashboard
        echo "<script>alert('Status Updated Successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>