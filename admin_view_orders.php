<?php
session_start();
require_once "db_write.php"; // admin needs write access

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php?error=" . urlencode("Please log in as an admin."));
    exit();
}

// Update order status if admin submits the form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $update_sql = "UPDATE orders SET status = ? WHERE order_id = ?";
    $params = [$status, $order_id];

    $update_stmt = sqlsrv_query($conn_write, $update_sql, $params);

    if ($update_stmt === false) {
        die("Failed to update status: " . print_r(sqlsrv_errors(), true));
    }

    header("Location: admin_view_orders.php?msg=" . urlencode("Order updated successfully."));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ShopSphere Admin - Manage Orders</title>

<style>
body { font-family: 'Helvetica Neue', Arial; background:#fafafa; margin:0; color:#333; }
header, footer { background:#f2f5f1; padding:15px; text-align:center; }
h1 { color:#2e5d34; }
.container { max-width:1200px; margin:30px auto; padding:20px; background:white; border-radius:8px; }
table { width:100%; border-collapse: collapse; margin-top:20px; }
th, td { padding:10px; border:1px solid #ddd; }
th { background:#2e5d34; color:white; }
.status { font-weight:bold; }
.status.Pending { color:orange; }
.status["Out for Delivery"] { color:blue; }
.status.Delivered { color:green; }
select { padding:6px; border-radius:4px; }
button { padding:6px 12px; background:#2e5d34; color:white; border:none; border-radius:4px; cursor:pointer; }
button:hover { background:#244928; }
.message { background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:10px; border:1px solid #c3e6cb; }
</style>
</head>

<body>

<header>
    <h1>Admin - View & Manage All Orders</h1>
</header>

<div class="container">

<?php if (isset($_GET['msg'])): ?>
    <div class="message"><?= htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>

<?php
// Fetch all orders + user info + payment method
$sql_orders = "
SELECT o.order_id, o.user_id, u.name AS customer_name, 
       o.total_amount, o.address, o.status, o.created_at, 
       o.payment_method
FROM orders o
LEFT JOIN users u ON o.user_id = u.id
ORDER BY o.created_at DESC";

$stmt_orders = sqlsrv_query($conn_write, $sql_orders);

if ($stmt_orders === false) {
    die("Failed to retrieve orders: " . print_r(sqlsrv_errors(), true));
}
?>

<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>User</th>
            <th>Payment Method</th>
            <th>Address</th>
            <th>Total (£)</th>
            <th>Status</th>
            <th>Update Status</th>
            <th>Placed At</th>
        </tr>
    </thead>
    <tbody>

<?php while ($order = sqlsrv_fetch_array($stmt_orders, SQLSRV_FETCH_ASSOC)): ?>
    <tr>
        <td><?= $order['order_id'] ?></td>

        <td><?= htmlspecialchars($order['customer_name'] ?? "Unknown") ?></td>

        <td><?= htmlspecialchars($order['payment_method'] ?? "N/A") ?></td>

        <td><?= htmlspecialchars($order['address']) ?></td>

        <td><?= number_format($order['total_amount'], 2) ?></td>

        <td class="status <?= htmlspecialchars($order['status']) ?>">
            <?= htmlspecialchars($order['status']) ?>
        </td>

        <td>
            <form method="post" style="display:flex; gap:5px;">
                <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">

                <select name="status">
                    <option value="Pending" <?= $order['status'] === "Pending" ? "selected" : "" ?>>Pending</option>
                    <option value="Out for Delivery" <?= $order['status'] === "Out for Delivery" ? "selected" : "" ?>>Out for Delivery</option>
                    <option value="Delivered" <?= $order['status'] === "Delivered" ? "selected" : "" ?>>Delivered</option>
                </select>

                <button type="submit">Update</button>
            </form>
        </td>

        <td>
            <?= ($order['created_at'] instanceof DateTime)
                ? $order['created_at']->format('Y-m-d H:i')
                : htmlspecialchars($order['created_at']); ?>
        </td>
    </tr>
<?php endwhile; ?>

    </tbody>
</table>

<?php
sqlsrv_free_stmt($stmt_orders);
sqlsrv_close($conn_write);
?>

</div>

<footer>
    &copy; 2025 ShopSphere | Admin Panel
</footer>

</body>
</html>
