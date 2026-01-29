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

// Retrieve categories data
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);

// Check if categories data exists
$categories = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Shuffle the categories array
shuffle($categories);

// Retrieve products data
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Check if products data exists
$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Shuffle the products array
shuffle($products);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatO Official Store</title>
    <link rel="stylesheet" href="./styles.css">
    <style>
    </style>
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
        <div class="welcomecontainer">
            <div class="slideshow-container">
                <!-- Large Ad -->
                <div class="slideshow">
                    <div class="slide">
                        <img src="./PICTURES/homescreen.PNG" alt="Ad 1">
                    </div>
                    <div class="slide">
                        <img src="./PICTURES/ad2.png" alt="Ad 2">
                    </div>
                    <div class="slide">
                        <img src="./PICTURES/ad3.jpg" alt="Ad 3">
                    </div>
                </div>

                <!-- Medium Ads Container -->
                <div class="medium-ads-container">
                    <!-- Medium Ad 1 -->
                    <div class="medium-ad">
                        <div class="slide">
                            <img src="./PICTURES/ad4.jpg" alt="Ad 4">
                        </div>
                        <div class="slide">
                            <img src="./PICTURES/ad5.jpg" alt="Ad 5">
                        </div>
                    </div>

                    <!-- Medium Ad 2 -->
                    <div class="medium-ad">
                        <div class="slide">
                            <img src="./PICTURES/ad6.webp" alt="Ad 6">
                        </div>
                        <div class="slide">
                            <img src="./PICTURES/ad7.jpg" alt="Ad 7">
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
                    $category_image_path = './' . $category['PICTURES'];

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
                <div class="search-bar">
                    <button onclick="location.href='./Categories.php'">View All</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Function to initialize all slideshows
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

        // Initialize all slideshows
        initializeSlideshows();

    </script>




</body>

</html>