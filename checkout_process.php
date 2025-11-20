<?php
session_start();
require_once "db_write.php"; // writable DB

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_submitted = false;
$order_id = null;
$order_items = [];
$total_amount = 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $address = trim($_POST['address']);
    $payment_method = trim($_POST['payment_method']); // NEW

    if (empty($address) || empty($payment_method)) {
        $error = "Please complete all fields.";
    } else {

        // Fetch basket items
        $sql_basket = "
            SELECT c.product_id, c.quantity, p.price, p.name
            FROM user_cart c
            JOIN products p ON c.product_id = p.product_id
            WHERE c.user_id = ?
        ";
        $stmt = sqlsrv_query($conn_write, $sql_basket, [$user_id]);

        if ($stmt === false) {
            die("Failed to load basket: " . print_r(sqlsrv_errors(), true));
        }

        $items = [];
        $total_amount = 0;
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $items[] = $row;
            $total_amount += $row['price'] * $row['quantity'];
        }
        sqlsrv_free_stmt($stmt);

        if (count($items) === 0) {
            $error = "Your basket is empty.";
        } else {

            // Insert order and capture ID
            $sql_order = "
                INSERT INTO orders (user_id, total_amount, address, payment_method, status)
                OUTPUT INSERTED.order_id
                VALUES (?, ?, ?, ?, 'Pending');
            ";

            $params_order = [$user_id, $total_amount, $address, $payment_method];
            $stmt_order = sqlsrv_query($conn_write, $sql_order, $params_order);

            if ($stmt_order === false) {
                die("Order insert failed: " . print_r(sqlsrv_errors(), true));
            }

            $row_order = sqlsrv_fetch_array($stmt_order, SQLSRV_FETCH_ASSOC);
            $order_id = $row_order['order_id'];

            // Insert each item into order_items
            foreach ($items as $item) {
                $sql_item = "
                    INSERT INTO order_items (order_id, product_id, quantity, price)
                    VALUES (?, ?, ?, ?)
                ";
                $params_item = [$order_id, $item['product_id'], $item['quantity'], $item['price']];
                sqlsrv_query($conn_write, $sql_item, $params_item);
            }

            // Clear basket
            sqlsrv_query($conn_write, "DELETE FROM user_cart WHERE user_id = ?", [$user_id]);

            $order_items = $items;
            $order_submitted = true;
        }
    }
}
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
.error { color:red; font-weight:bold; }
footer { background:#f2f5f1; padding:15px; text-align:center; }
</style>
</head>
<body>

<?php include "header_nav.php"; ?>

<div class="container">

<?php if ($order_submitted): ?>

    <h2>Thank you for your order!</h2>
    <p>Your Order ID is: <strong><?= $order_id ?></strong></p>
    <p>Payment Method: <strong><?= htmlspecialchars($payment_method) ?></strong></p>

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

    <div class="total">Total Paid: £<?= number_format($total_amount, 2) ?></div>

<?php else: ?>

    <h2>Payment Failed</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

<?php endif; ?>

</div>

<footer>&copy; 2025 ShopSphere | Fresh, Local & Healthy</footer>

<?php sqlsrv_close($conn_write); ?>
</body>
</html>
