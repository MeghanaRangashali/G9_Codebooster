<?php
require_once '../src/db_init.php';
require_once '../src/classes/OrderHandler.php';

$customerId = $_SESSION['customer_id'];
$orderHandler = new OrderHandler(new Database());
$sevenDaysAgo = date('Y-m-d H:i:s', strtotime('-7 days'));

try {
    $orders = $orderHandler->getOrdersByCustomerId();
} catch (Exception $e) {
    $orders = [];
    $error_message = "Unable to fetch your orders. Please try again later.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $orderId = $_POST['order_id'];
    try {
        $orderHandler->cancelOrder($orderId,  $sevenDaysAgo);
        header("Location: orders.php");
        exit;
    } catch (Exception $e) {
        $error_message = "Unable to cancel the order. " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <h1>Your Orders</h1>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php elseif (!empty($orders)): ?>
            <?php foreach ($orders as $orderId => $order): ?>
                <div class="card mb-3">
                    <div class="card-header">
                        Order #<?= $orderId ?> - <?= $order['order_date'] ?>
                    </div>
                    <div class="card-body">
                        <p><strong>Total Amount:</strong> $<?= number_format($order['total_amount'], 2) ?></p>
                        <h5>Items:</h5>
                        <ul>
                            <?php foreach ($order['items'] as $item): ?>
                                <li>Product ID: <?= $item['product_id'] ?>, Quantity: <?= $item['quantity'] ?>, Price: $<?= number_format($item['price'], 2) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if ($order['order_date'] >= $sevenDaysAgo): ?>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="order_id" value="<?= $orderId ?>">
                                <button type="submit" name="cancel_order" class="btn btn-danger">Cancel Order</button>
                            </form>
                        <?php else: ?>
                            <p class="text-muted">Order cannot be canceled as it is older than 7 days.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have no orders yet.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>