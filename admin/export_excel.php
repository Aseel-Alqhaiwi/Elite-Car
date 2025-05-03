<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../config.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=cars_export_" . date("Y-m-d") . ".xls");

echo "<table border='1'>";
echo "<tr><th>ID</th><th>Name</th><th>Brand</th><th>Price</th></tr>";

$stmt = $conn->query("SELECT id, name, brand, price FROM cars ORDER BY created_at DESC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['brand']) . "</td>";
    echo "<td>$" . number_format($row['price'], 2) . "</td>";
    echo "</tr>";
}
echo "</table>";
