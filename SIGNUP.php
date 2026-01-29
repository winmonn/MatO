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

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    if (isset($_POST['username']) && isset($_POST['Firstname']) && isset($_POST['Lastname']) && isset($_POST['Email']) && isset($_POST['PhoneNumber']) && isset($_POST['password']) && isset($_POST['date_of_birth']) && isset($_POST['current_location'])) {
        $username = $_POST['username'];
        $Firstname = $_POST['Firstname'];
        $Lastname = $_POST['Lastname'];
        $Email = $_POST['Email'];
        $PhoneNumber = $_POST['PhoneNumber'];
        $password = $_POST['password'];
        $date_of_birth = $_POST['date_of_birth'];
        $current_location = $_POST['current_location'];

        // Insert the data into the database
        $query = "INSERT INTO Customer (Username, Firstname, Lastname, Email, PhoneNumber, Password, date_of_birth, current_location) 
                  VALUES ('$username', '$Firstname', '$Lastname', '$Email', '$PhoneNumber', '$password', '$date_of_birth', '$current_location')";
        $result = $conn->query($query);

        // Check if the query was successful
        if ($result) {
            echo 'Sign up successful!';
        } else {
            echo 'Error: ' . $conn->error;
        }
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
    <title>Signup Page</title>
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
        <div class="category-section">
            <div class="login-form">
                <h2>Sign-Up Form</h2>
                <form action="SIGNUP.php" method="post">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username"><br>

                    <label for="Firstname">Firstname:</label>
                    <input type="text" id="Firstname" name="Firstname"><br>

                    <label for="Lastname">Lastname:</label>
                    <input type="text" id="Lastname" name="Lastname"><br>

                    <label for="Email">Email:</label>
                    <input type="text" id="Email" name="Email"><br>

                    <label for="date_of_birth">Date of Birth:</label>
                    <input type="date" id="date_of_birth" name="date_of_birth"><br>

                    <label for="current_location">Current Location:</label>
                    <input type="text" id="current_location" name="current_location"><br>

                    <label for="PhoneNumber">Phone Number:</label>
                    <input type="text" id="PhoneNumber" name="PhoneNumber"><br>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password"><br>
                    <input type="submit" value="Signup">
                </form>
            </div>
        </div>
    </div>
</body>

</html>