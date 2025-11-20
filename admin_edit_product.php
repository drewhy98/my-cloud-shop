<?php
session_start();
require_once "db_write.php"; // admin needs write access

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php?error=" . urlencode("Please log in as an admin."));
    exit();
}

// Get product ID
if (!isset($_GET['id'])) {
    header("Location: admin_view_products.php?error=" . urlencode("No product selected."));
    exit();
}

$product_id = intval($_GET['id']);

// Fetch current product details
$sql = "SELECT product_id, name, price, category, image_url, stock
        FROM products
        WHERE product_id = ?";
$stmt = sqlsrv_query($conn_write, $sql, [$product_id]);

if ($stmt === false || ($product = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) === null) {
    die("Product not found: " . print_r(sqlsrv_errors(), true));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $category = trim($_POST['category']);
    $image_url = trim($_POST['image_url']);
    $stock = intval($_POST['stock']);

    $update_sql = "UPDATE products
                   SET name = ?, price = ?, category = ?, image_url = ?, stock = ?
                   WHERE product_id = ?";
    $params = [$name, $price, $category, $image_url, $stock, $product_id];

    $update_stmt = sqlsrv_query($conn_write, $update_sql, $params);

    if ($update_stmt === false) {
        die("Failed to update product: " . print_r(sqlsrv_errors(), true));
    }

    // Redirect back to the product list with success message
    header("Location: admin_view_products.php?msg=" . urlencode("Product updated successfully."));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Edit Product</title>
<style>
body { font-family:'Helvetica Neue',Arial; margin:0; background:#fafafa; color:#333; }
header, nav { padding:15px 40px; }
header { background:white; border-bottom:1px solid #ddd; display:flex; justify-content:space-between; align-items:center; }
header h1 { color:#2e5d34; font-family:'Georgia'; margin:0; }
.logout-btn { padding:6px 12px; background:#2e5d34; border:none; border-radius:4px; color:white; cursor:pointer; }
nav { background:#f2f5f1; display:flex; justify-content:center; gap:30px; flex-wrap:wrap; }
nav a { text-decoration:none; color:#2e5d34; font-weight:bold; }
.container { max-width:600px; margin:30px auto; background:white; padding:20px; border-radius:8px; }
form label { display:block; margin-top:10px; font-weight:bold; }
form input { width:100%; padding:8px; margin-top:5px; border:1px solid #ccc; border-radius:4px; }
form button { margin-top:15px; padding:8px 14px; background:#2e5d34; color:white; border:none; border-radius:4px; cursor:pointer; }
form button:hover { background:#244928; }
</style>
</head>
<body>

<header>
    <h1>ShopSphere Admin Panel</h1>
    <form method="post" action="admin_logout.php">
        <button class="logout-btn">Logout</button>
    </form>
</header>

<nav>
    <a href="admin_index.php">Dashboard</a>
    <a href="admin_view_orders.php">Manage Orders</a>
    <a href="admin_view_product.php">Manage Products</a>
</nav>

<div class="container">
    <h2>Edit Product</h2>
    <form method="post" action="">
        <label for="name">Product Name</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name']); ?>" required>

        <label for="price">Price (£)</label>
        <input type="number" name="price" id="price" step="0.01" value="<?= htmlspecialchars($product['price']); ?>" required>

        <label for="category">Category</label>
        <input type="text" name="category" id="category" value="<?= htmlspecialchars($product['category']); ?>" required>

        <label for="image_url">Image URL</label>
        <input type="text" name="image_url" id="image_url" value="<?= htmlspecialchars($product['image_url']); ?>">

        <label for="stock">Stock Quantity</label>
        <input type="number" name="stock" id="stock" value="<?= htmlspecialchars($product['stock']); ?>" min="0" required>

        <button type="submit">Update Product</button>
    </form>
</div>

<footer style="background:#f2f5f1;text-align:center;padding:15px;margin-top:40px;color:#2e5d34;border-top:1px solid #ddd;">
    &copy; <?= date("Y") ?> ShopSphere Admin
</footer>

</body>
</html>
