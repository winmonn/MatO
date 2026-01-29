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

// Get the username and password from the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password from the form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database to check if the username and password match
    $query = "SELECT * FROM stores WHERE EmailAddress = '$username' AND Password = '$password'";
    $result = $conn->query($query);

    // Check if the query returned a result
    if ($result->num_rows > 0) {
        // Login successful, redirect to a protected page
        $store_data = $result->fetch_assoc();
        session_start();
        $_SESSION['StoreID'] = $store_data['StoreID'];
        header('Location: STORES/s_DASHBOARD.php');
        exit;
    } else {
        // Login failed, display an error message
        echo "<script>alert('Invalid username or password');</script>";
    }
}

// Close the database connection
$conn->close();
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
</body>
</html>
