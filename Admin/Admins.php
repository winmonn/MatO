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

$query_admins = "SELECT AdminId, CONCAT(FirstName, ' ', LastName) AS AdminName, Email, ContactNumber, DateJoined FROM admins";
$result_admins = $conn->query($query_admins);

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
    <title>Admins</title>
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
                <p style="font-size: 1.5em; font-weight: bold;"><?php echo $user_counts['admin_count']; ?></p>
                <p style="font-size: 1.5em;">Admins</p>
            </div>
            <div class="user-icon">
                <a href="RegisteredUsers.php">
                    <img src="./Icons/Users icon.png" alt="Users Icon">
                </a>
            </div>
        </div>
    </div>
    <div class="tformat">
        <h2>Admin List</h2>
        <table>
            <tr>
                <th>Admin ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact Number</th>
                <th>Date Joined</th>
            </tr>
            <?php
            if ($result_admins->num_rows > 0) {
                while($row = $result_admins->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['AdminId'] . "</td>";
                    echo "<td>" . $row['AdminName'] . "</td>";
                    echo "<td>" . $row['Email'] . "</td>";
                    echo "<td>" . $row['ContactNumber'] . "</td>";
                    echo "<td>" . $row['DateJoined'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No admins found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
