<?php
session_start();
require_once "db_read.php"; // read-only replica for product info

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Query cart items first
$sql = "
    SELECT c.cart_id, c.product_id, c.quantity, p.name, p.price, p.image_url
    FROM user_cart c
    JOIN products p ON c.product_id = p.product_id
    WHERE c.user_id = ?
";
$params = [$user_id];
$stmt = sqlsrv_query($conn_read, $sql, $params);

$total = 0;
$cartItems = [];

if ($stmt !== false) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $row['subtotal'] = $row['price'] * $row['quantity'];
        $total += $row['subtotal'];
        $cartItems[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopSphere - Your Basket</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; margin:0; background:#fafafa; color:#333; line-height:1.6; }
        header { background: #fff; border-bottom:1px solid #e0e0e0; padding:15px 40px; display:flex; justify-content:space-between; align-items:center; }
        header h1 { color:#2e5d34; margin:0; }
        .auth-links span { margin-right:12px; font-weight:bold; }
        .logout-btn { background:#2e5d34; color:#fff; border:none; padding:5px 10px; border-radius:4px; cursor:pointer; font-weight:bold; }
        nav { background:#f2f5f1; padding:12px 30px; text-align:center; }
        nav a { margin:0 15px; text-decoration:none; color:#2e5d34; font-weight:600; }
        .total-pay { text-align:center; margin:20px 0; }
        .total-pay h2 { margin-bottom:10px; }
        .products-grid { max-width:1200px; margin:30px auto; display:grid; grid-template-columns: repeat(auto-fill,minmax(250px,1fr)); gap:25px; padding:0 20px; }
        .product-card { background:white; border:1px solid #e0e0e0; border-radius:8px; padding:15px; text-align:center; }
        .product-card img { width:100%; height:180px; object-fit:contain; border-radius:5px; }
        .product-card h4 { margin:10px 0 5px; color:#2e5d34; }
        .product-card p { color:#555; }
        .btn { background-color:#2e5d34; color:white; padding:8px 14px; border:none; border-radius:4px; cursor:pointer; margin-top:6px; }
        .btn:hover { background-color:#244928; }
        footer { margin-top:50px; background:#f2f5f1; padding:15px; text-align:center; color:#2e5d34; border-top:1px solid #ddd; }
    </style>
</head>
<body>

<header>
    <h1>ShopSphere</h1>
    <div class="auth-links">
        <span>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?></span>
        <form method="post" action="logout.php" style="display:inline;">
            <button class="logout-btn">Logout</button>
        </form>
        <a href="wishlist.php" class="btn" style="margin-left:10px;">Wishlist</a>
        <a href="basket.php" class="btn" style="margin-left:10px;">Basket</a>
    </div>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="display_meat.php">Meat</a>
    <a href="display_veg.php">Vegetables</a>
    <a href="display_bakery.php">Bakery</a>
</nav>

<!-- Total Cost & Pay Now immediately under header/nav -->
<div class="total-pay">
    <h2>Total Cost: £<?= number_format($total, 2); ?></h2>

    <?php if ($total > 0): ?>
        <form method="post" action="pay.php">
            <button type="submit" class="btn">Pay Now</button>
        </form>
    <?php endif; ?>
</div>

<div class="products-grid">

<?php
if (empty($cartItems)) {
    echo "<p style='grid-column:1/-1; text-align:center;'>Your basket is empty.</p>";
} else {
    foreach ($cartItems as $row):
        $image = !empty($row['image_url']) ? htmlspecialchars($row['image_url']) : "placeholder.png";
?>

    <div class="product-card">
        <img src="<?= $image ?>" alt="<?= htmlspecialchars($row['name']); ?>">
        <h4><?= htmlspecialchars($row['name']); ?></h4>
        <p>Price: £<?= number_format($row['price'], 2); ?></p>
        <p>Quantity: <?= $row['quantity']; ?></p>
        <p>Subtotal: £<?= number_format($row['subtotal'], 2); ?></p>

        <form method="post" action="remove_from_basket.php">
            <input type="hidden" name="cart_id" value="<?= $row['cart_id']; ?>">
            <button class="btn">Remove</button>
        </form>
    </div>

<?php
    endforeach;
}
?>

</div>

<footer>
    &copy; 2025 ShopSphere | Fresh, Local & Healthy
</footer>

<?php
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn_read);
?>

</body>
</html>
