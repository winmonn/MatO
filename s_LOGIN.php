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
    <title>Seller login</title>
    <link rel="stylesheet" href="lstyles.css">
</head>
<body>
    <header class="header-nav">
        <nav>
            <div class="account-cart">
            <div class="account" onclick="location.href='./HOME.php'">Home</div>
                <div class="account" onclick="location.href='./SIGNUP.php'">Sign Up</div>
                <div class="account" onclick="location.href='./LOGIN.php'">Login</div>
            </div>
        </nav>
    </header>



<body>
    <div class="container">
        <div class="login-form">
            <h2>Seller Login</h2>
            <form action="s_LOGIN.php" method="post">
                <label for="username">Email Address:</label>
                <input type="text" id="username" name="username"><br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <p p style="cursor: pointer;" onclick="location.href='./LOGIN.php'">Are you a Customer? Click here to login</p>
                <br>
                <input type="submit" value="Login">
            </form>
        </div>
    </div>
</body>

</html>