<?php
session_start();

$adminId = $_SESSION['AdminID'];

$dbHost = 'localhost';
$dbName = 'Mat0';
$dbUsername = 'root';
$dbPassword = '';

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getAdminName($conn, $adminId) {
    $sql_admin = "SELECT FirstName, LastName FROM Admins WHERE AdminId = ?";
    $stmt_admin = $conn->prepare($sql_admin);
    $stmt_admin->bind_param("i", $adminId);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();
    $admin_data = $result_admin->fetch_assoc();
    $stmt_admin->close();
    return $admin_data['FirstName'] . ' ' . $admin_data['LastName'];
}

$adminName = getAdminName($conn, $adminId);

$query_products = "SELECT COUNT(*) AS total_products FROM products";
$result_products = $conn->query($query_products);
$total_products = $result_products->fetch_assoc()['total_products'];

$query_in_stock = "SELECT COUNT(*) AS in_stock FROM products WHERE quantity > 0";
$result_in_stock = $conn->query($query_in_stock);
$in_stock_products = $result_in_stock->fetch_assoc()['in_stock'];

$query_out_of_stock = "SELECT COUNT(*) AS out_of_stock FROM products WHERE quantity = 0";
$result_out_of_stock = $conn->query($query_out_of_stock);
$out_of_stock_products = $result_out_of_stock->fetch_assoc()['out_of_stock'];

$query_low_in_stock = "SELECT COUNT(*) AS low_in_stock FROM products WHERE quantity > 0 AND quantity < 250";
$result_low_in_stock = $conn->query($query_low_in_stock);
$low_in_stock_products = $result_low_in_stock->fetch_assoc()['low_in_stock'];

$query_displayed = "SELECT COUNT(*) AS displayed FROM products WHERE product_status = 'displayed'";
$result_displayed = $conn->query($query_displayed);
$displayed_products = $result_displayed->fetch_assoc()['displayed'];

$query_pending = "SELECT COUNT(*) AS pending FROM products WHERE product_status = 'pending'";
$result_pending = $conn->query($query_pending);
$pending_products = $result_pending->fetch_assoc()['pending'];

$query_declined = "SELECT COUNT(*) AS declined FROM products WHERE product_status = 'declined'";
$result_declined = $conn->query($query_declined);
$declined_products = $result_declined->fetch_assoc()['declined'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Format1.css">
    <title>Products</title>
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
            <div class="user-link"><?php echo htmlspecialchars($adminName); ?></div>
        </div>
    </nav>
</div>
<div class="dashboard">
    <div class="container">
        <div class="card">
            <div class="text">
                <p><?php echo $total_products; ?> Products</p>
            </div>
            <div class="user-icon">
                <img src="./Icons/Products Icon.png" alt="Products Icon">
            </div>
        </div>
        <div class="user-details">
            <a href="In_Stock.php" style="text-decoration: none;">
                <p>Products In Stock</p>
            </a>
            <p style="margin-left: auto;"><?php echo $in_stock_products; ?></p>
        </div>
        <div class="user-details">
            <a href="OutOfStockProducts.php" style="text-decoration: none;">
                <p>Products Out Of Stock</p>
            </a>
            <p style="margin-left: auto;"><?php echo $out_of_stock_products ?></p>
        </div>
        <div class="user-details">
            <a href="LowInStock.php" style="text-decoration: none;">
                <p>Products Low In Stock</p>
            </a>
            <p style="margin-left: auto;"><?php echo $low_in_stock_products; ?></p>
        </div>
        <div class="user-details">
            <a href="Displayed_Products.php" style="text-decoration: none;">
                <p>Displayed Products</p>
            </a>
            <p style="margin-left: auto;"><?php echo $displayed_products; ?></p>
        </div>
        <div class="user-details">
            <a href="Pending_Products.php" style="text-decoration: none;">
                <p>Pending Products</p>
            </a>
            <p style="margin-left: auto;"><?php echo $pending_products; ?></p>
        </div>
        <div class="user-details">
            <a href="Declined_Products.php" style="text-decoration: none;">
                <p>Declined Products</p>
            </a>
            <p style="margin-left: auto;"><?php echo $declined_products; ?></p>
        </div>
    </div>
</div>
</body>
</html>

