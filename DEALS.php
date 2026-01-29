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

// Get the store name, price range, sort_by, and category from the query string parameters
$store_name = isset($_GET['store_name']) ? $_GET['store_name'] : '';
$price_range = isset($_GET['price_range']) ? $_GET['price_range'] : '';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Build the SQL query based on the store name, price range, sort_by, and category parameters
$sql = "SELECT p.*, s.StoreName FROM products p INNER JOIN stores s ON p.StoreID = s.StoreID";
$conditions = [];

if (!empty($store_name)) {
    $conditions[] = "s.StoreName LIKE '%" . $conn->real_escape_string($store_name) . "%'";
}

if (!empty($price_range)) {
    list($price_min, $price_max) = explode('-', $price_range);
    $conditions[] = "p.Price BETWEEN " . floatval($price_min) . " AND " . floatval($price_max);
}

if (!empty($category)) {
    $conditions[] = "p.CategoryID = " . intval($category);
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}

// Apply sorting
switch ($sort_by) {
    case 'price_asc':
        $sql .= " ORDER BY p.Price ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY p.Price DESC";
        break;
    default:
        // No sorting applied
        break;
}

$result = $conn->query($sql);

$products = [];
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    $result->free();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

function getCategoryName($category_id, $conn)
{
    $category_sql = "SELECT categoryName FROM categories WHERE categoryID = " . intval($category_id);
    $category_result = $conn->query($category_sql);
    if ($category_result) {
        $category_row = $category_result->fetch_assoc();
        $category_name = $category_row['categoryName'];
        $category_result->free();
        return $category_name;
    }
    return ''; // return empty string if no category found
}

if (!empty($category)) {
    $category_name = getCategoryName($category, $conn);
} else {
    $category_name = '';
}


$conn->close();

function displayDeals($products)
{
    foreach ($products as $product) {
        echo "<div class='product-item'>";
        echo "<a href='Purchase.php?ProductID=" . htmlspecialchars($product['ProductID']) . "'>";
        echo "<img src='" . htmlspecialchars($product['Picture']) . "' alt='" . htmlspecialchars($product['ProductName']) . "' />";
        echo "<div class='product-info'>";
        echo "<div class='product-name'>" . htmlspecialchars($product['ProductName']) . "</div>";
        echo "<div class='product-store'>Store: " . htmlspecialchars($product['StoreName']) . "</div>";
        echo "<div class='product-price'>Price: P" . htmlspecialchars($product['Price']) . "</div>";
        echo "</div>";
        echo "</a>";
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatO Official Store</title>
    <link rel="stylesheet" href="Dstyles.css">
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
    <div class="content-container">
        <aside class="filters">
        <div class="back-button" onclick="location.href='./HOME.php'"><img class="nav-iconback" src="icons/back-icon.png" alt="Back" title="Back">
</div><div class="containerfilter">
                <div class="filterelements">
            <div class="filter-title">Filters:</div>
            <form method="GET" action="DEALS.php">
                <div class="filter-category">Price:</div>
                <input type="text" name="price_range" placeholder="min-max">
                <div class="filter-category">Stores:</div>
                <select name="store_name">
                    <option value="">All Stores</option>
                    <option value="Cebu Home Builders">Cebu Home Builders</option>
                    <option value="Citi Hardware">Citi Hardware</option>
                    <option value="Wilcon Depot">Wilcon Depot</option>
                </select>
                <div class="filter-category">Sort by:</div>
                <select name="sort_by">
                    <option value="">None</option>
                    <option value="price_asc">Price, low to high</option>
                    <option value="price_desc">Price, high to low</option>
                </select>
                <button type="submit">Apply Filters</button>
</div></form></div>
        </aside>
        <section class="category-section">
            <div class="category-title">Categories > <?php echo $category_name; ?></div>

            <div class="product-grid">
                <?php displayDeals($products); ?>
            </div>
        </section>
    </main>
</body>

</html>