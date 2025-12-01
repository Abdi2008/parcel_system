<?php
session_start(); // Start a "session" to remember who is logged in
include 'db_connect.php';

if (isset($_POST['login_btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Check if email exists
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // 2. Verify the encrypted password
        // (Note: If you manually changed the password to "12345" in PHPMyAdmin without MD5 or Hash, 
        // this might fail. We usually test with a user created via the Register page).
        if (password_verify($password, $row['password'])) {
            
            // 3. Login Success! Save user info in Session
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['role'] = $row['role'];

            // 4. Redirect based on role (Admin vs Customer)
            if ($row['role'] == 'admin') {
                header("Location: admin_dashboard.php"); // We will build this later
            } else {
                header("Location: dashboard.php");
            }
            exit();

        } else {
            echo "<script>alert('Incorrect Password!'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('Email not found!'); window.location.href='index.php';</script>";
    }
}
?>