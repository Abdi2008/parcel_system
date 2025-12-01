<?php
// 1. Include the connection file
include 'db_connect.php';

// 2. Check if the button was clicked
if (isset($_POST['register_btn'])) {

    // 3. Get data from the form
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $raw_password = $_POST['password'];

    // 4. Secure the password (Encryption)
    // We use password_hash for security. It's better than MD5.
    $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

    // 5. Check if email already exists
    $check_email = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($check_email);

    if ($result->num_rows > 0) {
        // Email taken
        echo "<script>alert('Email already exists!'); window.location.href='register.php';</script>";
    } else {
        // 6. Insert new user
        $sql = "INSERT INTO users (full_name, email, phone, password, role) 
                VALUES ('$name', '$email', '$phone', '$hashed_password', 'customer')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registration Successful! Please Login.'); window.location.href='index.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>