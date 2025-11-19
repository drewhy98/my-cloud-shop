<?php
// index.php
session_start();
include "db_read.php"; // <-- your read-replica connection

// Function to fetch products by category
function getProducts($conn, $category) {
    $sql = "SELECT id, name, price FROM products WHERE category = ?";
    return sqlsrv_query($conn, $sql, [$category]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShopSphere - Christmas Dinner Specials</title>

  <style>
    body {
      font-family: 'Helvetica Neue', Arial, sans-serif;
      margin: 0;
      background-color: #fafafa;
      color: #333;
      line-height: 1.6;
    }

    h1, h2, h3, h4 {
      font-family: 'Georgia', serif;
    }

    header {
      background-color: #ffffff;
      border-bottom: 1px solid #e0e0e0;
      padding: 15px 40px;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
    }

    header h1 {
      color: #2e5d34;
      font-size: 1.8em;
      margin: 0;
    }

    .auth-links a, .auth-links span {
      color: #2e5d34;
      font-weight: bold;
      margin-left: 15px;
      text-decoration: none;
    }

    .logout-btn {
      background-color: #2e5d34;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 4px;
      cursor: pointer;
    }

    nav {
      background-color: #f2f5f1;
      padding: 12px 30px;
      display: flex;
      justify-content: center;
      gap: 35px;
      border-bottom: 1px solid #ddd;
    }

    nav a {
      color: #2e5d34;
      font-weight: 600;
      font-size: 0.95em;
      text-decoration: none;
    }

    .featured {
      max-width: 1100px;
      margin: 50px auto 20px auto;
      text-align: center;
    }

    .featured img {
      width: 100%;
      max-height: 420px;
      object-fit: cover;
      border-radius: 10px;
    }

    .sub-featured {
      max-width: 1200px;
      margin: 50px auto;
      padding: 0 20px;
    }

    .sub-featured h3 {
      color: #2e5d34;
      border-bottom: 2px solid #2e5d34;
      padding-bottom: 5px;
      margin-bottom: 25px;
      text-align: center;
      font-size: 1.4em;
    }

    .products {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 25px;
    }

    .product {
      background-color: white;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      text-align: center;
      padding: 15px;
      transition: box-shadow 0.3s;
    }

    .product:hover {
      box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    }

    .product h4 {
      color: #2e5d34;
      font-size: 1.1em;
      margin: 10px 0 5px;
    }

    .product p {
      color: #555;
      margin: 5px 0 10px;
    }

    .product button {
      background-color: #2e5d34;
      color: white;
      border: none;
      padding: 8px 14px;
      border-radius: 4px;
      cursor: pointer;
      margin-top: 5px;
      width: 100%;
    }

    footer {
      background-color: #f2f5f1;
      color: #2e5d34;
      text-align: center;
      padding: 15px;
      margin-top: 60px;
      border-top: 1px solid #ddd;
    }
  </style>
</head>

<body>

<header>
  <h1>ShopSphere</h1>

  <div class="auth-links">
    <?php if (isset($_SESSION['user_name'])): ?>
      <span>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
      <form method="post" action="logout.php" style="display:inline;">
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    <?php else: ?>
      <a href="login.php">Log In</a> |
      <a href="register.php">Sign Up</a> |
      <a href="add_users.php">Sign Up Automatically</a> |
      <a href="display_users.php">View Registered Users</a> |
      <a href="test_replication_lag.php">Test DB Replication</a>
    <?php endif; ?>
  </div>
</header>

<nav>
  <a href="#meat">Meat</a>
  <a href="#veg">Vegetables</a>
  <a href="#bakery">Bakery</a>
</nav>

<!-- FEATURED -->
<section class="featured">
  <h2>Featured This December</h2>
  <img src="https://learn.surlatable.com/wp-content/uploads/2022/12/Tips-for-planning-your-Christmas-dinner.jpg" alt="Christmas Dinner">
</section>

<!-- MEAT -->
<section class="sub-featured" id="meat">
  <h3>Meat</h3>
  <div class="products">

    <?php
      $items = getProducts($conn_read, "meat");
      while ($row = sqlsrv_fetch_array($items, SQLSRV_FETCH_ASSOC)):
    ?>

      <div class="product">
        <h4><?= htmlspecialchars($row['name']) ?></h4>
        <p>£<?= number_format($row['price'], 2) ?></p>

        <form action="add_to_cart.php" method="post">
          <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
          <button type="submit">Add to Cart</button>
        </form>

        <form action="add_to_wishlist.php" method="post">
          <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
          <button type="submit">❤️ Wishlist</button>
        </form>
      </div>

    <?php endwhile; ?>
  </div>
</section>

<!-- VEGETABLES -->
<section class="sub-featured" id="veg">
  <h3>Vegetables</h3>
  <div class="products">

    <?php
      $items = getProducts($conn_read, "veg");
      while ($row = sqlsrv_fetch_array($items, SQLSRV_FETCH_ASSOC)):
    ?>

      <div class="product">
        <h4><?= htmlspecialchars($row['name']) ?></h4>
        <p>£<?= number_format($row['price'], 2) ?></p>

        <form action="add_to_cart.php" method="post">
          <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
          <button type="submit">Add to Cart</button>
        </form>

        <form action="add_to_wishlist.php" method="post">
          <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
          <button type="submit">❤️ Wishlist</button>
        </form>
      </div>

    <?php endwhile; ?>
  </div>
</section>

<!-- BAKERY -->
<section class="sub-featured" id="bakery">
  <h3>Bakery</h3>
  <div class="products">

    <?php
      $items = getProducts($conn_read, "bakery");
      while ($row = sqlsrv_fetch_array($items, SQLSRV_FETCH_ASSOC)):
    ?>

      <div class="product">
        <h4><?= htmlspecialchars($row['name']) ?></h4>
        <p>£<?= number_format($row['price'], 2) ?></p>

        <form action="add_to_cart.php" method="post">
          <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
          <button type="submit">Add to Cart</button>
        </form>

        <form action="add_to_wishlist.php" method="post">
          <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
          <button type="submit">❤️ Wishlist</button>
        </form>
      </div>

    <?php endwhile; ?>
  </div>
</section>

<footer>
  <p>&copy; 2025 ShopSphere | Fresh, Local & Healthy</p>
</footer>

</body>
</html>
