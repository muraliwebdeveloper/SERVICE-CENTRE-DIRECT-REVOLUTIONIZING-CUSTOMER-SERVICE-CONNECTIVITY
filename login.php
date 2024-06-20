<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tirudb";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if Email and Password are set in $_GET array
if (isset($_GET["email"]) && isset($_GET["password"])) {
    $email = $_GET["email"];
    $password = $_GET["password"];

    $sql = "SELECT email, password FROM Reg_user WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($row["email"] == $email && $row["password"] == $password) {
            header("location: dashboard.php");
        }
    } else {
        echo "INVALID E-MAIL/PASSWORD";
    }
} else {
    echo "Email or Password not provided";
}

mysqli_close($conn);
?>
