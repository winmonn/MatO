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

// Retrieve category data
$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);
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
        <title>MatO Official Store</title>
        <link rel="stylesheet" href="./styles.css">
        <style>
        </style>
    </head>

    <body>
    <div class="header-nav">
        <header>
            <div class="logo"  onclick="location.href='./HOME.php'">MatO</div>
            
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
                        $category_image_path = $row['PICTURES'];

                        // Generate the HTML code dynamically
                        echo '<div class="product-item">';
                        echo '<a href="DEALS.php?category=' . $category_id . '">';
                        echo '<img src="' . $category_image_path . '" alt="' . $category_name . '">';
                        echo '<div class="product-info">';
                        echo '<div class="product-name">' . $category_name . '</div>';
                        echo '</div>';
                        echo '</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </section>
        </main>
    </body>

</html>

<?php
// Close the database connection
mysqli_close($conn);
?>