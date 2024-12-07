<?php
require_once '../src/db_init.php';
require_once '../src/classes/DB.php';
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assests/style.css">
    <title>Codebooster: Toasters & Grillers</title>
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
                        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Orders</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Cart</a></li>
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

    <section class="hero">
        <div class="hero-content text-center text-light py-5">
            <h1>Codebooster: Toasters & Grillers</h1>
            <p>Your one-stop shop for high-quality toasters and Grillers. Perfect for your kitchen and outdoor adventures.</p>
            <a href="products.php" class="btn btn-light btn-lg mt-3">Shop Now</a>
        </div>
    </section>

    <main class="container mt-5">
        <h1>Welcome to Codebooster</h1>
        <p>Explore our range of toasters and grillers!</p>
        <?php if ($signup_message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($signup_message) ?></div>
        <?php elseif ($login_message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($login_message) ?></div>
        <?php endif; ?>
    </main>

    <section class="container mt-5">
        <h2>Why Choose Us?</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <img src="./assests/images/high-quality.jpg" class="card-img-top" alt="High Quality">
                    <div class="card-body">
                        <h5 class="card-title">High Quality</h5>
                        <p class="card-text">We offer the best toasters and grillers with exceptional durability and performance.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="./assests/images/affordable-prices.webp" class="card-img-top" alt="Affordable">
                    <div class="card-body">
                        <h5 class="card-title">Affordable Prices</h5>
                        <p class="card-text">Our products are priced competitively to provide you with the best value for your money.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="./assests/images/fast-delivery.png" class="card-img-top" alt="Fast Delivery">
                    <div class="card-body">
                        <h5 class="card-title">Fast Delivery</h5>
                        <p class="card-text">Enjoy fast and reliable delivery for all your orders, no matter where you are.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container mt-5">
        <h2>Customer Testimonials</h2>
        <div class="row">
            <div class="col-md-4">
                <blockquote class="blockquote">
                    <p>"Codebooster's grillers are top-notch! Perfect for my outdoor barbecues."</p>
                    <footer class="blockquote-footer">Meghana Rangashali</footer>
                </blockquote>
            </div>
            <div class="col-md-4">
                <blockquote class="blockquote">
                    <p>"Amazing quality and quick delivery. Highly recommended!"</p>
                    <footer class="blockquote-footer">
                        Divyang Nakrani
                    </footer>
                </blockquote>
            </div>

            <div class="col-md-4">
                <blockquote class="blockquote">
                    <p>"Amazing quality and quick delivery. Highly recommended!"</p>
                    <footer class="blockquote-footer">
                        Akshar Mangukiya
                    </footer>
                </blockquote>
            </div>
        </div>
    </section>

    <section class="container mt-5">
        <h2>Our Best Sellers</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <img src="./assests/images/compact-toaster.jpeg" class="card-img-top" alt="Product 1">
                    <div class="card-body">
                        <h5 class="card-title">Compact Toaster</h5>
                        <p class="card-text">$29.99</p>
                        <a href="products.php" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <img src="./assests/images/portable_griller.jpeg" class="card-img-top" alt="Product 2">
                    <div class="card-body">
                        <h5 class="card-title">Portable Griller</h5>
                        <p class="card-text">$69.99</p>
                        <a href="products.php" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'login_signup_modal.php'; ?>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; <?= date('Y') ?> Codebooster: Toasters & Grillers. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>