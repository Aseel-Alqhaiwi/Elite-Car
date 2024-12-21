<?php
include 'config.php';
session_start();

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Car not found!");
}

// Fetch car details
$sql = "SELECT * FROM cars WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) {
    die("Car not found!");
}

// Fetch reviews for this car
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
    <title><?= htmlspecialchars($car['name']); ?> Details</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1><?= htmlspecialchars($car['name']); ?></h1>
        <div class="row">
            <div class="col-md-6">
                <img src="assets/images/<?= htmlspecialchars($car['image']); ?>" class="img-fluid" alt="<?= htmlspecialchars($car['name']); ?>">
            </div>
            <div class="col-md-6">
                <h3>Brand: <?= htmlspecialchars($car['brand']); ?></h3>
                <h4>Price: $<?= number_format($car['price'], 0); ?></h4>
                <p><?= nl2br(htmlspecialchars($car['description'])); ?></p>

                <!-- Buttons Section -->
                <div class="mt-3">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="payment.php?id=<?= $car['id']; ?>&amount=<?= $car['price']; ?>" class="btn btn-success">Buy Now</a>
                        <?php else: ?>
                        <p><a href="login.php">Log in</a> to proceed with the purchase.</p>
                    <?php endif; ?>
                    <a href="index.php" class="btn btn-secondary ms-2">Back to Listings</a>
                </div>
            </div>
        </div>

        <hr>
        <h2>Reviews</h2>
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="mb-3">
                    <strong><?= htmlspecialchars($review['user_name']); ?></strong>
                    <span>Rating: <?= str_repeat('★', $review['rating']); ?></span>
                    <p><?= htmlspecialchars($review['comment']); ?></p>
                    <small class="text-muted">Posted on <?= $review['created_at']; ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No reviews yet. Be the first to leave a review!</p>
        <?php endif; ?>

        <hr>
        <!-- Review Form -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <h3>Leave a Review</h3>
            <form action="submit_review.php" method="POST">
                <input type="hidden" name="car_id" value="<?= $id; ?>">
                <div class="mb-3">
                    <label for="rating" class="form-label">Rating</label>
                    <select name="rating" class="form-select" required>
                        <option value="1">1 - Poor</option>
                        <option value="2">2 - Fair</option>
                        <option value="3">3 - Good</option>
                        <option value="4">4 - Very Good</option>
                        <option value="5">5 - Excellent</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea name="comment" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
        <?php else: ?>
            <p><a href="login.php">Log in</a> to leave a review.</p>
        <?php endif; ?>
    </div>
</body>
</html>
