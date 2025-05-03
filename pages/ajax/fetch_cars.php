<?php
include '../../config.php';


$filter_brand = $_GET['brand'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$search_query = $_GET['search'] ?? '';

$sql = "SELECT * FROM cars WHERE 1=1";
$params = [];

if ($filter_brand) {
    $sql .= " AND brand = ?";
    $params[] = $filter_brand;
}
if ($min_price !== '') {
    $sql .= " AND price >= ?";
    $params[] = $min_price;
}
if ($max_price !== '') {
    $sql .= " AND price <= ?";
    $params[] = $max_price;
}
if ($search_query) {
    $sql .= " AND (name LIKE ? OR brand LIKE ?)";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
}

$sql .= " ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
<?php if (!empty($cars)): ?>
    <?php foreach ($cars as $car): ?>
        <?php $is_new = (strtotime($car['created_at'] ?? '') > strtotime('-7 days')); ?>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4">
        <div class="card car-card mb-4 shadow-sm">
                <?php if ($is_new): ?>
                    <span class="badge bg-danger position-absolute m-2">New</span>
                <?php endif; ?>
                <img src="../assets/images/<?= htmlspecialchars($car['image'] ?? 'default_car.jpg'); ?>" class="card-img-top" alt="<?= htmlspecialchars($car['name']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($car['name']); ?></h5>
                    <p class="card-text">
                        <?= htmlspecialchars($car['brand']); ?><br>
                        <strong class="text-success">$<?= number_format($car['price'], 0); ?></strong>
                    </p>
                    <button 
    class="btn btn-primary w-100 mb-2 view-details-btn" 
    data-bs-toggle="modal" 
    data-bs-target="#carDetailsModal" 
    data-id="<?= $car['id']; ?>">
    View Details
</button>


<button class="btn btn-contact w-100" data-bs-toggle="modal" data-bs-target="#contactModal" data-car="<?= htmlspecialchars($car['name']); ?>">Contact Seller</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="text-center text-muted">No cars found matching your criteria.</div>
<?php endif; ?>
</div>
