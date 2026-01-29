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
$customer_id = $_SESSION['CustomerID'];

// Fetch categories
$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
shuffle($categories);

// Fetch products
$sql = "SELECT * FROM products WHERE product_status = 'displayed'";
$result1 = $conn->query($sql);
$products = $result1->fetch_all(MYSQLI_ASSOC);
shuffle($products);

// Fetch customer username
$sql = "SELECT Username FROM Customer WHERE CustomerID = '$customer_id'";
$result_name = mysqli_query($conn, $sql);
$customer_data = mysqli_fetch_assoc($result_name);
$username = $customer_data['Username'];

// Fetch cart count
$sql_cart_items = "SELECT COUNT(*) AS cart_count FROM orderitems WHERE CustomerID = '$customer_id' AND ostatus = 'cart'";
$result_cart_items = mysqli_query($conn, $sql_cart_items);
$cart_count = mysqli_fetch_assoc($result_cart_items)['cart_count'];

// Fetch orders
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
    <link rel="stylesheet" href="hstyles.css">
</head>

<body>
    <div class="header-nav">
        <header>
            <div class="logo" onclick="location.href='./u_HOME.php'" style="cursor: pointer;">MatO</div>

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
                <div class="account" onclick="location.href='./u_ACCOUNT.php'">Account (<?php echo $username; ?>)</div>
                <div class="nav-cart" onclick="location.href='./u_CART.php'">
                    <div class="notification-number"><?php echo $cart_count; ?></div>
                    <img class="nav-icon" src="../icons/cart-icon.png" alt="Cart" title="Cart">
                </div>
            </div>
        </nav>
    </div>

    <main>
        <!-- Orders Popup -->
        <div id="orders-popup" class="popup">
            <div class="popup-content">
                <span class="close" onclick="closePopup()">&times;</span>
                <ul id="order-list"></ul>
            </div>
        </div>

        <div class="welcomecontainer">
            <div class="slideshow-container">
                <!-- Large Ad -->
                <div class="slideshow">
                    <div class="slide">
                        <img src="../PICTURES/homescreen.PNG" alt="Ad 1">
                    </div>
                    <div class="slide">
                        <img src="../PICTURES/ad2.png" alt="Ad 2">
                    </div>
                    <div class="slide">
                        <img src="../PICTURES/ad3.jpg" alt="Ad 3">
                    </div>
                </div>

                <!-- Medium Ads Container -->
                <div class="medium-ads-container">
                    <!-- Medium Ad 1 -->
                    <div class="medium-ad">
                        <div class="slide">
                            <img src="../PICTURES/ad4.jpg" alt="Ad 4">
                        </div>
                        <div class="slide">
                            <img src="../PICTURES/ad5.jpg" alt="Ad 5">
                        </div>
                    </div>

                    <!-- Medium Ad 2 -->
                    <div class="medium-ad">
                        <div class="slide">
                            <img src="../PICTURES/ad6.webp" alt="Ad 6">
                        </div>
                        <div class="slide">
                            <img src="../PICTURES/ad7.jpg" alt="Ad 7">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="deal-header">
                <div class="deals-text">Deals just for you!</div>
            </div>
            <div class="deal-container">
                <?php
                $counter = 0;
                foreach ($products as $product) {
                    $counter++;
                    if ($counter > 6)
                        break;

                    $product_id = $product['ProductID'];
                    $product_name = $product['ProductName'];
                    $product_image_path = $product['Picture'];

                    echo '<div class="deal-item">';
                    echo '<a href="./u_Purchase.php?ProductID=' . $product_id . '" style="text-decoration: none;">';
                    echo '<img src="' . $product_image_path . '" alt="' . $product_name . '">';
                    echo '<div class="product-info">';
                    echo '<div class="product-name">' . $product_name . '</div>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <div class="container categories">
            <div class="categories-text">Categories</div>
            <div class="category-container">
                <?php
                $counter = 0;
                foreach ($categories as $category) {
                    $counter++;
                    if ($counter > 5)
                        break;

                    $category_id = $category['CategoryID'];
                    $category_name = $category['CategoryName'];
                    $category_image_path = '../' . $category['PICTURES'];

                    echo '<div class="category-item">';
                    echo '<a href="./u_DEALS.php?category=' . $category_id . '" style="text-decoration: none;">';
                    echo '<img src="' . $category_image_path . '" alt="' . $category_name . '">';
                    echo '<div class="product-info">';
                    echo '<div class="product-name">' . $category_name . '</div>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
                ?>
                <div class="search-bar">
                    <button onclick="location.href='./u_Categories.php'">View All</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Function to initialize all slideshows
        function initializeSlideshows() {
            const slideshows = document.querySelectorAll('.slideshow, .medium-ad');

            slideshows.forEach(slideshow => {
                let slideIndex = 0;
                const slides = slideshow.querySelectorAll('.slide');

                function showSlides() {
                    slides.forEach(slide => {
                        slide.style.display = 'none';
                    });

                    slideIndex++;
                    if (slideIndex > slides.length) { slideIndex = 1 }
                    slides[slideIndex - 1].style.display = 'block';

                    setTimeout(showSlides, 5000); // Change image every 5 seconds
                }

                showSlides(); // Initial call to start the slideshow
            });
        }

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