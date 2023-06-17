<?php
session_start();
$email = $_SESSION['email'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];

// Establish a connection to the database
$user = 'root';
$pass = '';
$db = 'travelgram';

$conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");
//sql code
//check the connection
if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}

// Retrieve the values from the form
$name = $_POST['name'];
$phonenumber = $_POST['phonenumber'];
$bio = $_POST['bio'];
$picture = $_FILES['profile_image_input']['tmp_name'];

// Escape the values to prevent SQL injection
$name = $conn->real_escape_string($name);
$phonenumber = $conn->real_escape_string($phonenumber);
$bio = $conn->real_escape_string($bio);
$pictureData = file_get_contents($picture);
$pictureData = $conn->real_escape_string($pictureData);

// Construct and execute the SQL query
$sql = "UPDATE profile SET name = '$name', phone = '$phonenumber', bio = '$bio', profileImage = '$pictureData' WHERE email = '$email'";
$result = $conn->query($sql);

if ($result === TRUE) {
    // echo "Values added successfully.";
    echo '<script type = "text/javascript">';
    echo 'alert("Register successful! Welcome to Travelgram!");';
    echo 'window.location.href = "Login.php";';
    echo '</script>';
} else {
    echo "Error adding values: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
