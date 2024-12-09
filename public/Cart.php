<?php
require_once '../src/db_init.php';
require_once '../src/classes/DB.php';
require_once '../src/classes/Product.php';
require_once '../src/classes/AuthHandler.php';

$db = new Database();
$productHandler = new Product($db);

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$products = [];
$total_price = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            if ($action === 'increment') {
                $item['quantity']++;
            } elseif ($action === 'decrement' && $item['quantity'] > 1) {
                $item['quantity']--;
            }
            break;
        }
    }
    $_SESSION['cart'] = $cart;
    header("Location: cart.php");
    exit;
}

foreach ($cart as $item) {
    $product = $productHandler->getProductById($item['product_id']);
    if ($product) {
        $product['quantity'] = $item['quantity'];
        $product_total = $product['price'] * $item['quantity'];
        $total_price += $product_total;
        $products[] = $product;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Your Cart</h1>
        <?php if (count($products) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['name'] ?? 'Unknown') ?></td>
                            <td>$<?= htmlspecialchars(number_format($product['price'] ?? 0, 2)) ?></td>
                            <td><?= htmlspecialchars($product['quantity'] ?? 0) ?></td>
                            <td>$<?= htmlspecialchars(number_format(($product['price'] ?? 0) * ($product['quantity'] ?? 0), 2)) ?></td>
                            <td>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id'] ?? '') ?>">
                                    <input type="hidden" name="action" value="decrement">
                                    <button type="submit" name="update_quantity" class="btn btn-sm btn-warning">-</button>
                                </form>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id'] ?? '') ?>">
                                    <input type="hidden" name="action" value="increment">
                                    <button type="submit" name="update_quantity" class="btn btn-sm btn-success">+</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center">
                <h4>Total Price: $<?= number_format($total_price, 2) ?></h4>
                <?php if (isset($_SESSION['first_name'])): ?>
                    <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
                <?php else: ?>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#checkoutModal">Proceed to Checkout</button>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Login Required</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    You need to log in to proceed to the checkout. Please log in or create an account to continue.
                </div>
            </div>
        </div>
    </div>
    <?php include 'login_signup_modal.php'; ?>
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>