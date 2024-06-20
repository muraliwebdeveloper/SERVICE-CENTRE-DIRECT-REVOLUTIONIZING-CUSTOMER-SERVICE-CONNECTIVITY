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
$name=$_POST["name"];
$email=$_POST["email"];
$password=$_POST["password"];


$sql = "INSERT INTO Reg_user (name,email,password)
VALUES ('".$name."','".$email."','".$password."')";

if (mysqli_query($conn, $sql)) {
   echo "<script>alert('Submited  Successfully!');window.location.href='index.html';</script>";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>