<?php
session_start();

$adminId = $_SESSION['AdminID'];

$dbHost = 'localhost';
$dbName = 'Mat0';
$dbUsername = 'root';
$dbPassword = '';

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['signout'])) {
    session_destroy();
    header('Location: ../HOME.php'); 
    exit;
}

$sql_admin = "SELECT * FROM admins WHERE AdminId = '$adminId'";
$result_admin = $conn->query($sql_admin);

if (!$result_admin) {
    die("Query failed: " . $conn->error);
}

$admin_data = $result_admin->fetch_assoc();

if (!$admin_data) {
    die("Admin not found.");
}

$adminName = $admin_data['FirstName'] . ' ' . $admin_data['LastName'];
$emailAddress = $admin_data['Email'];
$contactNumber = $admin_data['ContactNumber'];
$dateJoined = $admin_data['DateJoined'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile Page</title>
    <link rel="stylesheet" href="account.css">
</head>

<body>
    <div class="header-nav">
        <header>
            <div class="logo" onclick="location.href='./Admin_Dashboard.php'">MatO</div>
        </header>

        <nav>
            <div class="account-cart">
                <div class="user-link"><?php echo htmlspecialchars($adminName); ?></div>
            </div>
        </nav>
    </div>

    <main>
        <div class="profile-container">
            <div class="profile-info">
                <div class="profile-details">
                    <p><strong>Admin Name:</strong> <?php echo $adminName; ?></p>
                    <p><strong>Email Address:</strong> <?php echo $emailAddress; ?></p>
                    <p><strong>Contact Number:</strong> <?php echo $contactNumber; ?></p>
                    <p><strong>Date Joined:</strong> <?php echo $dateJoined; ?></p>
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
