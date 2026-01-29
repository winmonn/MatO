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

$query_stores = "SELECT StoreID, StoreName, Location, EmailAddress, PhoneNumber FROM stores";
$result_stores = $conn->query($query_stores);

$query_users = "
    SELECT 
        (SELECT COUNT(*) FROM customer) AS customer_count,
        (SELECT COUNT(*) FROM stores) AS store_count,
        (SELECT COUNT(*) FROM admins) AS admin_count
";

$result_users = $conn->query($query_users);
$user_counts = $result_users->fetch_assoc();
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Format2.css">
    <title>Stores</title>
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
                <p style="font-size: 1.5em; font-weight: bold;"><?php echo $user_counts['store_count']; ?></p>
                <p style="font-size: 1.5em;">Stores</p>
            </div>
            <div class="user-icon">
                <a href="RegisteredUsers.php">
                    <img src="./Icons/Users icon.png" alt="Users Icon">
                </a>
            </div>
        </div>
    </div>
    <div class="tformat">
        <h2>Store List</h2>
        <table>
            <tr>
                <th>Store ID</th>
                <th>Store Name</th>
                <th>Location</th>
                <th>Email Address</th>
                <th>Phone Number</th>
            </tr>
            <?php
            if ($result_stores->num_rows > 0) {
                while($row = $result_stores->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['StoreID'] . "</td>";
                    echo "<td>" . $row['StoreName'] . "</td>";
                    echo "<td>" . $row['Location'] . "</td>";
                    echo "<td>" . $row['EmailAddress'] . "</td>";
                    echo "<td>" . $row['PhoneNumber'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No stores found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
