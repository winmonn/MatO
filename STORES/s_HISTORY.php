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

include 'getLastOrderNumber.php';

$storeID = $_SESSION['StoreID'];

$lastOrderNumber = 0; // default value
$newOrderNumber = 1; // default value

// Fetch the last order number
$query_order_number = "SELECT LastOrderNumber FROM store_order_counter WHERE StoreID = '$storeID'";
$result_order_number = $conn->query($query_order_number);
if ($result_order_number->num_rows > 0) {
    $lastOrderNumber = $result_order_number->fetch_assoc()['LastOrderNumber'];
    $newOrderNumber = $lastOrderNumber + 1; // generate a new order number
}


// Query the database to retrieve the store name
$query = "SELECT StoreName FROM stores WHERE StoreID = '$storeID'";
$result = $conn->query($query);

// Check if the query returned a result
if ($result->num_rows > 0) {
    $store_data = $result->fetch_assoc();
    $storeName = $store_data['StoreName'];
} else {
    $storeName = 'Unknown Store';
}

// Query the database to retrieve the pending orders for the seller's store
$query = "SELECT o.OrderID, CONCAT(c.FirstName, ' ', c.LastName) AS CustomerName, c.current_location, o.order_date, o.order_status, SUM(oi.quantity * p.Price) + 70 AS order_total
          FROM orders o
          JOIN orderitems oi ON o.OrderID = oi.order_id
          JOIN products p ON oi.product_id = p.ProductID
          JOIN customer c ON o.customer_id = c.CustomerID
          WHERE p.StoreID = '$storeID'
          GROUP BY o.OrderID, c.FirstName, c.LastName, c.current_location, o.order_date, o.order_status";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <link rel="stylesheet" href="history.css">
</head>

<body>
    <header>
        <div class="logo" onclick="location.href='./s_DASHBOARD.php'">MatO</div>
        <div class="user-links">
            <a href="../s_LOGIN.php" class="user-link"><?php echo $storeName; ?></a>
        </div>
    </header>

    <nav>
        <div class="nav-item" onclick="location.href='./s_DASHBOARD.php'">Dashboard</div>
        <div class="nav-item">Orders</div>
        <div class="nav-item">Products</div>
        <div class="nav-item">History</div>
        <div class="nav-item">Account</div>
    </nav>

    <div class="recent-orders">
        <h2>Recent Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>OrderID</th>
                    <th>Customer</th>
                    <th>Location</th>
                    <th>Order Date</th>
                    <th>Order Status</th>
                    <th>Order Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php
                    // Fetch the last order number
                    $query_order_number = "SELECT LastOrderNumber FROM store_order_counter WHERE StoreID = '$storeID'";
                    $result_order_number = $conn->query($query_order_number);
                    $order_number = 1; // Initialize order number
                    if ($result_order_number->num_rows > 0) {
                        $order_number = $result_order_number->fetch_assoc()['LastOrderNumber'] + 1;
                    }

                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $order_number++; ?></td>
                            <td><?php echo $row['OrderID']; ?></td>
                            <td><?php echo $row['CustomerName']; ?></td>
                            <td><?php echo $row['current_location']; ?></td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td><?php echo $row['order_status']; ?></td>
                            <td>P <?php echo number_format($row['order_total'], 2); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="chart">
        <!-- Chart content can be added here -->
    </div>

</body>

</html>
