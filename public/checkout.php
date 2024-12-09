<?php
require_once '../src/db_init.php';
require_once '../src/classes/DB.php';
require_once '../src/classes/OrderHandler.php';
require_once '../src/classes/AuthHandler.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = htmlspecialchars(trim($_POST['first_name'] ?? ''));
    $last_name = htmlspecialchars(trim($_POST['last_name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $address = htmlspecialchars(trim($_POST['address'] ?? ''));
    $city = htmlspecialchars(trim($_POST['city'] ?? ''));
    $state = htmlspecialchars(trim($_POST['state'] ?? ''));
    $zip = htmlspecialchars(trim($_POST['zip'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $card_number = htmlspecialchars(trim($_POST['card_number'] ?? ''));
    $card_expiry = htmlspecialchars(trim($_POST['card_expiry'] ?? ''));
    $card_cvv = htmlspecialchars(trim($_POST['card_cvv'] ?? ''));

    if (empty($first_name)) $errors['first_name'] = "First Name is required.";
    if (empty($last_name)) $errors['last_name'] = "Last Name is required.";
    if (!$email) $errors['email'] = "A valid Email is required.";
    if (empty($address)) $errors['address'] = "Address is required.";
    if (empty($city)) $errors['city'] = "City is required.";
    if (empty($state)) $errors['state'] = "State is required.";
    if (empty($zip)) $errors['zip'] = "Zip Code is required.";
    if (empty($phone)) $errors['phone'] = "Phone number is required.";
    if (empty($card_number) || !preg_match('/^\d{16}$/', $card_number)) $errors['card_number'] = "Valid card number is required.";
    if (empty($card_expiry) || !preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $card_expiry)) $errors['card_expiry'] = "Valid expiry date (MM/YY) is required.";
    if (empty($card_cvv) || !preg_match('/^\d{3,4}$/', $card_cvv)) $errors['card_cvv'] = "Valid CVV is required.";

    if (empty($errors)) {
        try {
            $orderHandler = new OrderHandler($db);

            $customerData = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
            ];

            $orderId = $orderHandler->processOrder($customerData, $cart);
            $_SESSION['order_id'] = $orderId;
            header('Location: thank_you.php');
            unset($_SESSION['cart']);
        } catch (Exception $e) {
            $errors['general'] = "An error occurred while processing your order: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assests/style.css">
    <title>Checkout - Codebooster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <h1>Checkout</h1>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6">
                    <h4>Billing Details</h4>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>" id="first_name" name="first_name" value="<?= htmlspecialchars($first_name ?? '') ?>">
                        <div class="invalid-feedback"><?= $errors['first_name'] ?? '' ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>" id="last_name" name="last_name" value="<?= htmlspecialchars($last_name ?? '') ?>">
                        <div class="invalid-feedback"><?= $errors['last_name'] ?? '' ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
                        <div class="invalid-feedback"><?= $errors['email'] ?? '' ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" id="phone" name="phone" value="<?= htmlspecialchars($phone ?? '') ?>">
                        <div class="invalid-feedback"><?= $errors['phone'] ?? '' ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4>Shipping Details</h4>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control <?= isset($errors['address']) ? 'is-invalid' : '' ?>" id="address" name="address" value="<?= htmlspecialchars($address ?? '') ?>">
                        <div class="invalid-feedback"><?= $errors['address'] ?? '' ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control <?= isset($errors['city']) ? 'is-invalid' : '' ?>" id="city" name="city" value="<?= htmlspecialchars($city ?? '') ?>">
                        <div class="invalid-feedback"><?= $errors['city'] ?? '' ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="text" class="form-control <?= isset($errors['state']) ? 'is-invalid' : '' ?>" id="state" name="state" value="<?= htmlspecialchars($state ?? '') ?>">
                        <div class="invalid-feedback"><?= $errors['state'] ?? '' ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="zip" class="form-label">Zip Code</label>
                        <input type="text" class="form-control <?= isset($errors['zip']) ? 'is-invalid' : '' ?>" id="zip" name="zip" value="<?= htmlspecialchars($zip ?? '') ?>">
                        <div class="invalid-feedback"><?= $errors['zip'] ?? '' ?></div>
                    </div>
                </div>
            </div>
            <h4>Card Details</h4>
            <div class="mb-3">
                <label for="card_number" class="form-label">Card Number</label>
                <input type="text" class="form-control <?= isset($errors['card_number']) ? 'is-invalid' : '' ?>" id="card_number" name="card_number" value="<?= htmlspecialchars($card_number ?? '') ?>">
                <div class="invalid-feedback"><?= $errors['card_number'] ?? '' ?></div>
            </div>
            <div class="mb-3">
                <label for="card_expiry" class="form-label">Expiry Date (MM/YY)</label>
                <input type="text" class="form-control <?= isset($errors['card_expiry']) ? 'is-invalid' : '' ?>" id="card_expiry" name="card_expiry" value="<?= htmlspecialchars($card_expiry ?? '') ?>">
                <div class="invalid-feedback"><?= $errors['card_expiry'] ?? '' ?></div>
            </div>
            <div class="mb-3">
                <label for="card_cvv" class="form-label">CVV</label>
                <input type="text" class="form-control <?= isset($errors['card_cvv']) ? 'is-invalid' : '' ?>" id="card_cvv" name="card_cvv" value="<?= htmlspecialchars($card_cvv ?? '') ?>">
                <div class="invalid-feedback"><?= $errors['card_cvv'] ?? '' ?></div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Place Order</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>