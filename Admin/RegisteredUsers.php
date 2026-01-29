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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Format1.css">
    <title>Registered Users</title>
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
        <div class="container">
            <div class="card">
                <div class="text">
                    <p><?php echo $total_users; ?> Registered Users</p>
                </div>
                <a href="RegisteredUsers.php">
                    <div class="user-icon">
                        <img src="./Icons/Users Icon.png" alt="Users Icon">
                    </div>
                </a>
            </div>
            <div class="user-details">
                <a href="Admins.php" style="text-decoration: none;">
                    <p>Admins</p>
                </a>
                <p style="margin-left: auto;"><?php echo $user_counts['admin_count']; ?></p>
            </div>
            <div class="user-details">
                <a href="StoresList.php" style="text-decoration: none;">
                    <p>Stores</p>
                </a>
                <p style="margin-left: auto;"><?php echo $user_counts['store_count']; ?></p>
            </div>
            <div class="user-details">
                <a href="Customers.php" style="text-decoration: none;">
                    <p>Customers</p>
                </a>
                <p style="margin-left: auto;"><?php echo $user_counts['customer_count']; ?></p>
            </div>
        </div>
    </div>
</body>
</html>
