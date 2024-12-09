<?php
require_once '../src/db_init.php';
require_once '../src/classes/DB.php';
require_once '../src/classes/Product.php';



$db = new Database();
$productHandler = new Product($db);

if (!isset($_GET['id'])) {
    die("Product ID is required.");
}

$productId = $_GET['id'];
$product = $productHandler->getProductById($productId);

if (!$product) {
    die("Product not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>


    <div class="container mt-5">
        <h1 class="mb-4"><?= htmlspecialchars($product['name']) ?></h1>
        <div class="row">
            <div class="col-md-6">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid">
            </div>
            <div class="col-md-6">
                <p><strong>Category:</strong> <?= htmlspecialchars($product['category']) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($product['description']) ?></p>
                <p><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>
                <form method="POST" action="add_to_cart.php">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" class="form-control mb-3">
                    <input type="hidden" name="product_id" value="<?= $productId ?>">
                    <button type="submit" class="btn btn-success">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <?php include 'login_signup_modal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>