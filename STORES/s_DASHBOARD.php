<?php
// Database connection parameters
$dbHost = 'localhost';
$dbName = 'Mat0';
$dbUsername = 'root';
$dbPassword = '';

// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

$storeID = $_SESSION['StoreID'];

// Query the database to retrieve the store name
$query = "SELECT StoreName FROM stores WHERE StoreID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $storeID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $store_data = $result->fetch_assoc();
    $storeName = $store_data['StoreName'];
} else {
    $storeName = 'Unknown Store';
}
$stmt->close();

// Query the database to get the counts for pending, delivering, and completed items
$query_pending = "SELECT COUNT(*) AS pending_count FROM orderitems oi
                  JOIN products p ON oi.product_id = p.ProductID
                  WHERE p.StoreID = ? AND oi.ostatus = 'pending'";
$stmt = $conn->prepare($query_pending);
$stmt->bind_param("i", $storeID);
$stmt->execute();
$result_pending = $stmt->get_result();
$pending_count = $result_pending->fetch_assoc()['pending_count'];
$stmt->close();

$query_delivering = "SELECT COUNT(*) AS delivering_count FROM orderitems oi
                    JOIN products p ON oi.product_id = p.ProductID
                    WHERE p.StoreID = ? AND oi.ostatus = 'Accepted'";
$stmt = $conn->prepare($query_delivering);
$stmt->bind_param("i", $storeID);
$stmt->execute();
$result_delivering = $stmt->get_result();
$delivering_count = $result_delivering->fetch_assoc()['delivering_count'];
$stmt->close();

$query_completed = "SELECT COUNT(*) AS completed_count FROM orderitems oi
                    JOIN products p ON oi.product_id = p.ProductID
                    WHERE p.StoreID = ? AND oi.ostatus = 'received'";
$stmt = $conn->prepare($query_completed);
$stmt->bind_param("i", $storeID);
$stmt->execute();
$result_completed = $stmt->get_result();
$completed_count = $result_completed->fetch_assoc()['completed_count'];
$stmt->close();

// Define filter variables and set default values
$filterStatus = isset($_POST['status']) ? $_POST['status'] : 'all';
$filterDateStart = isset($_POST['date_start']) ? $_POST['date_start'] : '';
$filterDateEnd = isset($_POST['date_end']) ? $_POST['date_end'] : '';

// Build the query with filters
$query = "SELECT o.OrderID, o.reference_number, CONCAT(c.FirstName, ' ', c.LastName) AS CustomerName, c.current_location, o.order_date, oi.ostatus AS order_status, (oi.quantity * p.Price) + 70 AS order_total, p.ProductName
          FROM orders o
          JOIN orderitems oi ON o.OrderID = oi.order_id
          JOIN products p ON oi.product_id = p.ProductID
          JOIN customer c ON o.customer_id = c.CustomerID
          WHERE p.StoreID = ?";

// Add status filter to the query
if ($filterStatus != 'all') {
    $query .= " AND oi.ostatus = ?";
}

// Add date range filter to the query
if (!empty($filterDateStart) && !empty($filterDateEnd)) {
    $query .= " AND o.order_date BETWEEN ? AND ?";
}

// Append order by clause
$query .= " ORDER BY o.order_date DESC, oi.order_id DESC LIMIT 5";

// Prepare and bind parameters
$stmt = $conn->prepare($query);

if ($filterStatus != 'all' && !empty($filterDateStart) && !empty($filterDateEnd)) {
    $stmt->bind_param("isss", $storeID, $filterStatus, $filterDateStart, $filterDateEnd);
} elseif ($filterStatus != 'all') {
    $stmt->bind_param("is", $storeID, $filterStatus);
} elseif (!empty($filterDateStart) && !empty($filterDateEnd)) {
    $stmt->bind_param("iss", $storeID, $filterDateStart, $filterDateEnd);
} else {
    $stmt->bind_param("i", $storeID);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="Dash.css">
</head>

<body>
  <div class="header-nav">
    <header>
      <div class="logo" >MatO</div>
      <div class="nav-item" onclick="location.href='./s_DASHBOARD.php'">
        <img class="nav-icon" src="../icons/home-icon.png" alt="Home" title="Dashboard">
      </div>
      <div class="nav-item" onclick="location.href='./s_PRODUCTS.php'">
        <img class="nav-icon" src="../icons/products-icon.png" alt="All Products" title="All Products">
      </div>

      <div class="nav-item" onclick="location.href='./s_ADD.php'">
        <img class="nav-icon" src="../icons/new-product.png" alt="All Products" title="All Products">
      </div>

    </header>
    <nav>
      <div class="account-cart">
        <a href="./s_ACCOUNT.php" class="user-link"><?php echo htmlspecialchars($storeName); ?></a>
      </div>
    </nav>
  </div>

  <div class="dashboard">
    <div>
      <div><?php echo $pending_count; ?></div>
      <div><a href="./s_PENDING.php">Pending</a></div>
    </div>
    <div>
      <div><?php echo $delivering_count; ?></div>
      <div><a href="./s_DELIVERED.php">Delivering</a></div>
    </div>
    <div>
      <div><?php echo $completed_count; ?></div>
      <div><a href="./s_COMPLETED.php">Completed</a></div>
    </div>
  </div>


  

  <div class="recent-orders">
    <h2>Recent Orders</h2>
    <table>
      <thead>
        <tr>
          <th>Reference Number</th>
          <th>Customer</th>
          <th>Location</th>
          <th>Order Date</th>
          <th>Order Status</th>
          <th>Order Total</th>
          <th>Product</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['reference_number']); ?></td>
              <td><?php echo htmlspecialchars($row['CustomerName']); ?></td>
              <td><?php echo htmlspecialchars($row['current_location']); ?></td>
              <td><?php echo htmlspecialchars($row['order_date']); ?></td>
              <td><?php echo htmlspecialchars($row['order_status']); ?></td>
              <td><?php echo number_format($row['order_total'], 2); ?></td>
              <td><?php echo htmlspecialchars($row['ProductName']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="7">No orders found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="chart">
    <!-- Chart content can be added here -->
  </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
