<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$car_id = $_POST['car_id'] ?? null;
$amount = $_POST['amount'] ?? null;
$paypal_email = $_POST['paypal_email'] ?? null;

if (!$car_id || !$amount || !$paypal_email) {
    die("Invalid payment details.");
}

// Simulate payment processing (fake success logic)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment Success</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center text-success">Payment Successful!</h1>
        <p class="text-center">Thank you for your payment of <strong>$<?= htmlspecialchars($amount); ?></strong> for Car ID <strong><?= htmlspecialchars($car_id); ?></strong>.</p>
        <p class="text-center">The payment has been made using the PayPal email: <strong><?= htmlspecialchars($paypal_email); ?></strong>.</p>
        <div class="text-center mt-3">
            <a href="index.php" class="btn btn-secondary">Return to Home</a>
        </div>
    </div>
</body>
</html>
