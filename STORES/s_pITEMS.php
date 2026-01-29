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

$OrderID = $_GET['OrderID'];

// Query the database to retrieve the reference number for the order
$query = "SELECT reference_number FROM orders WHERE OrderID = '$OrderID'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $order_data = $result->fetch_assoc();
    $referenceNumber = $order_data['reference_number'];
} else {
    $referenceNumber = 'Unknown Reference Number';
}

// Query the database to retrieve the order items that belong to the store
$query = "SELECT oi.order_item_id, p.ProductName, oi.quantity, p.Price, p.Quantity
          FROM orderitems oi
          JOIN products p ON oi.product_id = p.ProductID
          WHERE oi.order_id = '$OrderID' AND p.StoreID = '$storeID'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending items</title>
    <link rel="stylesheet" href="pe.css">
</head>

<body>
<div class="header-nav">
    <header>
      <div class="logo"onclick="location.href='./s_DASHBOARD.php'">MatO</div>
      <div class="nav-item" onclick="location.href='./s_DASHBOARD.php'">
        <img class="nav-icon" src="../icons/home-icon.png" alt="Home" title="Dashboard">
      </div>
      <div class="nav-item" onclick="location.href='./s_PRODUCTS.php'">
        <img class="nav-icon" src="../icons/products-icon.png" alt="All Products" title="All Products">
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
            <h1>Pending Orders</h1>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Reference Number: <?php echo $referenceNumber; ?></h2>
            </div>

            <form id="order-items-form" method="POST" action="process_orderitems.php">
                <input type="hidden" name="OrderID" value="<?php echo $OrderID; ?>">
                <input type="hidden" name="StoreID" value="<?php echo $storeID; ?>">
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Stock Left</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['ProductName']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td>P <?php echo number_format($row['Price'], 2); ?></td>
                                <td><?php echo $row['Quantity']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </form>
        </section>
    </main>

    <script>
        document.getElementById('select-all-btn').addEventListener('click', function () {
            const checkboxes = document.querySelectorAll('input[name="orderitems[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = true);
        });

        document.getElementById('accept-btn').addEventListener('click', function () {
            document.getElementById('order-items-form').action = 'process_orderitems.php?action=accepts';
            document.getElementById('order-items-form').submit();
        });

        document.getElementById('decline-btn').addEventListener('click', function () {
            document.getElementById('order-items-form').action = 'process_orderitems.php?action=decline';
            document.getElementById('order-items-form').submit();
        });
    </script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>
