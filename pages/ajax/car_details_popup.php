<?php
include '../../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo '<div class="text-danger">Car ID missing.</div>';
    exit;
}

$stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) {
    echo '<div class="text-danger">Car not found.</div>';
    exit;
}
?>

<div class="row">
    <div class="col-md-6">
    <img src="/CarElite/assets/images/<?= htmlspecialchars($car['image']); ?>" alt="<?= htmlspecialchars($car['name']); ?>" class="img-fluid rounded mb-3">
    </div>
    <div class="col-md-6">
        <h4><?= htmlspecialchars($car['name']); ?></h4>
        <p><strong>Brand:</strong> <?= htmlspecialchars($car['brand']); ?></p>
        <p><strong>Price:</strong> $<?= number_format($car['price'], 0); ?></p>
        <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($car['description'])); ?></p>
    </div>
</div>
