<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$car_id = $_POST['car_id'] ?? null;
$amount = $_POST['amount'] ?? null;

if (!$car_id || !$amount) {
    die("Invalid payment details.");
}

// Fake payment logic
$card_number = $_POST['card_number'] ?? '';
$card_holder = $_POST['card_holder'] ?? '';
$expiry_date = $_POST['expiry_date'] ?? '';
$cvv = $_POST['cvv'] ?? '';

// Here we simply display a success message
// In a real-world app, you'd use a payment processor like Stripe or PayPal.
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
        <p class="text-center">Thank you, <?= htmlspecialchars($card_holder); ?>. Your payment of $<?= htmlspecialchars($amount); ?> for Car ID <?= htmlspecialchars($car_id); ?> was successful.</p>
        <div class="text-center mt-3">
            <a href="index.php" class="btn btn-secondary">Return to Home</a>
        </div>
    </div>
</body>
</html>
