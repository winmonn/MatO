<?php

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
// Retrieve the StoreID and StoreName from the session
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $productName = $_POST['product-name'];
    $productDescription = $_POST['product-description'];
    $productPrice = $_POST['product-price'];
    $productQuantity = $_POST['product-quantity'];

    // Sanitize inputs
    $productName = htmlspecialchars($productName);
    $productDescription = htmlspecialchars($productDescription);
    $productPrice = floatval($productPrice); 
    $productQuantity = intval($productQuantity); 

    // Prepare SQL statement to insert product
    $stmt = $conn->prepare("INSERT INTO products (ProductName, StoreID, Price, Quantity, Description, product_status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("sidis", $productName, $storeID, $productPrice, $productQuantity, $productDescription);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully!'); window.location.href = 's_ADD.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 's_ADD.php';</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
    exit;
}

// If not a POST request or after handling the POST request, continue to HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="add.css">
    <title>Seller Interface</title>
</head>
<body>
<div class="header-nav">
    <header>
      <div class="logo">MatO</div>
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

    <div class="container">
        <h1>Add New Product</h1>
        <form id="product-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="product-name">Product Name</label>
                <input type="text" id="product-name" name="product-name" required>
            </div>
            <div class="form-group">
                <label for="product-description">Description</label>
                <textarea id="product-description" name="product-description" required></textarea>
            </div>
            <div class="form-group">
                <label for="product-price">Price (P)</label>
                <input type="number" id="product-price" name="product-price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="product-quantity">Quantity</label>
                <input type="number" id="product-quantity" name="product-quantity" required>
            </div>
            <button type="submit">Add Product</button>
        </form>
    </div>

</body>
</html>
