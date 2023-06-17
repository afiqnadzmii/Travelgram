<?php 
// Start session
session_start();
$_SESSION['email'] = $_POST['email'];

require 'phpmailer/includes/Exception.php';
require 'phpmailer/includes/PHPMailer.php';
require 'phpmailer/includes/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$user = 'root';
$pass = '';
$db = 'travelgram';

$conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");

// Function to check if the email exists in the database
function check_email_in_database($email, $conn){
    $email = $conn->real_escape_string($email); // Sanitize the input to prevent SQL injection

    // Execute a query to find a matching email address
    $query = "SELECT email FROM profile WHERE email = '$email'";
    $result = $conn->query($query);

    // Check if the query returned any rows
    if($result->num_rows > 0){
        return true; // Email exists in the database
    }
    else{
        return false; // Email does not exist in the database
    }
}

// Prompt the user to enter an email address
$email = $_POST['email']; // Retrieve from a form submission

// Check if the email exists in the database
if(check_email_in_database($email, $conn)){
    // Create an instance of PHPMailer
    $mail = new PHPMailer();

    // Set the mailer to use SMTP
    $mail->isSMTP();

    // Define the SMTP host
    $mail->Host = "smtp.gmail.com";

    // Enable SMTP authentication
    $mail->SMTPAuth = true;

    // Set the type of encryption (ssl/tls)
    $mail->SMTPSecure = "tls";

    // Set the port to connect SMTP
    $mail->Port = 587;

    // Set the Gmail username
    $mail->Username = "iffah.syamimi04@gmail.com";

    // Set the Gmail password
    $mail->Password = "rzsskwzxirmvajpk";

    // Set the email subject
    $mail->Subject = "Verification Code";

    // Set the sender email
    $mail->setFrom("iffah.syamimi04@gmail.com");

    // Generate a random 4-digit verification code
    $verification_code = rand(1000, 9999);

    // Email body
    $mail->Body = "Your verification code is: " . $verification_code;

    // Add the recipient (user's email)
    $mail->addAddress($email);

    // Update the users table to store the verification code
    $query = "UPDATE profile SET verification_code = '$verification_code' WHERE email = '$email'";
    $result = $conn->query($query);

    // Send the email
    if($mail->send()){
        // echo "Verification code has been sent to your email.";
        header("Location: ResetPassword.php?email=" . urlencode($email));
        echo '<script type = "text/javascript">';
        echo 'alert("Verification code has been sent to your email.");';
        echo 'window.location.href = "ResetPassword.php";';
        echo '</script>';
        exit();
    } else{
        // echo "Failed to send the verification code. Please try again.";
        echo '<script type = "text/javascript">';
        echo 'alert("Failed to send the verification code. Please try again.");';
        echo 'window.location.href = "ForgotPassword.php";';
        echo '</script>';
    }
} else{
    // echo "Email entered does not exist.";
    echo '<script type = "text/javascript">';
    echo 'alert("Email entered does not exist.");';
    echo 'window.location.href = "ForgotPassword.php";';
    echo '</script>';
}

// Close the database connection
$conn->close();

?>
