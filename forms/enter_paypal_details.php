<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$car_id = $_GET['id'] ?? null;
$amount = $_GET['amount'] ?? null;

if (!$car_id || !$amount) {
    die("Invalid payment details.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Enter PayPal Details</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        .paypal-form {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Enter PayPal Details</h1>
        <div class="paypal-form">
            <h3>Pay $<?= htmlspecialchars($amount); ?> for Car ID <?= htmlspecialchars($car_id); ?></h3>
            <form action="fake_paypal_process.php" method="POST">
                <input type="hidden" name="car_id" value="<?= $car_id; ?>">
                <input type="hidden" name="amount" value="<?= $amount; ?>">

                <div class="mb-3">
                    <label for="paypal_email" class="form-label">PayPal Email</label>
                    <input type="email" class="form-control" id="paypal_email" name="paypal_email" placeholder="example@paypal.com" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Pay Now</button>
            </form>
        </div>
    </div>
</body>
</html>
