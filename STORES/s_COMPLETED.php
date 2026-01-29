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

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the order ID and store ID from the form
    $orderID = $_POST['OrderID'];
    $storeID = $_POST['storeID'];

    // Update the orderitems table to mark the order as completed
    $query = "UPDATE orderitems SET ostatus = 'completed' WHERE order_id = ? AND product_id IN (SELECT ProductID FROM products WHERE StoreID = ?)";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ii", $orderID, $storeID);
        if ($stmt->execute()) {
        } else {
            echo "Error marking order as completed: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Query the database to retrieve the store name
$query = "SELECT StoreName FROM stores WHERE StoreID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $storeID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $store_data = $result->fetch_assoc();
    $storeName = $store_data['StoreName'];
} else {
    $storeName = 'Unknown Store';
}
$stmt->close();

// Query the database to retrieve the pending orders for the seller's store
$query = "SELECT o.OrderID, o.reference_number, CONCAT(c.FirstName, ' ', c.LastName) AS CustomerName, c.current_location, o.order_date, SUM(oi.quantity * p.Price) as order_total
          FROM orders o
          JOIN orderitems oi ON o.OrderID = oi.order_id
          JOIN products p ON oi.product_id = p.ProductID
          JOIN customer c ON o.customer_id = c.CustomerID
          WHERE p.StoreID = ? AND oi.ostatus = 'received'
          GROUP BY o.OrderID, o.reference_number, CONCAT(c.FirstName, ' ', c.LastName), c.current_location, o.order_date
          ORDER BY o.OrderID DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $storeID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Orders</title>
    <link rel="stylesheet" href="de.css">
</head>

<body>
    <div class="header-nav">
        <header>
            <div class="logo">MatO</div>
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
            <h1>Fulfilled Orders</h1>
            <table>
                <thead>
                    <tr>
                        <th>Reference Number</th>
                        <th>Customer</th>
                        <th>Location</th>
                        <th>Date Ordered</th>
                        <th>Order Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><a
                                    href="./s_pITEMS.php?OrderID=<?php echo $row['OrderID']; ?>&ReferenceNumber=<?php echo htmlspecialchars($row['reference_number']); ?>"><?php echo htmlspecialchars($row['reference_number']); ?></a>
                            </td>
                            <td><?php echo htmlspecialchars($row['CustomerName']); ?></td>
                            <td><?php echo htmlspecialchars($row['current_location']); ?></td>
                            <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                            <td><?php echo number_format($row['order_total'], 2); ?></td>
                            <td>
                                <form action="" method="post" style="display:inline;">
                                    <input type="hidden" name="OrderID" value="<?php echo $row['OrderID']; ?>">
                                    <input type="hidden" name="storeID" value="<?php echo $storeID; ?>">
                                    <button type="submit" class="accept-btn">Mark as Completed</button>
                                </form>
                            </td>
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