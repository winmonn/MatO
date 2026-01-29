<?php
// Database connection details
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
$sql = "SELECT * FROM customer WHERE CustomerID = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result_customer = $stmt->get_result();
    if ($result_customer && $result_customer->num_rows > 0) {
        $customer_data = $result_customer->fetch_assoc();
        $username = $customer_data['Username'];
    } else {
        $username = 'Error'; // default value if query fails or no data found
    }
    $stmt->close();
} else {
    die("Error preparing statement: " . $conn->error);
}

// Retrieve accepted orders for the customer
$sql = "SELECT o.reference_number, o.order_date, oi.product_id, p.ProductName, p.Picture, oi.quantity, oi.unit_price, (oi.unit_price * oi.quantity) AS total_price
        FROM orderitems oi
        INNER JOIN products p ON oi.product_id = p.ProductID
        INNER JOIN orders o ON oi.order_id = o.OrderID
        WHERE oi.CustomerID = ? AND oi.ostatus IN ('completed', 'received')
        ORDER BY o.order_date DESC";


$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Error preparing statement: " . $conn->error);
}

$orders = [];
while ($row = $result->fetch_assoc()) {
    $refNumber = $row['reference_number'];
    if (!isset($orders[$refNumber])) {
        $orders[$refNumber] = [
            'order_date' => $row['order_date'],
            'total_price' => 0,
            'items' => []
        ];
    }
    $orders[$refNumber]['total_price'] += $row['total_price'];
    $orders[$refNumber]['items'][] = [
        'ProductName' => $row['ProductName'],
        'quantity' => $row['quantity'],
        'unit_price' => $row['unit_price'],
        'Picture' => $row['Picture'] // Add this line
    ];
}


$sql_cart_items = "SELECT COUNT(*) AS cart_count FROM orderitems WHERE CustomerID = '$customer_id' AND ostatus = 'cart'";
$result_cart_items = mysqli_query($conn, $sql_cart_items);

// Fetch the result as an associative array
$cart_count = mysqli_fetch_assoc($result_cart_items)['cart_count'];

// Fetch customer username
$sql_username = "SELECT Username FROM Customer WHERE CustomerID = '$customer_id'";
$result_username = mysqli_query($conn, $sql_username);
$username = mysqli_fetch_assoc($result_username)['Username'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivered Products</title>
    <link rel="stylesheet" href="Complete.css">
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

    
    <h1 onclick="location.href='./u_ACCOUNT.php'" style="cursor: pointer;">Completed Orders</h1>

    <main>
        
        <table>
            <thead>
                <tr>
                    <th>Reference Number</th>
                    <th>Order Date</th>
                    <th>Order Received</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($orders as $refNumber => $order) {
                    echo "<tr>";
                    echo "<td><a href='#' onclick='showOrderDetails(\"" . htmlspecialchars($refNumber) . "\")'>" . htmlspecialchars($refNumber) . "</a></td>";
                    echo "<td>" . htmlspecialchars($order['order_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($order['order_date']) . "</td>";
                    echo "<td>P " . number_format($order['total_price'], 2) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <div class="overlay" id="overlay">
        <div class="overlay-content">
            <span class="close-btn" onclick="hideOverlay()">×</span>
            <div id="order-details"></div>
        </div>
    </div>

    <script>
        var orders = <?php echo json_encode($orders); ?>;

        function showOrderDetails(refNumber) {
            var order = orders[refNumber];
            var details = '<h2>Order Details</h2>';
            details += '<p><strong>Reference Number:</strong> ' + refNumber + '</p>';
            details += '<p><strong>Order Date:</strong> ' + order.order_date + '</p>';
            details += '<table>';
            details += '<thead><tr><th>Product Image</th><th>Product Name</th><th>Quantity</th><th>Unit Price</th></tr></thead>';
            details += '<tbody>';
            order.items.forEach(function (item) {
                details += '<tr>';
                details += '<td><img src="' + item.Picture + '" alt="' + item.ProductName + '" style="width: 100px; height: 100px;"></td>';
                details += '<td>' + item.ProductName + '</td>';
                details += '<td>' + item.quantity + '</td>';
                details += '<td>P ' + Number(item.unit_price).toFixed(2) + '</td>';
                details += '</tr>';
            });
            details += '</tbody>';
            details += '</table>';
            details += '<p><strong>Total Payment:</strong> P ' + Number(order.total_price).toFixed(2) + '</p>';
            document.getElementById('order-details').innerHTML = details;
            document.getElementById('overlay').style.display = 'flex';
        }


        function hideOverlay() {
            document.getElementById('overlay').style.display = 'none';
        }
    </script>
</body>

</html>