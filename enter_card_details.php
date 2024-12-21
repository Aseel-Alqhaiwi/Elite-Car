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
    <title>Enter Card Details</title>
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
        .error-message {
            color: red;
            font-size: 0.9rem;
        }
    </style>
    <script>
        // Basic validation for card details
        function validateCardForm() {
            let cardNumber = document.getElementById("card_number").value;
            let expiryDate = document.getElementById("expiry_date").value;
            let cvv = document.getElementById("cvv").value;
            let error = "";

            // Validate card number (must be 16 digits)
            if (!/^\d{16}$/.test(cardNumber)) {
                error += "Card number must be 16 digits.\\n";
            }

            // Validate expiry date (MM/YY)
            if (!/^\d{2}\/\d{2}$/.test(expiryDate)) {
                error += "Expiry date must be in MM/YY format.\\n";
            } else {
                // Validate the date is not in the past
                let [month, year] = expiryDate.split("/").map(Number);
                let now = new Date();
                let currentYear = now.getFullYear() % 100; // Get last two digits of the year
                let currentMonth = now.getMonth() + 1;

                if (year < currentYear || (year === currentYear && month < currentMonth)) {
                    error += "Expiry date cannot be in the past.\\n";
                }
            }

            // Validate CVV (must be 3 digits)
            if (!/^\d{3}$/.test(cvv)) {
                error += "CVV must be 3 digits.\\n";
            }

            // Show errors or allow form submission
            if (error) {
                alert(error);
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Enter Card Details</h1>
        <div class="credit-card-form">
            <h3>Pay $<?= htmlspecialchars($amount); ?> for Car ID <?= htmlspecialchars($car_id); ?></h3>
            <form action="fake_card_process.php" method="POST" onsubmit="return validateCardForm()">
                <input type="hidden" name="car_id" value="<?= $car_id; ?>">
                <input type="hidden" name="amount" value="<?= $amount; ?>">

                <div class="mb-3">
                    <label for="card_number" class="form-label">Card Number</label>
                    <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234567812345678" required>
                    <small class="error-message" id="card_number_error"></small>
                </div>

                <div class="mb-3">
                    <label for="card_holder" class="form-label">Card Holder Name</label>
                    <input type="text" class="form-control" id="card_holder" name="card_holder" placeholder="John Doe" required>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="expiry_date" class="form-label">Expiry Date (MM/YY)</label>
                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
                        <small class="error-message" id="expiry_date_error"></small>
                    </div>
                    <div class="col">
                        <label for="cvv" class="form-label">CVV</label>
                        <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123" required>
                        <small class="error-message" id="cvv_error"></small>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Pay Now</button>
            </form>
        </div>
    </div>
</body>
</html>
