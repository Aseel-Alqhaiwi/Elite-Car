<?php include 'includes/header.php'; ?>

<?php
include 'config.php';

// Retrieve filter and pagination parameters
$filter_brand = $_GET['brand'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$search_query = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 6; // Number of cars per page
$offset = ($page - 1) * $limit;

// Build dynamic query for filters and search
$sql = "SELECT * FROM cars WHERE 1=1";
$params = [];

if ($filter_brand) {
    $sql .= " AND brand = ?";
    $params[] = $filter_brand;
}
if ($min_price) {
    $sql .= " AND price >= ?";
    $params[] = $min_price;
}
if ($max_price) {
    $sql .= " AND price <= ?";
    $params[] = $max_price;
}
if ($search_query) {
    $sql .= " AND (name LIKE ? OR brand LIKE ?)";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
}

// Count total cars for pagination
$total_sql = str_replace("SELECT *", "SELECT COUNT(*)", $sql);
$total_stmt = $conn->prepare($total_sql);
$total_stmt->execute($params);
$total_cars = $total_stmt->fetchColumn();

// Add LIMIT and OFFSET for pagination
$sql .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total pages
$total_pages = ceil($total_cars / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Car Marketplace</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Car Marketplace</h1>

        <!-- Filter and Search Form -->
        <form class="row mb-4" method="GET" action="">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search by name or brand" value="<?= htmlspecialchars($search_query); ?>">
            </div>
            <div class="col-md-3">
                <select name="brand" class="form-select">
                    <option value="">All Brands</option>
                    <option value="Nissan" <?= $filter_brand === 'Nissan' ? 'selected' : ''; ?>>Nissan</option>
                    <option value="Toyota" <?= $filter_brand === 'Toyota' ? 'selected' : ''; ?>>Toyota</option>
                    <option value="Honda" <?= $filter_brand === 'Honda' ? 'selected' : ''; ?>>Honda</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="<?= htmlspecialchars($min_price); ?>">
            </div>
            <div class="col-md-2">
                <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="<?= htmlspecialchars($max_price); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <!-- Display Cars -->
        <div class="row">
            <?php if (!empty($cars)): ?>
                <?php foreach ($cars as $car): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="assets/images/<?= htmlspecialchars($car['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($car['name']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($car['name']); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($car['brand']); ?> - $<?= number_format($car['price'], 0); ?></p>
                                <a href="car_details.php?id=<?= $car['id']; ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No cars found matching your criteria.</p>
            <?php endif; ?>
        </div>

        <!-- Pagination Controls -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">Previous</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
