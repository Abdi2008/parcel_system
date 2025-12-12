<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - IPMS</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Create Account</h2>
        <form action="register_action.php" method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="password" name="password" placeholder="Password" required>
    
            <div style="margin: 10px 0;">
            <label style="font-size: 14px; color: #666; display: block; text-align: left; margin-bottom: 5px;">I want to be a:</label>
            <select name="role" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: white;">
            <option value="customer">📦 Customer (I want to send parcels)</option>
            <option value="driver">🚚 Driver (I want to deliver parcels)</option>
            </select>
            </div>

        <button type="submit" name="register_btn">Sign Up</button>
        </form>
        <a href="login.php" class="link">Already have an account? Login</a>
    </div>
</body>
</html>