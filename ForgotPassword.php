<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/x-icon" href="Pictures/travelgramIcon.svg">
        <title>Forgot Password</title>
        <link rel="stylesheet" href="css_login.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet1'>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet2'>
    </head>

    <body>
        <div class="container-ForgotPassword">
            <div class="form-box-ForgotPassword">
                <form method="POST" action="ForgotPasswordProcess.php">
                    <img id="word_travelgram" src="Pictures/iconLock.svg" style="color: white; width: 150px; height: 100px;">
                    <h4 style="color: #d9b8c4; padding-top: 5px;">Trouble logging in?</h4>
                    <p style="color: #d9b8c4ac; width: 80%; margin-left: 10%; padding-top: 7px;">Don't worry just fill in your email and we'll help you to reset your email!</p>
                    <div class="input-box-ForgotPassword">
                        <!-- <span class="icon"><box-icon name='user' type='solid' color='#d9b8c4' ></box-icon></span> -->
                        <input class="email" name="email" id="email" type="email" placeholder="Email" required> 
                    </div>
                    <button type="submit" class="btn">SEND</button>
                    <div class="login-register">
                        <p style="color: rgba(245, 222, 179, 0.538); font-size:small;">Not A Member? <a href="Registration.php"
                            class="register-link">Register Now</a></p>
                    </div>
                    <div class="login-register">
                        <p style="color: rgba(245, 222, 179, 0.538); margin-top: 90px; font-size: small;">Already have an account?<a href="Login.php" class="login-page">Login Page</a></p>
                    </div>
                </form>
            </div>
        </div>

        
        
    </body>
</html>