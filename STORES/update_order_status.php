<?php
// Database connection parameters
$dbHost = 'localhost';
$dbName = 'Mat0';
$dbUsername = 'root';
$dbPassword = '';

// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

$OrderID = $_GET['OrderID'];


// Check if all products from this store are accepted
$query = "SELECT COUNT(*) as totalItems, SUM(CASE WHEN ostatus = 'Accepted' THEN 1 ELSE 0 END) as acceptedItems
          FROM orderitems oi
          JOIN products p ON oi.product_id = p.ProductID
          WHERE oi.order_id = '$OrderID'";
$result = $conn->query($query);

if (!$result) {
    die("Error retrieving order items: " . $conn->error);
}

$row = $result->fetch_assoc();

if ($row['totalItems'] == $row['acceptedItems']) {
    $orderStatus = 'Delivering'; // Update order status to Delivering if all products are accepted
} elseif ($row['acceptedItems'] > 0) {
    $orderStatus = 'Partially Fulfilled'; // Update order status to Partially Fulfilled if some products are accepted
} else {
    $orderStatus = 'Declined'; // Update order status to Declined if no products are accepted
}

// Update order status
$query = "UPDATE orders SET order_status = '$orderStatus' WHERE OrderID = '$OrderID'";
if (!$conn->query($query)) {
    die("Error updating order status: " . $conn->error);
}

header("Location: s_PENDING.php");
?>
