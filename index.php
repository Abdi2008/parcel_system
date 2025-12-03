<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to IPMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: beige; color: #E67E22; }
        
        /* Navbar */
        .navbar { display: flex; justify-content: space-between; padding: 20px 50px; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .logo { font-size: 24pt; font-weight: bold; color: #E67E22; }
        .nav-links a { text-decoration: none; color: #333; margin-left: 20px; font-weight: 600; }
        .btn { padding: 10px 20px; border-radius: 5px; text-decoration: none; color: white; background: #2980b9; }
        .btn-1:hover{ padding: 10px 20px; border-radius: 5px; text-decoration: none; color: white; background: #2980b9;}
        
        /* Hero Section */
        .hero { height: 80vh; display: flex; align-items: center; justify-content: center; text-align: center; background-image: url("images/freights.jpg"); background-size: cover; color: white; }
        .hero h1 { font-size: 50px; margin-bottom: 20px; color: black;}
        .logo-img { width: 50px; height: 50px; }
        .hero p { font-size: 20px; margin-bottom: 30px; color: black; }
        .cta-btn { padding: 15px 30px; background: #27ae60; color: white; text-decoration: none; font-size: 18px; border-radius: 5px; font-weight: bold; }
        
        /* Services */
        .features { display: flex; padding: 50px; justify-content: space-around; background-color: rgb(117, 117, 91); }
        .feature-box { text-align: center; max-width: 300px; }
        .feature-box h3 { margin: 15px 0; color: #EBD5AB; font-size: 20pt; }
        .feature-box p {color: black; font-size: ;}
    </style>
</head>
<body>

    <nav class="navbar">
        <img src="images/bat-silhouette-black-shape-with-wings-forming-a-circle.png" alt="" class="logo-img">
        <div class="logo">IPMS Logistics</div>
        <div class="nav-links">
            <a href="login.php" class="btn-1">Login</a>
            <a href="register.php" class="btn">Sign Up</a>
        </div>
    </nav>

    <div class="hero">
        <div>
            <h1>Fast, Reliable & Secure Delivery</h1>
            <p>From perishable goods to fragile items, we handle it all.</p>
            <a href="register.php" class="cta-btn">Book a Shipment</a>
        </div>
    </div>

    <div class="features">
        <div class="feature-box">
            <h3>🚀 Deluxe Speed</h3>
            <p>Need it there yesterday? Our Deluxe service prioritizes your package.</p>
        </div>
        <div class="feature-box">
            <h3>❄️ Perishable Care</h3>
            <p>Specialized handling for food, flowers, and temperature-sensitive items.</p>
        </div>
        <div class="feature-box">
            <h3>📦 Overnight Storage</h3>
            <p>Secure warehousing options available for short-term storage.</p>
        </div>
    </div>

</body>
</html>