<?php
require_once '../src/db_init.php';
require_once '../src/classes/AccountHandler.php';
require_once '../src/classes/AuthHandler.php';


if (!isset($_SESSION['customer_id'])) {
    header("Location: index.php");
    exit;
}

$db = new Database();
$accountHandler = new AccountHandler($db);

$customerId = $_SESSION['customer_id'];
$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'first_name' => htmlspecialchars(trim($_POST['first_name'])),
        'last_name' => htmlspecialchars(trim($_POST['last_name'])),
        'email' => htmlspecialchars(trim($_POST['email'])),
        'phone' => htmlspecialchars(trim($_POST['phone'])),
        'address' => htmlspecialchars(trim($_POST['address']))
    ];

    if (empty($data['first_name'])) $errors['first_name'] = "First Name is required.";
    if (empty($data['last_name'])) $errors['last_name'] = "Last Name is required.";
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = "A valid Email is required.";
    if (empty($data['phone'])) $errors['phone'] = "Phone number is required.";
    if (empty($data['address'])) $errors['address'] = "Address is required.";

    if (empty($errors)) {
        if ($accountHandler->updateUserProfile($customerId, $data)) {
            $_SESSION['first_name'] = $data['first_name'];
            $success_message = "Profile updated successfully!";
        } else {
            $errors['general'] = "Failed to update profile. Please try again.";
        }
    }
}

$user = $accountHandler->getUserProfile($customerId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <h1>My Account</h1>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($success_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif (!empty($errors['general'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($errors['general']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>">
                <div class="invalid-feedback"><?= $errors['first_name'] ?? '' ?></div>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">
                <div class="invalid-feedback"><?= $errors['last_name'] ?? '' ?></div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                <div class="invalid-feedback"><?= $errors['email'] ?? '' ?></div>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                <div class="invalid-feedback"><?= $errors['phone'] ?? '' ?></div>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control <?= isset($errors['address']) ? 'is-invalid' : '' ?>" id="address" name="address" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                <div class="invalid-feedback"><?= $errors['address'] ?? '' ?></div>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
    <?php include 'login_signup_modal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>