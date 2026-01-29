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

// Get the product ID from the query string parameter
$productID = isset($_GET['ProductID']) ? $_GET['ProductID'] : '';

// Query to get the product details
$sql = "SELECT p.*, s.StoreName FROM products p INNER JOIN stores s ON p.StoreID = s.StoreID WHERE p.ProductID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productID);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Product</title>
    <link rel="stylesheet" href="Pstyles.css">
</head>

<body>
<div class="header-nav">
        <header>
            <div class="logo">MatO</div>
            
            <div class="nav-item" onclick="location.href='./HOME.php'">
                <img class="nav-icon" src="./icons/home-icon.png" alt="Home" title="Home">
            </div>
            <div class="nav-item" onclick="location.href='./Categories.php'">
                <img class="nav-icon" src="./icons/category-icon.svg" alt="All Category" title="All Category">
            </div>
            <div class="nav-item" onclick="location.href='./DEALS.php'">
                <img class="nav-icon" src="./icons/products-icon.png" alt="All Products" title="All Products">
            </div>
            <div class="nav-item" onclick="location.href='./ABOUT_US.php'">
                <img class="nav-icon" src="./icons/aboutus-icon.png" alt="About Us" title="About Us">
            </div>
        </header>
        <nav>
        
        <div class="account-cart">
                <div class="account" onclick="location.href='./SIGNUP.php'">Sign Up</div>
                <div class="account" onclick="location.href='./LOGIN.php'">Login</div>
                <div class="nav-cart" onclick="location.href='./LOGIN.php'">
                    <img class="nav-icon" src="icons/cart-icon.png" alt="Cart" title="Cart">
                </div>
            </div>
        </nav>
    </div>

    <main>
        <section class="product-section">
            <?php
            if ($product) {
                echo "<div class='product-left'>";
                echo "<img src='" . htmlspecialchars($product['Picture']) . "' alt='" . htmlspecialchars($product['ProductName']) . "' class='product-image' />";
                echo "<div class='product-description'>";
                echo "<h2>Description</h2>";
                echo "<p>" . htmlspecialchars($product['Description']) . "</p>";
                echo "</div>";
                echo "</div>";
                echo "<div class='product-right'>";
                echo "<h1 class='product-name'>" . htmlspecialchars($product['ProductName']) . "</h1>";
                echo "<div class='product-store'>by " . htmlspecialchars($product['StoreName']) . "</div>";
                echo "<div class='product-price'>Price: P" . htmlspecialchars($product['Price']) . "</div>";
                echo "<div class='product-quantity'>";
                echo "Quantity: <input type='number' value='1' min='1' />";
                echo "</div>";
                echo "<div class='product-buttons'>";
                echo "<button class='add-to-cart' onclick='checkLogin(\"add-to-cart\")'>Add to Cart</button>";
                echo "<button class='buy-now' onclick='checkLogin(\"buy-now\")'>Buy Now</button>";
                echo "</div>";
                echo "</div>";
            } else {
                echo "<div class='error'>Product details not found.</div>";
            }
            ?>
        </section>
    </main>


</body>

<script>
    function checkLogin(action) {
        // Check if user is logged in (you can use a PHP session or cookie to store login status)
        <?php if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) { ?>
            window.location.href = 'LOGIN.php';
        <?php } else { ?>
            // Perform action (add to cart or buy now)
            if (action === 'add-to-cart') {
                // Add to cart logic here
            } else if (action === 'buy-now') {
                // Buy now logic here
            }
        <?php } ?>
    }
</script>

</html>