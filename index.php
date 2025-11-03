<?php
// index.php
session_start();
// optionally check if user is logged in, set variables e.g. $userName
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MyShop – Online Store</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    /* Basic styling, you’ll likely move this to styles.css */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }
    .header {
      background-color: #2c3e50;
      color: white;
      padding: 20px 0;
      text-align: center;
    }
    .nav {
      background-color: #34495e;
      padding: 10px 0;
      text-align: center;
    }
    .nav a {
      color: white;
      margin: 0 15px;
      text-decoration: none;
      font-size: 16px;
    }
    .container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 0 20px;
    }
    .featured-products {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    .product-card {
      background: white;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: calc(33.333% - 20px);
      padding: 20px;
      box-sizing: border-box;
      text-align: center;
    }
    .product-card img {
      max-width: 100%;
      height: auto;
    }
    .product-card h3 {
      margin: 15px 0 10px;
    }
    .product-card p.price {
      font-size: 18px;
      color: #e74c3c;
      margin: 10px 0;
    }
    .product-card a.btn {
      display: inline-block;
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 4px;
      margin-top: 10px;
    }
    .product-card a.btn:hover {
      background-color: #0056b3;
    }
    .footer {
      text-align: center;
      padding: 20px 0;
      background-color: #2c3e50;
      color: white;
      margin-top: 40px;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>MyShop</h1>
    <p>Your one-stop destination for amazing products</p>
  </div>

  <div class="nav">
    <a href="index.php">Home</a>
    <a href="shop.php">Shop All</a>
    <a href="about.php">About Us</a>
    <a href="contact.php">Contact</a>
    <?php if (isset($_SESSION['user_name'])): ?>
      <a href="logout.php">Log Out (<?php echo htmlspecialchars($_SESSION['user_name']); ?>)</a>
    <?php else: ?>
      <a href="login.php">Log In</a>
      <a href="register.php">Register</a>
    <?php endif; ?>
  </div>

  <div class="container">
    <h2>Featured Products</h2>
    <div class="featured-products">
      <!-- Example product card 1 -->
      <div class="product-card">
        <img src="images/product1.jpg" alt="Product 1">
        <h3>Product 1 Name</h3>
        <p class="price">£9.99</p>
        <a href="product.php?id=1" class="btn">View Product</a>
      </div>
      <!-- Example product card 2 -->
      <div class="product-card">
        <img src="images/product2.jpg" alt="Product 2">
        <h3>Product 2 Name</h3>
        <p class="price">£14.99</p>
        <a href="product.php?id=2" class="btn">View Product</a>
      </div>
      <!-- Example product card 3 -->
      <div class="product-card">
        <img src="images/product3.jpg" alt="Product 3">
        <h3>Product 3 Name</h3>
        <p class="price">£7.50</p>
        <a href="product.php?id=3" class="btn">View Product</a>
      </div>
      <!-- You can duplicate/loop as needed for more products -->
    </div>
  </div>

  <div class="footer">
    <p>&copy; <?php echo date("Y"); ?> MyShop. All rights reserved.</p>
  </div>

</body>
</html>
