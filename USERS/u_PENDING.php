<?php
// Database connection parameters
$dbHost = 'localhost';
$dbName = 'Mat0';
$dbUsername = 'root';
$dbPassword = '';
session_start();
$customer_id = $_SESSION['CustomerID'];

// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve customer information
$sql = "SELECT * FROM Customer WHERE CustomerID = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result_customer = $stmt->get_result();
    if ($result_customer) {
        $customer_data = $result_customer->fetch_assoc();
        $username = $customer_data['Username'];
    } else {
        $username = 'Error'; // default value if query fails
    }
    $stmt->close();
} else {
    die("Error preparing statement: " . $conn->error);
}

// Retrieve pending orders for the customer
$sql = "SELECT p.ProductName, o.order_date, (oi.unit_price * oi.quantity) AS total_price, o.reference_number
        FROM orderitems oi
        INNER JOIN products p ON oi.product_id = p.ProductID
        INNER JOIN orders o ON oi.order_id = o.OrderID
        WHERE oi.CustomerID = ? AND oi.ostatus IN ('pending', 'received')";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Error preparing statement: " . $conn->error);

}

$sql_cart_items = "SELECT COUNT(*) AS cart_count FROM orderitems WHERE CustomerID = '$customer_id' AND ostatus = 'cart'";
$result_cart_items = mysqli_query($conn, $sql_cart_items);

// Fetch the result as an associative array
$cart_count = mysqli_fetch_assoc($result_cart_items)['cart_count'];

// Fetch customer username
$sql_username = "SELECT Username FROM Customer WHERE CustomerID = '$customer_id'";
$result_username = mysqli_query($conn, $sql_username);
$username = mysqli_fetch_assoc($result_username)['Username'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Orders</title>
    <link rel="stylesheet" href="pend.css">
</head>

<body>
<div class="header-nav">
        <header>
            <div class="logo">MatO</div>
            
            <div class="nav-item" onclick="location.href='./u_HOME.php'">
                <img class="nav-icon" src="../icons/home-icon.png" alt="Home" title="Home">
            </div>
            <div class="nav-item" onclick="location.href='./u_Categories.php'">
                <img class="nav-icon" src="../icons/category-icon.svg" alt="All Category" title="All Category">
            </div>
            <div class="nav-item" onclick="location.href='./u_DEALS.php'">
                <img class="nav-icon" src="../icons/products-icon.png" alt="All Products" title="All Products">
            </div>
            <div class="nav-item" onclick="location.href='./u_ABOUT_US.php'">
                <img class="nav-icon" src="../icons/aboutus-icon.png" alt="About Us" title="About Us">
            </div>
        </header>
        <nav>
        
            <div class="account-cart">
            <div class="account" onclick="location.href='./u_ACCOUNT.php'">Account (<?php echo $username;?>) </div>
                <div class="nav-cart" onclick="location.href='./u_CART.php'"> 
                <div class="notification-number"><?php echo $cart_count; ?></div>
                    <img class="nav-icon" src="../icons/cart-icon.png" alt="Cart" title="Cart">
                </div>
            </div>
        </nav>
    </div>

    <h1 onclick="location.href='./u_ACCOUNT.php'" style="cursor: pointer;">Pending Orders</h1>

    <main>
        <table>
            <thead>
                <tr>
                    <th>Product name</th>
                    <th>Order Date</th>
                    <th>Payment</th>
                    <th>Reference number</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch and display the pending orders
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['ProductName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['order_date']) . "</td>";
                    echo "<td>P " . number_format($row['total_price'], 2) . "</td>";
                    echo "<td>" . htmlspecialchars($row['reference_number']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
</body>

</html>
<?php
// Close the database connection
$conn->close();
?>