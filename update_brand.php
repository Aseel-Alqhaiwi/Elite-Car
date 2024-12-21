<?php
// Include the database configuration file to connect to the database
include 'config.php';

try {
    // SQL query to update all cars where the brand is 'Tesla' to 'Nissan'
    $sql = "UPDATE cars SET brand = 'Nissan' WHERE brand = 'Tesla'";

    // Prepare and execute the SQL query
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Output success message
    echo "Brand updated successfully from Tesla to Nissan.";
} catch (PDOException $e) {
    // Output error message if something goes wrong
    echo "Error updating brand: " . $e->getMessage();
}
