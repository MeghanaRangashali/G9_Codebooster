<?php
require_once '../src/db_init.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assests/style.css">
    <title>Codebooster: Toasters & Grillers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
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
                        <li class="nav-item"><a class="nav-link" href="#">Login</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <section class="hero">
        <div class="hero-content">
            <h1>Codebooster: Toasters & Grillers</h1>
            <p>Your one-stop shop for high-quality toasters and Grillers. Perfect for your kitchen and outdoor adventures.</p>
            <a href="#" class="btn btn-primary btn-lg mt-3">Shop Now</a>
        </div>
    </section>
    <footer class="bg-dark text-center text-white py-3">
        &copy; 2024 G9 Codebooster. All rights reserved.
    </footer>
</body>

</html>