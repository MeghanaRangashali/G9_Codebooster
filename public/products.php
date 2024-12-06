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

$selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;

$categories = $productHandler->getAllCategories();

$products = $selectedCategory ? $productHandler->getProductsByCategory($selectedCategory) : $productHandler->getAllProducts();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Codebooster</title>
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

    <main class="container mt-5">
        <h1 class="mb-4">Our Products</h1>

        <form method="GET" action="products.php" class="mb-4">
            <label for="categoryFilter" class="form-label">Filter by Category:</label>
            <select name="category" id="categoryFilter" class="form-select" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category) ?>" <?= $selectedCategory == $category ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <div class="row">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                                <p class="card-text"><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>
                                <a href="product_details.php?id=<?= $product['product_id'] ?>" class="btn btn-warning">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No products found in this category.</p>
            <?php endif; ?>
        </div>
        <?php include 'login_signup_modal.php';?>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>