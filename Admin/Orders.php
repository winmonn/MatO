<?php
session_start();

$dbHost = 'localhost';
$dbName = 'Mat0';
$dbUsername = 'root';
$dbPassword = '';

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the AdminId from the session
$adminId = $_SESSION['AdminID'];

$sql_admin = "SELECT * FROM Admins WHERE AdminId = '$adminId'";
$result_admin = $conn->query($sql_admin);

$admin_data = $result_admin->fetch_assoc();

if (!$admin_data) {
    die("Admin not found.");
}

$query_total_orders = "SELECT COUNT(*) AS total_order_count FROM orderitems";
$result_total_orders = $conn->query($query_total_orders);
$total_orders = $result_total_orders->fetch_assoc()['total_order_count'];

$query_completed_orders = "
    SELECT COUNT(*) AS completed_count
    FROM orderitems
    WHERE ostatus = 'Completed'
";

$result_completed_orders = $conn->query($query_completed_orders);
$completed_count = $result_completed_orders->fetch_assoc()['completed_count'];

$query_ongoing_orders = "
    SELECT COUNT(*) AS ongoing_count
    FROM orderitems
    WHERE ostatus = 'ordered'
";

$result_ongoing_orders = $conn->query($query_ongoing_orders);
$ongoing_count = $result_ongoing_orders->fetch_assoc()['ongoing_count'];

$query_declined_orders = "
    SELECT COUNT(*) AS declined_count
    FROM orderitems
    WHERE ostatus = 'Declined'
";

$result_declined_orders = $conn->query($query_declined_orders);
$declined_count = $result_declined_orders->fetch_assoc()['declined_count'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Format1.css">
    <title>Orders</title>
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
                <div class="user-link"><?php echo isset($admin_data['FirstName']) && isset($admin_data['LastName']) ? htmlspecialchars($admin_data['FirstName'] . ' ' . $admin_data['LastName']) : ''; ?></div>
            </div>
        </nav>
    </div>
    <div class="dashboard">
        <div class="container">
            <div class="card">
                <div class="text">
                    <p><?php echo $total_orders; ?> Orders</p>
                </div>
                <div class="user-icon">
                    <img src="./Icons/Orders Icon.jpg" alt="Users Icon">
                </div>
            </div>
            <div class="user-details">
                <a href="Completed_Orders.php" style="text-decoration: none;">
                    <p>Completed Orders</p>
                </a>
                <p style="margin-left: auto;"><?php echo $completed_count; ?></p>
            </div>
            <div class="user-details">
                <a href="Ongoing_Orders.php" style="text-decoration: none;">
                    <p>Ongoing Orders</p>
                </a>
                <p style="margin-left: auto;"><?php echo $ongoing_count; ?></p>
            </div>
            <div class="user-details">
                <a href="Declined_Orders.php" style="text-decoration: none;">
                    <p>Declined Orders</p>
                </a>
                <p style="margin-left: auto;"><?php echo $declined_count; ?></p>
            </div>
        </div>
    </div>
</body>
</html>
