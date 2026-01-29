<?php
// Database connection parameters
$dbHost = 'localhost';
$dbName = 'Mat0';
$dbUsername = 'root';
$dbPassword = '';
session_start();
$customer_id = $_SESSION['CustomerID'];

// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate unique reference number
function generateReferenceNumber($storeID, $orderID) {
    return 'REF' . $storeID . '-' . $orderID . '-' . uniqid();
}

// Handle changing address
if (isset($_POST['new_address'])) {
    $new_address = $_POST['new_address'];

    // Update the customer's address in the database
    $sql = "UPDATE Customer SET current_location = ? WHERE CustomerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_address, $customer_id);
    $stmt->execute();
    $stmt->close();

    // Update the address in the session for immediate reflection
    $_SESSION['CustomerAddress'] = $new_address;
    header('Location: ' . $_SERVER['PHP_SELF']); // Refresh the page to show the updated address
    exit;
}

// Retrieve customer information
$sql = "SELECT * FROM Customer WHERE CustomerID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result_customer = $stmt->get_result();
if ($result_customer) {
    $customer_data = $result_customer->fetch_assoc();
    $username = $customer_data['Username'];
    $address = $customer_data['current_location'];
    $contact_number = $customer_data['PhoneNumber'];
} else {
    $username = 'Error'; // default value if query fails
    $address = '';
    $contact_number = '';
}
$stmt->close();

// Get order items with status 'checkout'
$sql = "SELECT oi.product_id, p.ProductName, p.Price, oi.quantity, p.Picture, s.StoreName, p.Price AS unit_price
        FROM orderitems oi
        INNER JOIN products p ON oi.product_id = p.ProductID
        INNER JOIN stores s ON p.StoreID = s.StoreID
        WHERE oi.CustomerID = ? AND oi.ostatus = 'checkout'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle moving items to cart
