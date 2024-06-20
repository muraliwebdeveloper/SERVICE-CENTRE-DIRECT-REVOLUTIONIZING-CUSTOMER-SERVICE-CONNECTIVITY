<?php
// Include database connection parameters
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

// Start the session
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the email is provided
    if (isset($_POST["email"]) && !empty($_POST["email"])) {
        $email = $_POST["email"];

        // Generate a random password
        $newPassword = bin2hex(random_bytes(8)); // Generate an 8-character random password
        
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $sql = "UPDATE Reg_users SET password = '$hashedPassword' WHERE email = '$email'";
        if (mysqli_query($conn, $sql)) {
            // Send the new password to the user's email
            $to = $email;
            $subject = "Password Reset";
            $message = "Your new password is: $newPassword"; // You may want to customize this message
            $headers = "From: muralipraba425@gmail.com"; // You may want to customize the sender email

            if (mail($to, $subject, $message, $headers)) {
                echo "An email with the new password has been sent to $email.";
            } else {
                echo "Failed to send email. Please try again later.";
            }
        } else {
            echo "Error updating password: " . mysqli_error($conn);
        }
    } else {
        echo "Email is required.";
    }
}

// Close the database connection
mysqli_close($conn);
?>
