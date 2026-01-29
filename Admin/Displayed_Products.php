<?php
$dbHost = 'localhost';
$dbName = 'Mat0';
$dbUsername = 'root';
$dbPassword = '';

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if (!isset($_SESSION['AdminID'])) {
    die("Admin not logged in.");
}

$adminId = $_SESSION['AdminID'];

$sql_admin = "SELECT * FROM Admins WHERE AdminId = '$adminId'";
$result_admin = $conn->query($sql_admin);

$admin_data = $result_admin->fetch_assoc();

if (!$admin_data) {
    die("Admin not found.");
}

$query_products = "
    SELECT ProductID, ProductName, StoreID, Price, Quantity
    FROM products
    WHERE product_status = 'displayed';
";

$result_products = $conn->query($query_products);
$total_products = $result_products->num_rows;
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Format2.css">
    <title>Displayed Products</title>
</head>
<body>
<div class="header-nav">
    <header>
        <a href="Admin_Dashboard.php" style="text-decoration: none;">
            <div class="logo">MatO</div>
        </a>
        <div class="search-bar">
            <input type="text" placeholder="Search">
            <button onclick="location.href='Admin_Dashboard.php'">Search</button>
        </div>
    </header>
    <nav>
            <div class="account-cart">
                    <?php echo htmlspecialchars($admin_data['FirstName'] . ' ' . $admin_data['LastName']); ?>
            </div>
        </nav>
 </div>
 <div class="dashboard">
        <div class="card">
            <div class="text">
                <p style="font-size: 1.5em; font-weight: bold;"><?php echo $total_products ?></p>
                <p style="font-size: 1.5em;">Displayed Products</p>
            </div>
            <div class="user-icon">
                <a href="Products.php">
                    <img src="./Icons/Products Icon.png" alt="Users Icon">
                </a>
            </div>
        </div>
    </div>
    <div class="tformat">
        <h2>Displayed Products</h2>
        <table>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Store ID</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>
            <?php
            if ($result_products->num_rows > 0) {
                while($row = $result_products->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['ProductID'] . "</td>";
                    echo "<td>" . $row['ProductName'] . "</td>";
                    echo "<td>" . $row['StoreID'] . "</td>";
                    echo "<td>" . $row['Price'] . "</td>";
                    echo "<td>" . $row['Quantity'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No displayed products found.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
