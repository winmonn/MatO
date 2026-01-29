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

$storeID = $_SESSION['StoreID'];
$OrderID = $_POST['OrderID'];
$action = $_GET['action'];

// Retrieve order items for the given order and store
$query = "SELECT oi.order_item_id
          FROM orderitems oi
          JOIN products p ON oi.product_id = p.ProductID
          WHERE oi.order_id = '$OrderID' AND p.StoreID = '$storeID'";
$orderItems = $conn->query($query);

if ($action == 'accept') {
    while ($item = $orderItems->fetch_assoc()) {
        $itemID = $item['order_item_id'];
        $query = "UPDATE orderitems SET ostatus = 'Accepted' WHERE order_item_id = '$itemID'";
        if (!$conn->query($query)) {
            die("Error updating order item status: " . $conn->error);
        }
    }
} elseif ($action == 'decline') {
    while ($item = $orderItems->fetch_assoc()) {
        $itemID = $item['order_item_id'];
        $query = "UPDATE orderitems SET ostatus = 'Declined' WHERE order_item_id = '$itemID'";
        if (!$conn->query($query)) {
            die("Error updating order item status: " . $conn->error);
        }
    }
}

// Redirect to the order status update page
header("Location: update_order_status.php?OrderID=$OrderID&storeID=$storeID");
?>
