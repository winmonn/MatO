<?php
session_start();

// Retrieve the CustomerID from the session
$customer_id = $_SESSION['CustomerID'];

$dbHost = 'localhost';
$dbName = 'Mat0';
$dbUsername = 'root';
$dbPassword = '';

// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['signout'])) {
    session_destroy();
    header('Location: ../HOME.php'); // redirect to login page
    exit;
}

// Retrieve products from the database
$sql_products = "SELECT p.ProductID, p.ProductName, p.Price, p.Picture, s.StoreName
                 FROM products p
                 JOIN stores s ON p.StoreID = s.StoreID
                 WHERE p.product_status = 'displayed'";

$result_products = mysqli_query($conn, $sql_products);

// Fetch products as an associative array
$products = mysqli_fetch_all($result_products, MYSQLI_ASSOC);

// Shuffle the products array
shuffle($products);

$recommended_products = array_slice($products, 0, 6);


// Retrieve customer data
$sql_customer = "SELECT * FROM Customer WHERE CustomerID = '$customer_id'";
$result_customer = mysqli_query($conn, $sql_customer);
if ($result_customer) {
    $customer_data = mysqli_fetch_assoc($result_customer);
    $username = $customer_data['Username'];
    $profile_picture = $customer_data['profile_picture'];
    $date_of_birth = $customer_data['date_of_birth'];
    $current_location = $customer_data['current_location'];
} else {
    $username = 'Error'; // default value if query fails
    $profile_picture = '';
    $date_of_birth = '';
    $current_location = '';
}


$sql_cart_items = "SELECT COUNT(*) AS cart_count FROM orderitems WHERE CustomerID = '$customer_id' AND ostatus = 'cart'";
$result_cart_items = mysqli_query($conn, $sql_cart_items);

// Fetch the result as an associative array
$cart_count = mysqli_fetch_assoc($result_cart_items)['cart_count'];

// Fetch customer username
$sql_username = "SELECT Username FROM Customer WHERE CustomerID = '$customer_id'";
$result_username = mysqli_query($conn, $sql_username);
$username = mysqli_fetch_assoc($result_username)['Username'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="Astyles.css">
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


    <main>
        <div class="profile-container">
            <div class="profile-info">
                <div class="profile-image">
                    <img src="<?php echo $profile_picture; ?>" alt="Profile Image">
                </div>
                <div class="profile-details">
                    <p><strong>Name:</strong> <?php echo $username; ?></p>
                    <p><strong>Email Address:</strong> <?php echo $customer_data['Email']; ?></p>
                    <p><strong>Phone Number:</strong> <?php echo $customer_data['PhoneNumber']; ?></p>
                    <p><strong>Date of Birth:</strong> <?php echo $date_of_birth; ?></p>
                    <p><strong>Current Location:</strong> <?php echo $current_location; ?></p>
                </div>
            </div>

            <div class="profile-actions">
                <div class="purchase">
                    <h3>My Purchase</h3>
                    <div class="purchase-status">
    <div class="status pending" onclick="location.href='./u_PENDING.php'">
        <img class="PP" img src="./PP/pending-icon.png" alt="Pending" title="Pending">
        Pending Orders
    </div>
    <div class="status delivered" onclick="location.href='./u_DELIVERED.php'">
        <img class="PP" img src="./PP/delivered-icon.png" alt="Delivered" title="Delivered">
        To receive
    </div>
    <div class="status completed" onclick="location.href='./u_COMPLETED.php'">
        <img class="PP" img src="./PP/completed-icon.png" alt="Completed" title="Completed">
        Completed
    </div>
</div>

</div>
                <div class="recommendations">
                    <h3>You May Also Like</h3>
                    <div class="recommendation-list">
                        <?php foreach ($recommended_products as $product) { ?>
                            <div class="recommendation-item">
                            <a href="u_Purchase.php?ProductID=<?php echo $product['ProductID']; ?>">
                                <img src="<?php echo $product['Picture']; ?>" alt="Recommendation Image">
                                <p><?php echo $product['ProductName']; ?> by <?php echo $product['StoreName']; ?></a></p>
                                <p class="price">P <?php echo $product['Price']; ?>.00</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <br><br>


                <div class="sign-out">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" name="signout" value="1">
                        <input type="submit" value="Sign Out" class="sign-out-btn">
                    </form>
                </div>
            </div>
        </div>
    </main>

</body>

</html>