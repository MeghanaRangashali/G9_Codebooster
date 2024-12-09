<?php
require_once '../src/db_init.php';
require_once '../src/classes/OrderHandler.php';

if (!isset($_SESSION['order_id'])) {
    header('Location: index.php');
    exit;
}

$orderHandler = new OrderHandler(new Database());
$orderId = $_SESSION['order_id'];

try {
    $orderDetails = $orderHandler->getOrderDetails($orderId);
} catch (Exception $e) {
    $error_message = "Unable to fetch your order details. Please contact support.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <h1>Thank You for Your Order!</h1>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php else: ?>
            <p>Your order #<?= $orderDetails['order_id'] ?> has been successfully placed.</p>
            <p>Total Amount: $<?= number_format($orderDetails['total_amount'], 2) ?></p>
            <a href="generate_invoice.php?order_id=<?= $orderDetails['order_id'] ?>" target="_blank" class="btn btn-primary">Download Invoice</a>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>