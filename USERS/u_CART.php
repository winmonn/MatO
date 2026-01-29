<?php
session_start();
$customer_id = $_SESSION['CustomerID'];

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

// Get the order items for the current customer
$sql = "SELECT oi.product_id, p.ProductName, p.Price, oi.quantity, p.Picture, s.StoreName 
        FROM orderitems oi 
        INNER JOIN products p ON oi.product_id = p.ProductID 
        INNER JOIN stores s ON p.StoreID = s.StoreID 
        WHERE oi.CustomerID = '$customer_id' AND oi.ostatus = 'cart'";
$result = $conn->query($sql);

// Initialize the total price
$total_price = 0;

// Get the username
$sqlname = "SELECT Username FROM Customer WHERE CustomerID = '$customer_id'";
$result_name = $conn->query($sqlname);
if ($result_name) {
    $customer_data = $result_name->fetch_assoc();
    $username = $customer_data['Username'];
} else {
    $username = 'Error'; // default value if query fails
}

// Check if the remove form is submitted
if (isset($_POST['remove'])) {
    $product_id = $_POST['product_id'];
    $sql = "DELETE FROM orderitems WHERE CustomerID = '$customer_id' AND product_id = '$product_id' AND ostatus = 'cart'";
    $conn->query($sql);
    header('Location: ./u_CART.php');
    exit;
}

if (isset($_POST['action'])) {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    if ($action == 'increment') {
        $sql = "UPDATE orderitems SET quantity = quantity + 1 WHERE CustomerID = ? AND product_id = ? AND ostatus = 'cart'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $customer_id, $product_id);
        $stmt->execute();
        header('Location: ./u_CART.php');
        exit;
    } elseif ($action == 'decrement') {
        $sql = "UPDATE orderitems SET quantity = quantity - 1 WHERE CustomerID = ? AND product_id = ? AND ostatus = 'cart' AND quantity > 0";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $customer_id, $product_id);
        $stmt->execute();
        header('Location: ./u_CART.php');
        exit;
    }
}

if (isset($_POST['checkout'])) {
    if (!empty($_POST['selected'])) {
        $selected_products = $_POST['selected'];
        foreach ($selected_products as $product_id) {
            $sql = "UPDATE orderitems SET ostatus = 'checkout' WHERE CustomerID = '$customer_id' AND product_id = '$product_id' AND ostatus = 'cart'";
            $conn->query($sql);
        }
        header('Location: ./u_CHECK.php');
        exit;
    } else {
        echo "<script>alert('No items selected for checkout.');</script>";
    }
}

// Check if a product was added via "Buy Now"
$buy_now_product = isset($_GET['buy_now_product']) ? $_GET['buy_now_product'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Page</title>
    <link rel="stylesheet" href="CRstyles.css">
    <script>
        function updateTotal() {
            var checkboxes = document.querySelectorAll('input[name="selected[]"]:checked');
            var total = 0;
            checkboxes.forEach(function(checkbox) {
                var row = checkbox.closest('.cart-item');
                var price = parseFloat(row.querySelector('.product-price').innerText.replace(/[^0-9.-]+/g,""));
                total += price;
            });
            document.getElementById('total-price').innerText = total.toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', function() {
            var checkboxes = document.querySelectorAll('input[name="selected[]"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateTotal);
            });
            updateTotal();
        });
    </script>
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
                    <img class="nav-icon" src="../icons/cart-icon.png" alt="Cart" title="Cart">
                </div>
            </div>
        </nav>
    </div>

    <h1>My Cart</h1>
    <main>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="cart-container">
                <div class="cart-items">
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <div class="cart-item">
                            <input type="checkbox" name="selected[]" value="<?php echo $row['product_id']; ?>" <?php echo ($row['product_id'] == $buy_now_product) ? 'checked' : ''; ?>>
                            <img src="<?php echo $row['Picture']; ?>" alt="<?php echo $row['ProductName']; ?>">
                            <div class="cart-item-details">
                                <p><strong><?php echo $row['ProductName']; ?></strong></p>
                                <p>by <?php echo $row['StoreName']; ?></p>
                            </div>
                            <div class="remove">
                            <form method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                    <input type="submit" name="remove" value="Remove">
                                </form>
                    </div>
                            <div class="cart-item-quantity">
                                <form method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                    <input type="hidden" name="action" value="increment">
                                    <input type="submit" value="+">
                                </form>
                                <p>Quantity: <?php echo $row['quantity']; ?></p>
                                <form method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                    <input type="hidden" name="action" value="decrement">
                                    <input type="submit" value="-">
                                </form>
                            </div>
                            <div class="cart-item-total">P<span class="product-price"><?php echo number_format($row['Price'] * $row['quantity'], 2); ?></span></div>
                        </div>
                    <?php } ?>
                </div>
                <div class="cart-summary">
                    <h2>TOTAL</h2>
                    <p class="total-price">P<span id="total-price">0.00</span></p>
                    <p>Taxes and shipping calculated at checkout</p>
                    <button type="submit" name="checkout">Checkout Selected</button>
                </div>
            </div>
        </form>
    </main>

</body>
</html>