if (isset($_POST['move_to_cart'])) {
    if (isset($_POST['selected']) && is_array($_POST['selected'])) {
        $selected_items = $_POST['selected'];
        $selected_items_placeholder = implode(',', array_fill(0, count($selected_items), '?'));
        
        // Prepare the SQL statement to update the selected items' ostatus
        $sql = "UPDATE orderitems SET ostatus = 'cart' WHERE product_id IN ($selected_items_placeholder) AND CustomerID = ? AND ostatus = 'checkout'";
        $stmt = $conn->prepare($sql);

        // Bind parameters dynamically
        $types = str_repeat('i', count($selected_items)) . 'i';
        $params = array_merge($selected_items, [$customer_id]);
        $stmt->bind_param($types, ...$params);

        $stmt->execute();
        $stmt->close();

        // Redirect to the same page to reflect the changes
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Handle placing order
if (isset($_POST['place_order'])) {
    // Get the order items with status 'checkout'
    $sql = "SELECT p.Price, oi.quantity, oi.product_id
            FROM orderitems oi
            INNER JOIN products p ON oi.product_id = p.ProductID
            WHERE oi.CustomerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Calculate the total payment
    $subtotal = 0;
    while ($product = $result->fetch_assoc()) {
        $subtotal += $product['Price'] * $product['quantity'];
    }
    $shipping_fee = 70;
    $total_payment = $subtotal + $shipping_fee;

    error_log("Subtotal: $subtotal, Shipping Fee: $shipping_fee, Total Payment: $total_payment");

    // Create a new order entry
    $sql = "INSERT INTO orders (customer_id, order_date, order_status, order_total) VALUES (?, NOW(), 'pending', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $customer_id, $total_payment);
    $stmt->execute();
    $order_id = $conn->insert_id;

    // Generate and save reference number
    $storeID = 1; // Replace with actual store ID if available
    $referenceNumber = generateReferenceNumber($storeID, $order_id);
    $sql = "UPDATE orders SET reference_number = ? WHERE OrderID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $referenceNumber, $order_id);
    $stmt->execute();

    // Update order items to link them to the new order and update unit_price
    $sql = "UPDATE orderitems oi
            INNER JOIN products p ON oi.product_id = p.ProductID
            SET oi.order_id = ?, oi.ostatus = 'pending', oi.unit_price = p.Price
            WHERE oi.CustomerID = ? AND oi.ostatus = 'checkout'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $order_id, $customer_id);
    $stmt->execute();

    // Deduct the quantity of each product in the products table
    $result->data_seek(0); // Reset result pointer
    while ($product = $result->fetch_assoc()) {
        $sql = "UPDATE products SET quantity = quantity - ? WHERE ProductID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $product['quantity'], $product['product_id']);
        $stmt->execute();
    }

    // Redirect to order_success.php with the reference number
    header('Location: u_CHECKOUT.php?ref=' . $referenceNumber);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Review</title>
    <link rel="stylesheet" href="rstyles.css">
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
                <div class="notification-number">5</div>
                    <img class="nav-icon" src="../icons/cart-icon.png" alt="Cart" title="Cart">
                </div>
            </div>
        </nav>
    </div>


    <main>
        <div class="details">
            <h2>Details</h2>
            <div class="user-info">
                <p><strong><?php echo $username; ?></strong></p>
                <p><?php echo $contact_number; ?></p>

                <p id="address"><?php echo htmlspecialchars($address); ?></p>
                <button id="change-address-btn" class="change-address" onclick="showEditAddress()">Change Address</button>
                <form id="edit-address-form" style="display: none;" action="<?php echo $_SERVER['PHP_SELF']; ?>"method="post">
                    <input type="text" id="new-address" name="new-address" value="<?php echo htmlspecialchars($address); ?>">
                    <button type="submit" class="change-address">Save</button>
                </form>
            </div>
        </div>

        <div class="products">
            <h2>Products Ordered</h2>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <button type="submit" name="move_to_cart" class="change-address">Remove</button>
                <?php
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $customer_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    ?>
                <div class="product">
                    <input type="checkbox" name="selected[]" value="<?php echo $row['product_id']; ?>">

                    <img src="<?php echo $row['Picture']; ?>" alt="Product Image">
                    <div class="product-info">
                        <div class="product-name"><?php echo $row['ProductName']; ?> by <?php echo $row['StoreName']; ?>
                        </div>
                        <div class="product-quantity">
                            <span>Quantity: <?php echo $row['quantity']; ?></span>
                        </div>
                        <div class="product-price">P <?php echo number_format($row['Price'] * $row['quantity'], 2); ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </form>
        </div>

        <div class="payment-summary">
            <div class="payment">
                <h2>Payment Method</h2>
                <p>Cash on Delivery</p>
            </div>

            <div class="summary">
                <?php
                $subtotal = 0;
                $result->data_seek(0); // Reset result pointer to recalculate subtotal
                while ($product = $result->fetch_assoc()) {
                    $subtotal += $product['Price'] * $product['quantity'];
                }
                $shipping_fee = 70;
                $total_payment = $subtotal + $shipping_fee;
                ?>
                <p>Merchandise Subtotal: P <?php echo number_format($subtotal, 2); ?></p>
                <p>Shipping fee: P <?php echo number_format($shipping_fee, 2); ?></p>
                <p style="color: red; font-weight: bold;">Total Payment: P <?php echo number_format($total_payment, 2); ?>
                </p>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="place_order" value="1">
                    <button type="submit" class="place-order">Place Order</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Overlay for editing address -->
    <div class="overlay" id="overlay">
        <div class="overlay-content">
            <h2>Edit Address</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label for="new-address">New Address:</label>
                <input type="text" id="new-address" name="new_address" value="<?php echo $address; ?>">
                <button type="submit" class="change-address">Save</button>
            </form>
            <button onclick="hideEditAddress()" class="change-address">Cancel</button>
        </div>
    </div>

    <script>
        function showEditAddress() {
            document.getElementById("overlay").classList.add("show");
        }

        function hideEditAddress() {
            document.getElementById("overlay").classList.remove("show");
        }
    </script>

</body>

</html>
