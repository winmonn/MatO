<?php
session_start();

$AdminID = $_SESSION['AdminID']; 

$dbHost = 'localhost';
$dbName = 'Mat0';
$dbUsername = 'root';
$dbPassword = '';

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['signout'])) {
    session_destroy();
    header('Location: ../HOME.php'); 
    exit;
}


$sql_admin = "SELECT * FROM Admins WHERE AdminID = '$AdminID'";
$result_admin = $conn->query($sql_admin);

$admin_data = $result_admin->fetch_assoc();

if (!$admin_data) {
    die("Admin not found.");
}


$query_users = "
    SELECT 
        (SELECT COUNT(*) FROM customer) AS customer_count,
        (SELECT COUNT(*) FROM stores) AS store_count,
        (SELECT COUNT(*) FROM admins) AS admin_count
";
$result_users = $conn->query($query_users);
$user_counts = $result_users->fetch_assoc();
$total_users = $user_counts['customer_count'] + $user_counts['store_count'] + $user_counts['admin_count'];

$query_total_orders = "SELECT COUNT(*) AS total_order_count FROM orderitems";
$result_total_orders = $conn->query($query_total_orders);
$total_orders = $result_total_orders->fetch_assoc()['total_order_count'];

$query_total_products = "SELECT COUNT(*) AS total_product_count FROM products";
$result_total_products = $conn->query($query_total_products);
$total_products = $result_total_products->fetch_assoc()['total_product_count'];

$query_recent_orders = "SELECT o.reference_number, CONCAT(c.FirstName, ' ', c.LastName) AS CustomerName, c.current_location, o.order_date, o.order_status, SUM(oi.quantity * p.Price) + 70 AS order_total
                        FROM orders o
                        JOIN orderitems oi ON o.OrderID = oi.order_id
                        JOIN products p ON oi.product_id = p.ProductID
                        JOIN customer c ON o.customer_id = c.CustomerID
                        GROUP BY o.reference_number, c.FirstName, c.LastName, c.current_location, o.order_date, o.order_status
                        ORDER BY o.order_date DESC
                        LIMIT 5";
$result_recent_orders = $conn->query($query_recent_orders);

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $search = mysqli_real_escape_string($conn, $search);

    $query_search_orders = "SELECT o.reference_number, CONCAT(c.FirstName, ' ', c.LastName) AS CustomerName, c.current_location, o.order_date, o.order_status, SUM(oi.quantity * p.Price) + 70 AS order_total
                            FROM orders o
                            JOIN orderitems oi ON o.OrderID = oi.order_id
                            JOIN products p ON oi.product_id = p.ProductID
                            JOIN customer c ON o.customer_id = c.CustomerID
                            WHERE o.reference_number LIKE '%$search%'
                            OR CONCAT(c.FirstName, ' ', c.LastName) LIKE '%$search%'
                            OR c.current_location LIKE '%$search%'
                            OR o.order_date LIKE '%$search%'
                            OR o.order_status LIKE '%$search%'
                            GROUP BY o.reference_number, c.FirstName, c.LastName, c.current_location, o.order_date, o.order_status
                            ORDER BY o.order_date DESC
                            LIMIT 5";
    $result_recent_orders = $conn->query($query_search_orders);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Admin Panel.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="header-nav">
        <header>
            <a href="Admin_Dashboard.php" style="text-decoration: none;">
                <div class="logo">MatO</div>
            </a>
            <div class="search-bar">
                <form action="Admin_Dashboard.php" method="GET">
                    <input type="text" name="search" placeholder="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
        </header>
        <nav>
            <div class="account-cart">
                    <div class="user-link">
                    <a href="Admin_account.php" style="text-decoration: none;">
                        <?php echo htmlspecialchars($admin_data['FirstName'] . ' ' . $admin_data['LastName']); ?>
                    </a>
                    </div>
        </nav>
    </div>
    <div class="dashboard">
        <a href="RegisteredUsers.php">
            <div class="card">
                <img src="./Icons/Users Icon.png" alt="Users Icon">
                <p><?php echo $total_users; ?></p>
                <p>Registered Users</p>
            </div>
        </a>
        <a href="Orders.php">
            <div class="card">
                <img src="./Icons/Orders Icon.jpg" alt="Orders Icon">
                <p><?php echo $total_orders; ?></p>
                <p>Orders</p>
            </div>
        </a>
        <a href="Products.php">
            <div class="card">
                <img src="./Icons/Products Icon.png" alt="Products Icon">
                <p><?php echo $total_products; ?></p>
                <p>Products</p>
            </div>
        </a>
    </div>
    <div class="orders">
        <h2>Recent Orders</h2>
        <table>
            <tr>
                <th>#</th>
                <th>Reference Number</th>
                <th>Customer</th>
                <th>Location</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
            <?php
            $ordernumber = 1; // Initialize order number counter
            if ($result_recent_orders->num_rows > 0) {
                while($row = $result_recent_orders->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $ordernumber++ . "</td>";
                    echo "<td>" . $row['reference_number'] . "</td>";
                    echo "<td>" . $row['CustomerName'] . "</td>";
                    echo "<td>" . $row['current_location'] . "</td>";
                    echo "<td>" . $row['order_date'] . "</td>";
                    echo "<td>" . $row['order_status'] . "</td>";
                    echo "<td>$" . $row['order_total'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No orders found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
