<?php
require_once '../src/db_init.php';
require_once '../src/classes/DB.php';
require_once '../src/classes/AuthHandler.php';

function fetchPexelsImage($query)
{
    $apiKey = 'RH812gfiKdtH2p6Ii9IkGcQuUeYdS9OaHyUmmkET0NxxbPKOXYw6eqje';
    $url = "https://api.pexels.com/v1/search?query=" . urlencode($query) . "&per_page=1";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: $apiKey"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    return $data['photos'][0]['src']['large'] ?? null;
}

$toasterImage = fetchPexelsImage('toaster');
$grillerImage = fetchPexelsImage('griller');
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
    <?php include 'header.php'; ?>

    <section class="hero">
        <div class="hero-content text-center text-light py-5">
            <h1>Codebooster: Toasters & Grillers</h1>
            <p>Your one-stop shop for high-quality toasters and grillers. Perfect for your kitchen and outdoor adventures.</p>
            <a href="products.php" class="btn btn-light btn-lg mt-3">Shop Now</a>
        </div>
    </section>

    <main class="container mt-5">
        <h1>Welcome to Codebooster</h1>
        <p>Explore our range of toasters and grillers!</p>
        <?php if ($signup_message || $login_message): ?>
            <div class="alert <?= $signup_message ? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 1050;">
                <?= htmlspecialchars($signup_message ?? $login_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
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
                    <footer class="blockquote-footer">Divyang Nakrani</footer>
                </blockquote>
            </div>
            <div class="col-md-4">
                <blockquote class="blockquote">
                    <p>"Amazing quality and quick delivery. Highly recommended!"</p>
                    <footer class="blockquote-footer">Akshay Mangukiya</footer>
                </blockquote>
            </div>
        </div>
    </section>

    <section class="container mt-5">
        <h2>Our Best Sellers</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <img src="<?= $toasterImage ?>" class="card-img-top" alt="Compact Toaster">
                    <div class="card-body">
                        <h5 class="card-title">Compact Toaster</h5>
                        <p class="card-text">$29.99</p>
                        <a href="products.php" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <img src="<?= $grillerImage ?>" class="card-img-top" alt="Portable Griller">
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
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>