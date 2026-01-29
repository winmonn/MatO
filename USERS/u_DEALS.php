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

// Get the store name, price range, sort_by, and category from the query string parameters
$StoreID = isset($_GET['StoreID']) ? (int)$_GET['StoreID'] : '';
$price_range = isset($_GET['price_range']) ? $_GET['price_range'] : '';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : '';
$category = isset($_GET['category']) ? (int)$_GET['category'] : '';

// Retrieve the username
$sqlname = "SELECT Username FROM Customer WHERE CustomerID = '$customer_id'";
$result_name = mysqli_query($conn, $sqlname);
$username = ($result_name) ? mysqli_fetch_assoc($result_name)['Username'] : 'Error';


// Build the SQL query based on the filter parameters
$sql = "SELECT p.*, s.StoreName FROM products p INNER JOIN stores s ON p.StoreID = s.StoreID WHERE p.product_status = 'displayed'";
$conditions = ["p.product_status = 'displayed'"];

if (!empty($StoreID)) {
    $conditions[] = "s.StoreID = " . intval($StoreID);
}

if (!empty($price_range)) {
    list($price_min, $price_max) = explode('-', $price_range);
    $conditions[] = "p.Price BETWEEN " . floatval($price_min) . " AND " . floatval($price_max);
}

if (!empty($category)) {
    $conditions[] = "p.CategoryID = " . intval($category);
}

if (!empty($conditions)) {
    $sql .= " AND " . implode(' AND ', $conditions);
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
        break;
}


$result = $conn->query($sql);

$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    $result->free();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

function getCategoryName($category_id, $conn) {
    $category_sql = "SELECT categoryName FROM categories WHERE categoryID = " . intval($category_id);
    $category_result = $conn->query($category_sql);
    if ($category_result) {
        $category_name = $category_result->fetch_assoc()['categoryName'];
        $category_result->free();
        return $category_name;
    }
    return '';
}

$category_name = (!empty($category)) ? getCategoryName($category, $conn) : '';



function displayDeals($products) {
    foreach ($products as $product) {
        echo "<div class='product-item'>";
        echo "<a href='./u_Purchase.php?ProductID=" . htmlspecialchars($product['ProductID']) . "'>";
        echo "<img src='" . htmlspecialchars($product['Picture']) . "' alt='" . htmlspecialchars($product['ProductName']) . "' />";
        echo "<div class='product-info'>";
        echo "<div class='product-name' style='text-decoration:none'>" . htmlspecialchars($product['ProductName']) . "</div>";
        echo "<div class='product-store'>Store: " . htmlspecialchars($product['StoreName']) . "</div>";
        echo "<div class='product-price'>Price: P" . htmlspecialchars($product['Price']) . "</div>";
        echo "</div>";
        echo "</a>";
        echo "</div>";
    }
}

$sql_cart_items = "SELECT COUNT(*) AS cart_count FROM orderitems WHERE CustomerID = '$customer_id' AND ostatus = 'cart'";
$result_cart_items = mysqli_query($conn, $sql_cart_items);

// Fetch the result as an associative array
$cart_count = mysqli_fetch_assoc($result_cart_items)['cart_count'];

// Fetch customer username
$sql_username = "SELECT Username FROM Customer WHERE CustomerID = '$customer_id'";
$result_username = mysqli_query($conn, $sql_username);
$username = mysqli_fetch_assoc($result_username)['Username'];

$conn->close();
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
        <div class="content-container">
            <aside class="filters">
                <div class="back-button" onclick="location.href='./u_Categories.php'"><img class="nav-iconback" src="../icons/back-icon.png" alt="Back" title="Back"></div>
                <div class="containerfilter">
                    <div class="filterelements">
                        <div class="filter-title">Filters:</div>
                        <form method="GET" action="u_DEALS.php">
                            <div class="filter-category">Price:</div>
                            <input type="text" name="price_range" placeholder="min-max" value="<?php echo htmlspecialchars($price_range); ?>">
                            <div class="filter-category">Stores:</div>
                            <select name="StoreID">
                                <option value="">All Stores</option>
                                <option value="1" <?php if ($StoreID == '1') echo 'selected'; ?>>Cebu Home Builders</option>
                                <option value="2" <?php if ($StoreID == '2') echo 'selected'; ?>>Citi Hardware</option>
                                <option value="3" <?php if ($StoreID == '3') echo 'selected'; ?>>Wilcon Depot</option>
                            </select>
                            <div class="filter-category">Sort by:</div>
                            <select name="sort_by">
                                <option value="">None</option>
                                <option value="price_asc" <?php if ($sort_by == 'price_asc') echo 'selected'; ?>>Price, low to high</option>
                                <option value="price_desc" <?php if ($sort_by == 'price_desc') echo 'selected'; ?>>Price, high to low</option>
                            </select>
                            <button type="submit">Apply Filters</button>
                        </form>
                    </div>
                </div>
            </aside>
            <section class="category-section">
                <div class="category-title">Categories > <?php echo $category_name; ?></div>
                <div class="product-grid">
                    <?php displayDeals($products); ?>
                </div>
            </section>
        </div>
    </main>
</body>

</html>
