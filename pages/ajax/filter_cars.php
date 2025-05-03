<?php
include '../config.php';

$search = $_POST['search'] ?? '';
$brand = $_POST['brand'] ?? '';
$min_price = $_POST['min_price'] ?? '';
$max_price = $_POST['max_price'] ?? '';
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM cars WHERE 1=1";
$params = [];

if (!empty($brand)) {
    $sql .= " AND brand = ?";
    $params[] = $brand;
}

if (!empty($min_price)) {
    $sql .= " AND price >= ?";
    $params[] = $min_price;
}

if (!empty($max_price)) {
    $sql .= " AND price <= ?";
    $params[] = $max_price;
}

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR brand LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Get total count
$total_sql = str_replace("SELECT *", "SELECT COUNT(*)", $sql);
$stmt = $conn->prepare($total_sql);
$stmt->execute($params);
$total = $stmt->fetchColumn();

// Apply pagination
$sql .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generate car cards
$cardsHtml = '';
foreach ($cars as $car) {
    $is_new = (strtotime($car['created_at']) > strtotime('-7 days'));
    $img = htmlspecialchars($car['image'] ?? 'default_car.jpg');
    $name = htmlspecialchars($car['name']);
    $brand = htmlspecialchars($car['brand']);
    $price = number_format($car['price'], 0);
    $id = $car['id'];

    $cardsHtml .= "
    <div class='col-md-4'>
        <div class='card mb-4 shadow-sm position-relative'>
            " . ($is_new ? "<span class='badge bg-danger position-absolute m-2'>New</span>" : "") . "
            <img src='../assets/images/$img' class='card-img-top' alt='$name'>
            <div class='card-body'>
                <h5 class='card-title'>$name</h5>
                <p class='text-muted'>$brand<br><strong class='text-success'>\$$price</strong></p>
                <a href='car_details.php?id=$id' class='btn btn-primary w-100 mb-2'>View Details</a>
                <button class='btn btn-outline-light w-100' data-bs-toggle='modal' data-bs-target='#contactModal' data-car='$name'>Contact Seller</button>
            </div>
        </div>
    </div>";
}

if (empty($cardsHtml)) {
    $cardsHtml = "<p class='text-center'>No cars found matching your criteria.</p>";
}

// Generate pagination
$totalPages = ceil($total / $limit);
$paginationHtml = '';
if ($totalPages > 1) {
    $paginationHtml .= "<nav><ul class='pagination justify-content-center'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = ($i == $page) ? 'active' : '';
        $paginationHtml .= "<li class='page-item $active'>
            <a class='page-link' href='#' data-page='$i'>$i</a>
        </li>";
    }
    $paginationHtml .= "</ul></nav>";
}

echo $cardsHtml . '<!--split-->' . $paginationHtml;
?>
