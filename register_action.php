<?php
include 'db_connect.php';

if (isset($_POST['register_btn'])) {

    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $raw_password = $_POST['password'];
    $role_choice = $_POST['role']; // Get the choice

    // SECURITY CHECK: Only allow 'customer' or 'driver'. 
    // If they try to hack and send 'admin', force it to 'customer'.
    if ($role_choice !== 'driver') {
        $role_choice = 'customer';
    }

    $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($check_email);

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists!'); window.location.href='register.php';</script>";
    } else {
        // UPDATED SQL: We now insert the 'role' variable
        $sql = "INSERT INTO users (full_name, email, phone, password, role) 
                VALUES ('$name', '$email', '$phone', '$hashed_password', '$role_choice')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registration Successful! Please Login.'); window.location.href='index.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>