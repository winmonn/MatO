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
    $query = "(SELECT 'customer' AS user_type, CustomerID AS id, Username AS username FROM Customer WHERE Username = '$username' AND Password = '$password')
              UNION
              (SELECT 'admin' AS user_type, AdminID AS id, Email AS username FROM Admins WHERE Email = '$username' AND Password = '$password')";
    
    $result = $conn->query($query);

    // Check if the query returned a result
    if ($result->num_rows > 0) {
        // Login successful, determine user type and redirect
        $user_data = $result->fetch_assoc();
        session_start();
        
        if ($user_data['user_type'] == 'customer') {
            $_SESSION['CustomerID'] = $user_data['id'];
            header('Location: USERS/u_HOME.php');
        } elseif ($user_data['user_type'] == 'admin') {
            $_SESSION['AdminID'] = $user_data['id'];
            header('Location: Admin /Admin_Dashboard.php');
        }
        
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
    <title>Login Page</title>
    <link rel="stylesheet" href="sstyles.css">
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

    <div class="container">
        <div class="login-form">
            <h2>Login</h2>
            <form action="LOGIN.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username"><br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <p style="cursor: pointer;" onclick="location.href='./s_LOGIN.php'">Are you a seller? Click here to login</p>
                <br>
                <input type="submit" value="Login">
            </form>
        </div>
    </div>
</body>
</html>
