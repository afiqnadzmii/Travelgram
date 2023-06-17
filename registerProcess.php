<?php 
session_start();
$_SESSION['email'] = $_POST['email'];
$_SESSION['username'] = $_POST['username'];
$_SESSION['password'] = $_POST['password'];
$_SESSION['confirmpassword'] = $_POST['confirmpassword'];
//database connection details
$user = 'root';
$pass = '';
$db = 'travelgram';

$conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");
//sql code
//check the connection
if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}

//retrieve form data
$username = $_SESSION['username'];
$user_email = $_SESSION['email'];
$password = $_SESSION['password'];
$confirmpassword = $_SESSION['confirmpassword'];

if($password == $confirmpassword){
    //prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO profile(username, email, password) VALUES (?,?,?)");
    //bind the form data to the prepared statement
    $stmt->bind_param("sss", $username, $user_email, $password);
    //execute the statement
    $stmt->execute();
    //check if the insertion was successful
    if($stmt->affected_rows > 0){

        header("Location: RegistrationNext.php");
        exit();
    }
    else{
        echo "Error: Registration failed.";
    }
}
else{
    echo '<script type = "text/javascript">';
    echo 'alert("The password does not match. Please enter again.");';
    echo 'window.location.href = "Registration.php";';
    echo '</script>';
}

//close the prepared statement
$stmt->close();
//close the database connection
$conn->close();
?>