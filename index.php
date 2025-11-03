<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GolfShop - Home</title>
    <style>
        /* Reset & Base */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        /* Header */
        .header {
            background-color: #004d00;
            color: white;
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header h1 {
            font-size: 2em;
        }
        .header p {
            font-size: 0.9em;
        }
        .auth-buttons a {
            margin-left: 15px;
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
        .auth-buttons a.login {
            background-color: #fff;
            color: #004d00;
        }
        .auth-buttons a.register {
            background-color: #ffcc00;
            color: #004d00;
        }
        .auth-buttons a:hover {
            opacity: 0.8;
        }

        /* Hero Section */
        .hero {
            background: url('images/golf-hero.jpg') center/cover no-repeat;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
        }
        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
        }
        .hero-content {
            position: relative;
            z-index: 1;
        }
        .hero-content h2 {
            font-size: 3em;
            margin-bottom: 15px;
        }
        .hero-content p {
            font-size: 1.2em;
        }

        /* Categories */
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }
        .categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
        .category-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        .category-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .category-card h3 {
            color: #004d00;
            margin: 15px 0 10px;
        }
        .category-card p {
            padding: 0 15px;
            color: #555;
            font-size: 0.95em;
        }
        .category-card a {
            margin: 15px 0 20px;
            padding: 10px 20px;
            background-color: #004d00;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .category-card a:hover {
            background-color: #003300;
        }

        /* Footer */
        footer {
            background-color: #004d00;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div>
            <h1>GolfShop</h1>
            <p>Clubs, Clothing & Accessories</p>
        </div>
        <div class="auth-buttons">
            <a href="login.php" class="login">Log In</a>
            <a href="register.php" class="register">Register</a>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2>Upgrade Your Game</h2>
            <p>Shop the latest golf clubs, apparel, and accessories.</p>
        </div>
    </section>

    <!-- Categories -->
    <div class="container">
        <h2 style="text-align:center; margin-bottom: 30px;">Shop by Category</h2>
        <div class="categories">
            <div class="category-card">
                <img src="images/clubs.jpg" alt="Golf Clubs">
                <h3>Clubs</h3>
                <p>Drivers, irons, putters, and more for all skill levels.</p>
                <a href="clubs.php">Shop Clubs</a>
            </div>
            <div class="category-card">
                <img src="images/clothing.jpg" alt="Golf Clothing">
                <h3>Clothing</h3>
                <p>Polo shirts, pants, shoes, and golf apparel for men and women.</p>
                <a href="clothing.php">Shop Clothing</a>
            </div>
            <div class="category-card">
                <img src="images/accessories.jpg" alt="Golf Accessories">
                <h3>Accessories</h3>
                <p>Gloves, balls, tees, bags, and all essential golf gear.</p>
                <a href="accessories.php">Shop Accessories</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2025 GolfShop. All rights reserved.
    </footer>

</body>
</html>
