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

// Retrieve category data
$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);

$sqlname = "SELECT Username FROM Customer WHERE CustomerID = '$customer_id'";

$result_name = mysqli_query($conn, $sqlname);
if ($result_name) {
    $customer_data = mysqli_fetch_assoc($result_name);
    $username = $customer_data['Username'];
} else {
    $username = 'Error'; // default value if query fails
}

$sql_cart_items = "SELECT COUNT(*) AS cart_count FROM orderitems WHERE CustomerID = '$customer_id' AND ostatus = 'cart'";
$result_cart_items = mysqli_query($conn, $sql_cart_items);

// Fetch the result as an associative array
$cart_count = mysqli_fetch_assoc($result_cart_items)['cart_count'];

// Fetch customer username
$sql_username = "SELECT Username FROM Customer WHERE CustomerID = '$customer_id'";
$result_username = mysqli_query($conn, $sql_username);
$username = mysqli_fetch_assoc($result_username)['Username'];

$sql_orders = "
     SELECT 
        o.reference_number, o.order_date, oi.ostatus, oi.quantity, p.ProductName, p.Picture, oi.unit_price, 
        (oi.unit_price * oi.quantity + 70) as total_price
    FROM orders o
    JOIN orderitems oi ON o.OrderID = oi.order_id
    JOIN products p ON oi.product_id = p.ProductID
    WHERE o.customer_id = '$customer_id'
    ORDER BY oi.order_id DESC
    LIMIT 5";
$result_orders = mysqli_query($conn, $sql_orders);
$orders = mysqli_fetch_all($result_orders, MYSQLI_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatO Official Store</title>
    <link rel="stylesheet" href="cstyles.css">
</head>

<body>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Categories</title>
        <link rel="stylesheet" href="category.css">
        <style>
        </style>
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

            <div class="nav-item" id="notification-bell">
                <div class="notification-bell"></div>
                <img class="nav-icon" src="../icons/bell-icon.png" alt="Notifications" title="Notifications">
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
            <section class="category-section">
                <div class="category-title">
                    <div class="category-text">All Categories</div>
                </div>
                <div class="product-grid">
                    <?php
                    // Loop through the results
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Store the category data in variables
                        $category_id = $row['CategoryID'];
                        $category_name = $row['CategoryName'];
                        $category_image_path =  '../' . $row['PICTURES'];

                        // Generate the HTML code dynamically
                        echo '<div class="product-item">';
                        echo '<a href="u_DEALS.php?category=' . $category_id . '" style="text-decoration: none;">';
                        echo '<img src="' . $category_image_path . '" alt="' . $category_name . '">';
                        echo '<div class="product-info">';
                        echo '<div class="product-name" style="text-decoration: none;">' . $category_name . '</div>';
                        echo '</div>';
                        echo '</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </section>
        </main>
        

        <script>
        var orders = <?php echo json_encode($orders); ?>;

        document.getElementById("notification-bell").addEventListener("click", function () {
            console.log("Notification bell clicked");
            showOrders();
        });

        function showOrders() {
            console.log("showOrders function called");
            console.log(orders); // Log orders to check the structure and data types

            var orderList = '';
            orders.forEach(function (order) {
                // Trim whitespace and convert to lowercase
                var status = order.ostatus.trim().toLowerCase();
                var statusMessage = '';

                if (status === 'pending') {
                    statusMessage = 'Pending seller confirmation.';
                } else if (status === 'accepted') {
                    statusMessage = 'is Accepted by the seller.';
                } else if (status === 'declined') {
                    statusMessage = 'is Declined by the seller.';
                } else {
                    statusMessage = 'Completed'; // Default or fallback message
                }

                // Ensure total_price is a number before calling toFixed
                var totalPrice = Number(order.total_price) || 0;

                orderList += '<li>';
                orderList += '<p>' + order.ProductName + ' (P' + totalPrice.toFixed(2) + ') - ' + order.reference_number + ' ' + statusMessage + '</p>';
                orderList += '</li>';
            });
            document.getElementById('order-list').innerHTML = orderList;
            document.getElementById('orders-popup').style.display = 'block';
            console.log("Popup displayed with order list");
        }


        function closePopup() {
            console.log("closePopup function called");
            document.getElementById('orders-popup').style.display = 'none';
        }


    </script>


    </body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>