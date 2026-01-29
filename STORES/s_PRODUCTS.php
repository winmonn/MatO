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

// Determine the sorting order
$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'ProductName';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

// Toggle sorting order
$new_sort_order = ($sort_order == 'ASC') ? 'DESC' : 'ASC';

// Query the database to retrieve all products from the seller's store
$query = "SELECT ProductID, ProductName, Price, Quantity, Description, Picture
          FROM products
          WHERE StoreID = '$storeID' AND product_status = 'displayed'
          ORDER BY $sort_column $sort_order";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products</title>
    <link rel="stylesheet" href="pe.css">
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
        <section class="all-products">
            <h1>All Products</h1>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th class = "user-link"><a href="?sort_column=Price&sort_order=<?php echo $new_sort_order; ?>" class>Price</a></th>
                        <th><a href="?sort_column=Quantity&sort_order=<?php echo $new_sort_order; ?>">Quantity</a></th>
                        <th>Description</th>
                        <th>Picture</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['ProductName']; ?></td>
                            <td>P <?php echo number_format($row['Price'], 2); ?></td>
                            <td><?php echo $row['Quantity']; ?></td>
                            <td><?php echo $row['Description']; ?></td>
                            <td><img src="<?php echo $row['Picture']; ?>" alt="<?php echo $row['ProductName']; ?>" style="max-width: 100px;"></td>
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
