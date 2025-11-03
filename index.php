<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GolfShop - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f5f5f5;
        }
        .header {
            background-color: #006400;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .categories {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }
        .category-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        .category-card:hover {
            transform: translateY(-5px);
        }
        .category-card img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        .category-card h3 {
            margin: 15px 0 10px;
            color: #006400;
        }
        .category-card p {
            color: #555;
        }
        .category-card a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #006400;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .category-card a:hover {
            background-color: #004d00;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>GolfShop</h1>
        <p>Your destination for golf clubs, clothing, and accessories</p>
    </div>

    <div class="container">
        <h2>Shop by Category</h2>
        <div class="categories">
            <!-- Clubs -->
            <div class="category-card">
                <img src="images/clubs.jpg" alt="Golf Clubs">
                <h3>Clubs</h3>
                <p>Drivers, irons, putters, and more for all skill levels.</p>
                <a href="clubs.php">Shop Clubs</a>
            </div>

            <!-- Clothing -->
            <div class="category-card">
                <img src="images/clothing.jpg" alt="Golf Clothing">
                <h3>Clothing</h3>
                <p>Polo shirts, pants, shoes, and golf apparel for men and women.</p>
                <a href="clothing.php">Shop Clothing</a>
            </div>

            <!-- Accessories -->
            <div class="category-card">
                <img src="images/accessories.jpg" alt="Golf Accessories">
                <h3>Accessories</h3>
                <p>Gloves, balls, tees, bags, and all essential golf gear.</p>
                <a href="accessories.php">Shop Accessories</a>
            </div>
        </div>
    </div>

</body>
</html>
