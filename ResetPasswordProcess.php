<?php
session_start();
$email = $_SESSION['email'];

$user = 'root';
$pass = '';
$db = 'travelgram';

$conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");

// Retrieve the user's email address and verification code from the form or session
$user_verification_code = $_POST['verification_code']; // Assuming you have the verification code entered by the user

// Retrieve the verification code from the users table for the specified email address
$query = "SELECT verification_code FROM profile WHERE email = '$email'";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stored_verification_code = $row['verification_code'];

    // Check if the entered verification code matches the stored verification code
    if ($user_verification_code === $stored_verification_code) {
        // Verification code matches, proceed with password reset

        // Retrieve the new password entered by the user
        $new_password = $_POST['newPassword'];

        // Retrieve the retyped password entered by the user
        $retype_password = $_POST['retypePassword'];

        // Verify that the new password and retyped password match
        if ($new_password === $retype_password) {
            // Passwords match, update the password in the database
            $query = "UPDATE profile SET password = '$new_password' WHERE email = '$email'";
            $result = $conn->query($query);

            if ($result) {
                // echo "Password has been updated successfully!";
                echo '<script type = "text/javascript">';
                echo 'alert("Password has been updated successfully!");';
                echo 'window.location.href = "Login.php";';
                echo '</script>';
            } else {
                // echo "Failed to update password. Please try again.";
                echo '<script type = "text/javascript">';
                echo 'alert("Failed to update password. Please try again.");';
                echo 'window.location.href = "ResetPassword.php";';
                echo '</script>';
            }
        } else {
            // echo "New passwords do not match.";
            echo '<script type = "text/javascript">';
            echo 'alert("New passwords do not match.");';
            echo 'window.location.href = "ResetPassword.php";';
            echo '</script>';
        }
    } else {
        // echo "Verification code is incorrect.";
        echo '<script type = "text/javascript">';
        echo 'alert("Verification code is incorrect.");';
        echo 'window.location.href = "ResetPassword.php";';
        echo '</script>';
    }
} else {
    // echo "Invalid email address.";
    echo '<script type = "text/javascript">';
    echo 'alert("Invalid email address.");';
    echo 'window.location.href = "ForgotPassword.php";';
    echo '</script>';
}

// Close the database connection
$conn->close();

?>
