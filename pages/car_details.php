<?php
include '../config.php';
session_start();

$id = $_GET['id'] ?? null;
if (!$id) die("Car not found!");

$sql = "SELECT * FROM cars WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$car) die("Car not found!");

$sql_reviews = "SELECT reviews.*, users.name AS user_name FROM reviews 
                JOIN users ON reviews.user_id = users.id 
                WHERE car_id = ? ORDER BY created_at DESC";
$stmt_reviews = $conn->prepare($sql_reviews);
$stmt_reviews->execute([$id]);
$reviews = $stmt_reviews->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= htmlspecialchars($car['name']); ?> | Elite Cars</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .car-image {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            object-fit: cover;
            max-height: 450px;
            width: 100%;
        }
        .rating-stars {
            color: #ffc107;
        }
        .btn-back {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>

<a href="index.php" class="btn btn-outline-primary btn-back"><i class="bi bi-arrow-left"></i> Back</a>

<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <img src="../assets/images/<?= htmlspecialchars($car['image']); ?>" alt="<?= htmlspecialchars($car['name']); ?>" class="car-image">
        </div>
        <div class="col-md-6 mt-4 mt-md-0">
            <h1 class="fw-bold"><?= htmlspecialchars($car['name']); ?></h1>
            <h4 class="text-muted"><i class="bi bi-car-front"></i> <?= htmlspecialchars($car['brand']); ?></h4>
            <h3 class="text-success my-3"><i class="bi bi-currency-dollar"></i> <?= number_format($car['price'], 0); ?></h3>
            <p><?= nl2br(htmlspecialchars($car['description'])); ?></p>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="payment.php?id=<?= $car['id']; ?>&amount=<?= $car['price']; ?>" class="btn btn-success"><i class="bi bi-credit-card"></i> Buy Now</a>
            <?php else: ?>
                <div class="alert alert-warning mt-3">Please <a href="login.php">log in</a> to make a purchase.</div>
            <?php endif; ?>
        </div>
    </div>

    <hr class="my-5">

    <h2 class="mb-4">Customer Reviews</h2>
    <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $review): ?>
            <div class="mb-4 border rounded p-3 bg-white shadow-sm">
                <h6 class="mb-1 fw-bold"><?= htmlspecialchars($review['user_name']); ?></h6>
                <div class="rating-stars mb-1"><?= str_repeat('★', $review['rating']); ?></div>
                <p class="mb-1"><?= htmlspecialchars($review['comment']); ?></p>
                <small class="text-muted">Posted on <?= date('F j, Y', strtotime($review['created_at'])); ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No reviews yet. Be the first to leave a review.</p>
    <?php endif; ?>

    <hr class="my-5">

    <?php if (isset($_SESSION['user_id'])): ?>
        <h3 class="mb-3">Leave a Review</h3>
        <form action="submit_review.php" method="POST" class="bg-light p-4 rounded shadow-sm">
            <input type="hidden" name="car_id" value="<?= $id; ?>">
            <div class="mb-3">
                <label for="rating" class="form-label">Rating</label>
                <select name="rating" class="form-select" required>
                    <option value="">Choose rating</option>
                    <option value="5">★★★★★ - Excellent</option>
                    <option value="4">★★★★ - Very Good</option>
                    <option value="3">★★★ - Good</option>
                    <option value="2">★★ - Fair</option>
                    <option value="1">★ - Poor</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label">Your Comment</label>
                <textarea name="comment" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
    <?php else: ?>
        <p class="mt-4"><a href="login.php">Log in</a> to leave a review.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
