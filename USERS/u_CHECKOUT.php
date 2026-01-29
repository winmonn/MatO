<?php
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
$sql = "SELECT * FROM Customer WHERE CustomerID = '$customer_id'";
$result_customer = $conn->query($sql);
if ($result_customer) {
  $customer_data = $result_customer->fetch_assoc();
  $username = $customer_data['Username'];
} else {
  $username = 'Error'; // default value if query fails
}

// Retrieve the reference number from the URL
$reference_number = isset($_GET['ref']) ? $_GET['ref'] : 'N/A';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link rel="stylesheet" href="fstyles.css">
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
                <div class="notification-number">5</div>
                    <img class="nav-icon" src="../icons/cart-icon.png" alt="Cart" title="Cart">
                </div>
            </div>
        </nav>
    </div>

    <main>
        <div class="order-success-container">
            <div class="order-message">
                <h1>Order is now pending</h1>
                <p>THANK YOU FOR SHOPPING WITH MatO</p>
                <p>Your reference number is: <strong><?php echo $reference_number; ?></strong></p>
                <div class="buttons">
                    <button onclick="location.href='./u_HOME.php'">HOME</button>
                </div>
                <div class="order-links">
                    <span>Still missing something? <a href="./u_DEALS.php">Order more</a></span>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
