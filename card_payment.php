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
    <title>Card Payment</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        .credit-card-form {
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
        <h1 class="text-center">Card Payment</h1>
        <div class="credit-card-form">
            <h3>Pay $<?= htmlspecialchars($amount); ?> for Car ID <?= htmlspecialchars($car_id); ?></h3>
            <form action="fake_card_process.php" method="POST">
                <input type="hidden" name="car_id" value="<?= $car_id; ?>">
                <input type="hidden" name="amount" value="<?= $amount; ?>">

                <div class="mb-3">
                    <label for="card_number" class="form-label">Card Number</label>
                    <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" required>
                </div>

                <div class="mb-3">
                    <label for="card_holder" class="form-label">Card Holder Name</label>
                    <input type="text" class="form-control" id="card_holder" name="card_holder" placeholder="John Doe" required>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
                    </div>
                    <div class="col">
                        <label for="cvv" class="form-label">CVV</label>
                        <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Pay Now</button>
            </form>
        </div>
    </div>
</body>
</html>
