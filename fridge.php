<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoloader
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Function to get email address based on pincode
function getEmailFromPincode($pincode) {
    // Map pincode to list of email addresses
    $pincodeEmailsMap = array(
        '524121' => array('muralipraba425@gmail.com', 'praveenavula185@gmail.com', 'prasanthbandaru72@gmail.com'),
        '517127' => array('muralipraba425@gmail.com', 'praveenavula185@gmail.com'),
        // Add more pincode-emails mappings as needed
    );

    // Check if pincode exists in the map
    if (isset($pincodeEmailsMap[$pincode])) {
        // Get list of email addresses for the pincode
        $emailAddresses = $pincodeEmailsMap[$pincode];
        // Randomly select one email address
        $randomIndex = array_rand($emailAddresses);
        return $emailAddresses[$randomIndex];
    } else {
        return ''; // Return empty string if pincode not found
    }
}


// Create a new PHPMailer instance
$mail = new PHPMailer();

// SMTP configuration
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'muralipraba425@gmail.com'; // Your Gmail address
$mail->Password = 'ziup dqqf lcrm airz'; // Your Gmail password
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
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
// Function to generate a token number with a variable year (last two digits), fixed characters "CM0M", and random numbers
function generateToken($year) {
    // Extract the last two digits of the year
    $last_two_digits = substr($year, -2);

    // Generate a random number between 10 and 99 using the last two digits of the year
    $random_number = rand(10 + $last_two_digits, 99 + $last_two_digits);
    
    // Format the token number
    $token = $last_two_digits . "CM0F" . $random_number;
    
    return $token;
}

// Generate a token number for the year 2024
$token = generateToken("2024");
$name=$_POST["name"];
$email=$_POST["email"];
$mobile=$_POST["mobile"];
$address=$_POST["address"];
$pincode=$_POST["pincode"];
$brand=$_POST["brand"];
$modelnum=$_POST["modelnum"];
$issue=$_POST["issue"];


$sql = "INSERT INTO fridge_data (name,email,mobile,address,pincode,brand,modelnum,issue,token)
VALUES ('".$name."','".$email."','".$mobile."','".$address."','".$pincode."','".$brand."','".$modelnum."','".$issue."','".$token."')";

if (mysqli_query($conn, $sql)) {
   echo "<script>alert('Submited  Successfully!');window.location.href='dashboard.php';</script>";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);


if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pincode'])) {
    $fullname = $_POST['name'];
    $email = $_POST['email'];
    $pincode = $_POST['pincode'];
} else {
    echo 'Full name, email address, and pincode are required';
    exit;
}


// Sender and recipient
$mail->setFrom('muralipraba425@gmail.com', 'TDP COMPANY');
$mail->addAddress($email, $fullname);

// Set email subject
$mail->Subject = 'Booking is conformed ';

// Email body
$mail->Body = "Thank you for Choosing TDP Services , \n\n Booking Detials \n\n Your Name is : $name \n\n Your Brand is:$brand \n\n Your Model Number is:$modelnum \n\n Your ISSUE is:$issue\n\n   your token number is $token \n\n Your Mobile Number:$mobile\n\n Note: \n your issue solved Shortly..  ";

// Send email
if (!$mail->send()) {
    echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent successfully';
}
$mail->clearAddresses();
// Get email address based on pincode
$secondaryRecipientEmail = getEmailFromPincode($pincode);

// Check if email address is found for the provided pincode
if ($secondaryRecipientEmail !== '') {
    
    // Add secondary recipient
    $mail->addAddress($secondaryRecipientEmail, 'Secondary Recipient Name');

    // Set email subject and body for secondary recipient
$mail->isHTML(true);    
$mail->Subject = "Please Confirm Your Order Details";
    $mail->Body = "
    <html>
    <head>
        <title>TPD Serices</title>
    </head>
    <body>
        <p>Customer Name: $fullname,</p>
        <p>Token number: $token</p>
        <p>mobile:$mobile</p>
	<p>address:$pincode</p>
	<p>Modelnumber:$modelnum</p>
	<p>Brand Name:$brand</p>
	<p>Issue:$issue</p>
        <p>Please confirm your order by clicking the button below:</p>
        <a href='http://localhost/tiru/test/confrom_oder.php?token=$primaryToken'><button style='padding:10px;background-color:#4CAF50;color:white;border:none;border-radius:5px;'>Confirm Order</button></a>
	<p>Regards,<br>TDP Company</p>
	
       <script>
    // Add event listener to the button
    document.getElementById('confirmButton').addEventListener('click', function(event) {
        // Prevent the default behavior of the button
        event.preventDefault();
        
        // Show the 'thank you' message
        alert('Thank you for confirming your order!');
    });
</script>

    </body>
    </html>
";

    // Send email to secondary recipient
    if (!$mail->send()) {
        echo 'Message for secondary recipient could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message for secondary recipient has been sent successfully';
    }
} else {
    echo 'No email address found for the provided pincode';
}
?>
