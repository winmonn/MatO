<?php
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

$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);

// Fetch all categories into an array
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Shuffle the categories array
shuffle($categories);

// Retrieve product data
$sql = "SELECT * FROM products";
$result1 = mysqli_query($conn, $sql);

// Fetch all products into an array
$products = mysqli_fetch_all($result1, MYSQLI_ASSOC);

// Shuffle the products array
shuffle($products);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatO Official Store</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <div class="logo">MatO</div>
        <div class="search-bar">
            <input type="text" placeholder="Search">
            <button onclick="location.href='./Categories.php'">All Categories</button>
        </div>
        <div class="account-cart">
            <div class="account" onclick="location.href='./SIGNUP.php'">Sign Up</div>
            <div class="account" onclick="location.href='./LOGIN.php'">Login</div>
            <div class="cart" onclick="location.href='./LOGIN.php'">Cart</div>
        </div>
    </header>
    <nav>
        <div class="nav-item">FEATURED STORES</div>
        <div class="nav-item">Ace Hardware</div>
        <div class="nav-item">Atlantic Hardware</div>
        <div class="nav-item">Belmont Hardware</div>
        <div class="nav-item">Cebu Home Builders</div>
        <div class="nav-item">Citi Hardware</div>
        <div class="nav-item">Wilcon Depot</div>
    </nav>

    <main>
        <br>

        <div class="welcome-section"></div>
        <div class="flex-container">
            <div class="container deals">

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
                        echo '<a href="./Purchase.php?ProductID=' . $product_id . '">';
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
                <div class="categories-header">
                    <div class="deals-text">Categories</div>
                    <button class="view-all" onclick="location.href='./Categories.php'">View all</button>
                </div>
                <div class="category-container">
                    <?php
                    $counter = 0;
                    foreach ($categories as $category) {
                        $counter++;
                        if ($counter > 5)
                            break;
                        $category_id = $category['CategoryID'];
                        $category_name = $category['CategoryName'];
                        $category_image_path = './' . $category['PICTURES'];

                        // Generate the HTML code dynamically
                        echo '<div class="category-item">';
                        echo '<a href="./DEALS.php?category=' . $category_id . '">';
                        echo '<img src="' . $category_image_path . '" alt="' . $category_name . '">';
                        echo '<div class="product-info">';
                        echo '<div class="product-name">' . $category_name . '</div>';
                        echo '</div>';
                        echo '</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>

    </main>


</body>



</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
