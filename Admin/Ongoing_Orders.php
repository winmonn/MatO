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

$query_ongoing_count = "
    SELECT COUNT(*) AS ongoing_count
    FROM orderitems
    WHERE ostatus = 'ordered'
";

$result_ongoing_count = $conn->query($query_ongoing_count);
$ongoing_count = $result_ongoing_count->fetch_assoc()['ongoing_count'];

$query_ongoing_orders = "
    SELECT 
        order_item_id, 
        order_id, 
        product_id, 
        quantity, 
        unit_price, 
        CustomerID
    FROM 
        orderitems
    WHERE 
        ostatus = 'ordered';
";
$result_ongoing_orders = $conn->query($query_ongoing_orders);

?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Format2.css">
    <title>Ongoing Orders</title>
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
                <p style="font-size: 1.5em; font-weight: bold;"><?php echo $ongoing_count ?></p>
                <p style="font-size: 1.5em;">Ongoing Orders</p>
            </div>
            <div class="user-icon">
                <a href="Orders.php">
                    <img src="./Icons/Orders Icon.jpg" alt="Users Icon">
                </a>
            </div>
        </div>
    </div>
    <div class="tformat">
    <h2>Ongoing Orders</h2>
    <table>
        <tr>
            <th>Order Item ID</th>
            <th>Order ID</th>
            <th>Product ID</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Customer ID</th>
        </tr>
        <?php
        if ($result_ongoing_orders->num_rows > 0) {
            while($row = $result_ongoing_orders->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['order_item_id'] . "</td>";
                echo "<td>" . $row['order_id'] . "</td>";
                echo "<td>" . $row['product_id'] . "</td>";
                echo "<td>" . $row['quantity'] . "</td>";
                echo "<td>" . $row['unit_price'] . "</td>";
                echo "<td>" . $row['CustomerID'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No ongoing orders found</td></tr>";
        }
        ?>
    </table>
    </div>
</body>
</html>
