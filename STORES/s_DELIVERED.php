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
$query = "SELECT o.OrderID, o.reference_number, CONCAT(c.FirstName, ' ', c.LastName) AS CustomerName, c.current_location, o.order_date, SUM(oi.quantity * p.Price) as order_total
          FROM orders o
          JOIN orderitems oi ON o.OrderID = oi.order_id
          JOIN products p ON oi.product_id = p.ProductID
          JOIN customer c ON o.customer_id = c.CustomerID
          WHERE p.StoreID = '$storeID' AND oi.ostatus = 'Accepted'
          GROUP BY o.OrderID, o.reference_number, CONCAT(c.FirstName, ' ', c.LastName), c.current_location, o.order_date
          ORDER BY o.OrderID DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivered Orders</title>
    <link rel="stylesheet" href="de.css">
</head>

<body>
    <div class="header-nav">
        <header>
            <div class="logo" onclick="location.href='./s_DASHBOARD.php'">MatO</div>
            <div class="nav-item" onclick="location.href='./s_DASHBOARD.php'">
                <img class="nav-icon" src="../icons/home-icon.png" alt="Home" title="Dashboard">
            </div>
            <div class="nav-item" onclick="location.href='./s_PRODUCTS.php'">
                <img class="nav-icon" src="../icons/products-icon.png" alt="All Products" title="All Products">
            </div>

            <div class="nav-item" onclick="location.href='./s_ADD.php'">
                <img class="nav-icon" src="../icons/new-product.png" alt="All Products" title="All Products">
            </div>
        </header>

        <nav>
            <div class="account-cart">
                <a href="../s_LOGIN.php" class="user-link"><?php echo htmlspecialchars($storeName); ?></a>
            </div>
        </nav>
    </div>
    <main>
        <section class="pending-orders">
            <h1>Delivered Orders</h1>
            <table>
                <thead>
                    <tr>
                        <th>Reference Number</th>
                        <th>Customer</th>
                        <th>Location</th>
                        <th>Date Ordered</th>
                        <th>Order Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><a
                                    href="./s_pITEMS.php?OrderID=<?php echo $row['OrderID']; ?>&ReferenceNumber=<?php echo $row['reference_number']; ?>"><?php echo $row['reference_number']; ?></a>
                            </td>
                            <td><?php echo $row['CustomerName']; ?></td>
                            <td><?php echo $row['current_location']; ?></td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td>P <?php echo number_format($row['order_total'], 2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>