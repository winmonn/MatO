<?php
session_start();

// Retrieve the StoreID from the session
$storeID = $_SESSION['StoreID'];

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

if (isset($_POST['signout'])) {
    session_destroy();
    header('Location: ../HOME.php'); // redirect to login page
    exit;
}

// Retrieve store data from the database
$sql_store = "SELECT * FROM stores WHERE StoreID = '$storeID'";
$result_store = mysqli_query($conn, $sql_store);

// Fetch store data as an associative array
$store_data = mysqli_fetch_assoc($result_store);

// Check if store data was retrieved successfully
if (!$store_data) {
    die("Store not found.");
}

$storeName = $store_data['StoreName'];
$location = $store_data['Location'];
$emailAddress = $store_data['EmailAddress'];
$phoneNumber = $store_data['PhoneNumber'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Profile Page</title>
    <link rel="stylesheet" href="account.css">
</head>

<body>
    <div class="header-nav">
        <header>
            <div class="logo" onclick="location.href='./s_DASHBOARD.php'">MatO</div>
            <div class="nav-item" onclick="location.href='./s_DASHBOARD.php'">
                <img class="nav-icon" src="../icons/home-icon.png" alt="Home" title="Dashboard">
            </div>
            <div class="nav-item" onclick="location.href='./s_PRODUCTS.php'">
                <img class="nav-icon" src="../icons/products-icon.png" alt="All Products" title="All Products">
            </div>

            <div class="nav-item" onclick="location.href='./s_ADD.php'">
                <img class="nav-icon" src="../icons/new-product.png" alt="All Products" title="All Products">
            </div>
        </header>

        <nav>
            <div class="account-cart">
                <div class="user-link"><?php echo htmlspecialchars($storeName); ?></div>
            </div>
        </nav>
    </div>

    <main>
        <div class="profile-container">
            <div class="profile-info">
                <div class="profile-details">
                    <p><strong>Store Name:</strong> <?php echo $storeName; ?></p>
                    <p><strong>Location:</strong> <?php echo $location; ?></p>
                    <p><strong>Email Address:</strong> <?php echo $emailAddress; ?></p>
                    <p><strong>Phone Number:</strong> <?php echo $phoneNumber; ?></p>
                </div>
                <div class="sign-out">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" name="signout" value="1">
                        <input type="submit" value="Sign Out" class="sign-out-btn">
                    </form>
                </div>
            </div>
        </div>


    </main>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>