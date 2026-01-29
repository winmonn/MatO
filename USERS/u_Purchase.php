<?php
session_start();

// Retrieve the CustomerID from the session
$customer_id = $_SESSION['CustomerID'];

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

$sqlname = "SELECT Username FROM Customer WHERE CustomerID = '$customer_id'";
$result_name = mysqli_query($conn, $sqlname);
if ($result_name) {
    $customer_data = mysqli_fetch_assoc($result_name);
    $username = $customer_data['Username'];
} else {
    $username = 'Error'; // default value if query fails
}

if (isset($_GET['add-to-cart'])) {
    $quantity = isset($_GET['quantity']) ? $_GET['quantity'] : 1;
    $sql = "INSERT INTO orderitems (product_id, CustomerID, quantity, ostatus) VALUES (?,?, ?, 'cart')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $productID, $customer_id, $quantity);
    $stmt->execute();
    $message = "Product added to cart successfully!";
}

if (isset($_GET['buy-now'])) {
    $quantity = isset($_GET['quantity']) ? $_GET['quantity'] : 1;
    $sql = "INSERT INTO orderitems (product_id, CustomerID, quantity, ostatus) VALUES (?, ?, ?, 'cart')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $productID, $customer_id, $quantity);
    $stmt->execute();
    header('Location: ./u_CART.php?buy_now_product=' . $productID);
    exit;
}

$sql_cart_items = "SELECT COUNT(*) AS cart_count FROM orderitems WHERE CustomerID = '$customer_id' AND ostatus = 'cart'";
$result_cart_items = mysqli_query($conn, $sql_cart_items);

// Fetch the result as an associative array
$cart_count = mysqli_fetch_assoc($result_cart_items)['cart_count'];

// Fetch customer username
$sql_username = "SELECT Username FROM Customer WHERE CustomerID = '$customer_id'";
$result_username = mysqli_query($conn, $sql_username);
$username = mysqli_fetch_assoc($result_username)['Username'];


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
        <div class="logo"  onclick="location.href='./u_HOME.php'" style="cursor: pointer;">MatO</div>
            
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
                echo "Quantity: <input type='number' value='1' min='1' name='quantity' />";
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

    <p>
        <script>
            function checkLogin(action) {
                if (<?php echo isset($_SESSION['CustomerID']) ? 'true' : 'false'; ?>) {
                    if (action === 'add-to-cart') {
                        var quantity = document.querySelector('input[name="quantity"]').value;
                        window.location.href = '?ProductID=<?php echo $productID; ?>&add-to-cart=true&quantity=' + quantity;
                    } else if (action === 'buy-now') {
                        var quantity = document.querySelector('input[name="quantity"]').value;
                        window.location.href = '?ProductID=<?php echo $productID; ?>&buy-now=true&quantity=' + quantity;
                    }
                } else {
                    alert('Please login to continue');
                }
            }
        </script>
    </p>

    <?php if (isset($message)) { ?>
        <script> alert('<?php echo $message; ?>'); </script>
    <?php } ?>
</body>

</html>