<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tirudb";

// Function to generate random verification code
function generateVerificationCode($length = 6) {
    return rand(pow(10, $length-1), pow(10, $length)-1);
}

// Function to send email with verification code
function sendVerificationCode($email, $code) {
    $to = $email;
    $subject = 'Password Reset Verification Code';
    $message = 'Your verification code is: ' . $code;
    $headers = 'From:muralipraba425@gmail.com' . "\r\n" .
        'Reply-To: muralipraba425@gmail.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Send email
    mail($to, $subject, $message, $headers);
}

// Check if verification code is submitted
if(isset($_POST['verify_code']) && isset($_POST['verification_code'])) {
    $submitted_code = $_POST['verify_code'];
    $stored_code = $_POST['verification_code'];

    // Check if verification code is correct
    if($submitted_code == $stored_code) {
        // Verification successful, redirect to change password page
        header("Location: change_password.php");
        exit();
    } else {
        // Incorrect verification code, display error message
        $error = "Incorrect verification code. Please try again.";
    }
}

// Check if email and new password are submitted
if(isset($_POST['email']) && isset($_POST['new_password'])) {
    $email = $_POST['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Update password in database
    $sql = "UPDATE reg_user SET password='$new_password' WHERE email='$email'";

    if ($conn->query($sql) === TRUE) {
        // Password updated successfully
        $success_message = "Password updated successfully.";
    } else {
        // Error updating password
        $error = "Error updating password: " . $conn->error;
    }
}

// Check if email is submitted
if(isset($_POST['email'])) {
    $email = $_POST['email'];

    // Generate verification code
    $verification_code = generateVerificationCode();

    // Send verification code via email
    sendVerificationCode($email, $verification_code);

    // Display message
    $success_message = "Verification code sent to your email. Please check your inbox.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <h2>Forgot Password?</h2>
    <?php if(isset($error)): ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if(isset($success_message)): ?>
        <div style="color: green;"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <label for="email">Enter your email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <input type="submit" value="Send Verification Code">
    </form>
    <br>
    <form method="post" action="">
        <label for="verify_code">Enter verification code:</label><br>
        <input type="text" id="verify_code" name="verify_code" required><br>
        <input type="hidden" name="verification_code" value="<?php if(isset($verification_code)) echo $verification_code; ?>">
        <input type="submit" value="Verify">
    </form>
</body>
</html>
