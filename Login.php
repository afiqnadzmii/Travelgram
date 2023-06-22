<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$user = 'root';
$pass = '';
$db = 'travelgram';

$conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");

//check if the connection was successful
if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}

//check if the login form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //retrieve the input username and password
    $username = $_POST["username"];
    $password = $_POST["password"];

    //perform database validation (will have a look later on)

    $query = "SELECT * FROM profile WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    //check if the login is successful
    if($result->num_rows == 1){
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        //user is authenticated, redirect to the feed page
        header("Location: Feed.php");
        exit();
    }
    else{
        
        //login failed, show an error message
        // echo "Invalid username or password.";
        echo '<script type = "text/javascript">';
        echo 'alert("Invalid username or password.");';
        echo 'window.location.href = "login.php";';
        echo '</script>';
    }
    //close the database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="Pictures/travelgramIcon.svg">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet1'>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet2'>
    <link rel="stylesheet" href="css_login.css">
</head>
<body>

    <div class="home_container">
        <img id="firstImage" src="Pictures/travel1.jpg">
        <img id="secondImage" src="Pictures/travel2.jpeg">
        <img id="thirdImage" src="Pictures/travel3.png">
        <img id="fourthImage" src="Pictures/travel5.jpg">
        <img id="fifthImage" src="Pictures/travel4.jpg">
    </div>

    <div class="container">
        <div class="form-box">
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <img id="word_travelgram" src="Pictures/word_travelgram_new.png">
                <div class="input-box mb-3">
                    <!-- <span class="icon"><box-icon name='user' type='solid' color='#d9b8c4' ></box-icon></span> -->
                    <input class="usernamee" type="text" name="username" placeholder="Username" required> 
                </div>
                <div class="input-box mb-3">
                    <!-- <span class="icon"><box-icon name='lock-alt' type='solid' color='#d9b8c4' ></box-icon></span> -->
                    <input id="password_box" type="password" name="password" placeholder="Password" required> 
                </div>
                <div class="remember-forgot">
                    <a href="ForgotPassword.php">Forgot Password?</a>
                </div>
                <button type="submit" class="btn btn-primary">LOG IN</button>
                <div class="login-register">
                    <p style="color: rgba(245, 222, 179, 0.538); font-size: small;">Not A Member? <a href="Registration.php"
                        class="register-link">Register Now</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>
</html>