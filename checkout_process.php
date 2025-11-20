<?php
session_start();
require_once "db_write.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$address = trim($_POST['address'] ?? "");
$payment_method = trim($_POST['payment_method'] ?? "");

// Server-side validation
if (empty($address) || empty($payment_method)) {
    die("Delivery address and payment method are required. <a href='checkout.php'>Go back</a>");
}

// Get basket items
$sql = "
    SELECT c.product_id, c.quantity, p.price, p.name
    FROM user_cart c
    JOIN products p ON c.product_id = p.product_id
    WHERE c.user_id = ?
";
$stmt = sqlsrv_query($conn_write, $sql, [$user_id]);

$items = [];
$total_amount = 0;

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $items[] = $row;
    $total_amount += $row['price'] * $row['quantity'];
}

sqlsrv_free_stmt($stmt);

if (empty($items)) {
    die("Your basket is empty. <a href='basket.php'>Go back</a>");
}

// Insert order with payment method
$sql_order = "
    INSERT INTO orders (user_id, total_amount, address, payment_method, status)
    VALUES (?, ?, ?, ?, 'Pending')
";
$params_order = [$user_id, $total_amount, $address, $payment_method];
$stmt_order = sqlsrv_query($conn_write, $sql_order, $params_order);

if ($stmt_order === false) {
    die("Error creating order: " . print_r(sqlsrv_errors(), true));
}

// Get order_id
$sql_id = "SELECT SCOPE_IDENTITY() AS order_id";
$stmt_id = sqlsrv_query($conn_write, $sql_id);
$order_id = sqlsrv_fetch_array($stmt_id, SQLSRV_FETCH_ASSOC)['order_id'];
sqlsrv_free_stmt($stmt_id);

// Insert items into order_items
foreach ($items as $item) {
    $sql_item = "
        INSERT INTO order_items (order_id, product_id, quantity, price)
        VALUES (?, ?, ?, ?)
    ";
    sqlsrv_query($conn_write, $sql_item, [
        $order_id,
        $item['product_id'],
        $item['quantity'],
        $item['price']
    ]);
}

// Clear basket
sqlsrv_query($conn_write, "DELETE FROM user_cart WHERE user_id = ?", [$user_id]);

sqlsrv_close($conn_write);
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Confirmation</title>
<style>
body { font-family: Arial; background:#fafafa; margin:0; }
.container { max-width:700px; margin:30px auto; background:white; padding:20px; border-radius:8px; }
table { width:100%; border-collapse:collapse; margin-top:20px; }
td, th { border:1px solid #ddd; padding:8px; }
th { background:#2e5d34; color:white; }
.total { text-align:right; font-weight:bold; margin-top:20px; }
</style>
</head>

<body>
<div class="container">
    <h2>Thank you for your order!</h2>
    <p>Your Order ID: <strong><?= $order_id ?></strong></p>

    <h3>Order Summary</h3>

    <table>
        <tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>£<?= number_format($item['price'], 2) ?></td>
            <td>£<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="total">Total Paid: £<?= number_format($total_amount, 2) ?></div>

    <p><strong>Payment Method:</strong> <?= htmlspecialchars($payment_method) ?></p>
    <p><strong>Delivery Address:</strong> <?= nl2br(htmlspecialchars($address)) ?></p>
</div>
</body>
</html>
