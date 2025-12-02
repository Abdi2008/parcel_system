<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

// Handle Update
if (isset($_POST['update_btn'])) {
    $name = $_POST['full_name'];
    $phone = $_POST['phone'];
    
    $sql = "UPDATE users SET full_name='$name', phone='$phone' WHERE user_id='$user_id'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['full_name'] = $name; // Update session name instantly
        $msg = "<p style='color:green;'>Profile updated successfully!</p>";
    } else {
        $msg = "<p style='color:red;'>Error updating profile.</p>";
    }
}

// Fetch Current Data
$sql = "SELECT * FROM users WHERE user_id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Reusing Sidebar Layout */
        body { display: flex; height: 100vh; background-color: #f0f2f5; margin: 0; }
        .sidebar { width: 250px; background-color: #2c3e50; color: white; display: flex; flex-direction: column; padding: 20px; }
        .logo { font-size: 24px; font-weight: bold; margin-bottom: 40px; color: #ecf0f1; }
        .menu a { text-decoration: none; color: #bdc3c7; display: block; padding: 15px; margin-bottom: 10px; border-radius: 8px; transition: 0.3s; }
        .menu a:hover, .menu a.active { background-color: #34495e; color: white; padding-left: 20px; }
        .menu i { margin-right: 10px; width: 20px; }
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        
        .form-box { background: white; padding: 30px; border-radius: 10px; max-width: 500px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        label { font-weight: bold; color: #2c3e50; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo"><i class="fas fa-box-open"></i> IPMS</div>
        <div class="menu">
            <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="book_parcel.php"><i class="fas fa-shipping-fast"></i> Book Parcel</a>
            <a href="my_orders.php"><i class="fas fa-list"></i> My Orders</a>
            <a href="#" class="active"><i class="fas fa-user"></i> Profile</a>
            <a href="logout.php" style="margin-top: auto; color: #e74c3c;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <h2>👤 Edit Profile</h2>
        <br>
        <div class="form-box">
            <?php echo $msg; ?>
            <form method="POST">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>" required>
                
                <label>Email (Cannot be changed)</label>
                <input type="email" value="<?php echo $user['email']; ?>" disabled style="background:#eee;">
                
                <label>Phone Number</label>
                <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required>
                
                <button type="submit" name="update_btn">Update Profile</button>
            </form>
        </div>
    </div>
</body>
</html>