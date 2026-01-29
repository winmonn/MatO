<?php
// Configuration
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
$customer_id = $_SESSION['CustomerID'];

// Retrieve customer username
$sql = "SELECT Username FROM Customer WHERE CustomerID = '$customer_id'";
$result_name = mysqli_query($conn, $sql);
if ($result_name) { // Check $result_name instead of $result
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

// Close the database connection

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
    <title>About us</title>
    <link rel="stylesheet" href="about.css">
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


    <div class="container">
        <div class="about-container">
            <h2>About Us</h2>
            <p>Welcome to MatO, your premier destination for streamlined procurement of construction materials online. At MatO, we understand the challenges faced by individuals and businesses alike when sourcing materials for projects, whether small renovations or large-scale constructions.</p>
            
            <p>Our platform bridges the gap between consumers and suppliers, offering a user-friendly interface designed to enhance convenience and efficiency in the procurement process.</p>

            <h3>Our Mission</h3>
            <p>Our mission at MatO is to revolutionize the way construction materials are acquired. We aim to provide a comprehensive online marketplace where users can easily browse, compare, and purchase a wide range of construction materials from verified suppliers.</p>
            
            <p>By leveraging technology and innovative solutions, we strive to simplify the procurement journey, reducing the time and effort traditionally required to find the right materials.</p>

            <h3>What We Offer</h3>
            <ul>
                <li><strong>Extensive Selection:</strong> Explore a diverse inventory of construction materials, ranging from basic supplies to specialized products, all accessible with just a few clicks.</li>
                <li><strong>Efficient Ordering:</strong> Enjoy the convenience of delivery options, ensuring flexibility to meet your project's timeline and logistics needs.</li>
                <li><strong>Real-Time Updates:</strong> Track your orders seamlessly with real-time status updates, from processing to delivery, ensuring transparency and peace of mind.</li>
                <li><strong>Customer Support:</strong> Our dedicated support team is here to assist you every step of the way, providing timely assistance and resolving any queries you may have regarding orders, products, or technical issues.</li>
            </ul>

            <h3>Our Commitment</h3>
            <p>At MatO, we are committed to enhancing user experience through intuitive design, secure transactions, and reliable service. Whether you're a homeowner, contractor, or business owner, our platform caters to your specific needs, helping you achieve your project goals efficiently and effectively.</p>

            <h3>Get Started Today</h3>
            <p>Join the MatO community and experience the future of construction materials procurement. Simplify your purchasing process, save time, and explore a world of possibilities with our innovative platform. Discover why MatO is the preferred choice for individuals and businesses alike in the construction industry.</p>

            <div class="contact-info">
                <h3>Contact Us:</h3>
                <p>Email: <a href="mailto:info@mato.com">info@mato.com</a></p>
                <p>Phone: <a href="tel:+1234567890">+1234567890</a></p>
                <p>For inquiries, partnerships, or feedback, feel free to contact us</a>. We look forward to serving you on your journey towards building better, together.</p>
            </div>
        </div>
    </div>

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

        initializeSlideshows();

    </script>

</body>
</html>
