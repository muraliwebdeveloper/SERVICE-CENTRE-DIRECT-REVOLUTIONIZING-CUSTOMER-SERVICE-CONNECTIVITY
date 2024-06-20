<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tirudb";

// Check if form is submitted
if(isset($_POST['email']) && isset($_POST['new_password'])) {
    $email = $_POST['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update password in database
    $sql = "UPDATE reg_user SET password='$new_password' WHERE email='$email'";

    if ($conn->query($sql) === TRUE) {
        // Password updated successfully
        $success_message = "Password updated successfully.";
    } else {
        // Error updating password
        $error = "Error updating password: " . $conn->error;
    }

    // Close database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
</head>
<body>
    <h2>Change Password</h2>
    <?php if(isset($error)): ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if(isset($success_message)): ?>
        <div style="color: green;"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password" required><br>
        <input type="submit" value="Change Password">
    </form>
</body>
</html>
