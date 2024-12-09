<?php
require_once '../src/db_init.php';
require_once '../src/classes/DB.php';
require_once '../src/classes/Product.php';
require_once '../src/classes/AuthHandler.php';



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
    <?php include 'header.php'; ?>


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


        <?php include 'login_signup_modal.php'; ?>

    </main>
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>