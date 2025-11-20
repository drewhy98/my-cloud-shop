<?php
session_start();
require_once "db_read.php"; // read-only DB

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Cast user_id and order_id to integers to avoid SQL Server type issues
$user_id  = intval($_SESSION['user_id']);
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id <= 0) {
    die("No order specified.");
}

// Fetch order details
$sql_order = "
    SELECT order_id, total_amount, address, payment_method, status
    FROM orders
    WHERE order_id = $order_id AND user_id = $user_id
";
$stmt_order = sqlsrv_query($conn_read, $sql_order);

if ($stmt_order === false || ($order = sqlsrv_fetch_array($stmt_order, SQLSRV_FETCH_ASSOC)) === null) {
    die("Order not found.");
}
sqlsrv_free_stmt($stmt_order);

// Fetch order items
$sql_items = "
    SELECT p.name, oi.quantity, oi.price
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = $order_id
";
$stmt_items = sqlsrv_query($conn_read, $sql_items);

$order_items = [];
while ($row = sqlsrv_fetch_array($stmt_items, SQLSRV_FETCH_ASSOC)) {
    $order_items[] = $row;
}
sqlsrv_free_stmt($stmt_items);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Confirmation - ShopSphere</title>
<style>
body { font-family: Arial, sans-serif; background:#fafafa; margin:0; }
h1, h2 { color:#2e5d34; }
.container { max-width:800px; margin:30px auto; background:white; padding:20px; border-radius:8px; }
table { width:100%; border-collapse: collapse; margin-top:15px; }
th, td { border:1px solid #ccc; padding:10px; }
th { background:#2e5d34; color:white; }
.total { text-align:right; font-weight:bold; margin-top:10px; }
footer { background:#f2f5f1; padding:15px; text-align:center; }
</style>
</head>
<body>

<?php include "header_nav.php"; ?>

<div class="container">
<h1>Thank you for your order!</h1>
<p>Your Order ID is: <strong><?= $order['order_id'] ?></strong></p>
<p>Payment Method: <strong><?= htmlspecialchars($order['payment_method']) ?></strong></p>
<p>Delivery Address: <strong><?= htmlspecialchars($order['address']) ?></strong></p>

<h3>Order Summary</h3>
<table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Price (£)</th>
            <th>Qty</th>
            <th>Subtotal (£)</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($order_items as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= number_format($item['price'], 2) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td><?= number_format($item['price'] * $item['quantity'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="total">Total Paid: £<?= number_format($order['total_amount'], 2) ?></div>
</div>

<footer>&copy; 2025 ShopSphere | Fresh, Local & Healthy</footer>

<?php sqlsrv_close($conn_read); ?>
</body>
</html>
