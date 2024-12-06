<?php
require_once '../src/db_init.php';
require_once '../src/classes/DB.php';
require_once '../src/classes/Product.php';
require_once '../src/classes/AuthHandler.php';

$signup_message = isset($_SESSION['signup_message']) ? $_SESSION['signup_message'] : null;
$login_message = isset($_SESSION['login_message']) ? $_SESSION['login_message'] : null;
unset($_SESSION['signup_message'], $_SESSION['login_message']);

$logged_in_user = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : null;

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

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
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Codebooster Grillers And Toasters</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Orders</a></li>
                        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
                        <?php if ($logged_in_user): ?>
                            <li class="nav-item">
                                <span class="nav-link">Welcome, <?= htmlspecialchars($logged_in_user) ?></span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?logout=true">Logout</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginSignupModal">Login</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
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
        <?php include 'login_signup_modal.php';?>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>