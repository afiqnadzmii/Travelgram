<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/x-icon" href="Pictures/travelgramIcon.svg">
        <title>Reset Password</title>
        <link rel="stylesheet" href="css_login.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet1'>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet2'>
    </head>

    <body>
        <div class="container-ResetPassword">
            <div class="form-box-ResetPassword">
                <form method="post" action="ResetPasswordProcess.php">
                    <p style="color: #d9b8c4ac; width: 80%; margin-left: 10%; padding-top: 30px;">We have send a code via your email. Enter below to reset your password.</p>
                    <div class="input-box-ResetPassword">
                        <!-- <span class="icon"><box-icon name='user' type='solid' color='#d9b8c4' ></box-icon></span> -->
                        <input class="code" name="verification_code" placeholder="Code" required> 
                    </div>
                    <div class="input-box-ResetPassword">
                        <!-- <span class="icon"><box-icon name='user' type='solid' color='#d9b8c4' ></box-icon></span> -->
                        <input class="newPassword" name="newPassword" type="password" placeholder="New Password" required> 
                    </div>
                    <div class="input-box-ResetPassword">
                        <!-- <span class="icon"><box-icon name='user' type='solid' color='#d9b8c4' ></box-icon></span> -->
                        <input class="retypePassword" name="retypePassword" type="password" placeholder="Enter New Password Again" required> 
                    </div>
                    <button type="submit" class="btn">SUBMIT</button>
                    <div class="login-register">
                        <p style="color: rgba(245, 222, 179, 0.538); font-size:small;">Not A Member? <a href="Registration.php"
                            class="register-link">Register Now</a></p>
                    </div>
                    <div class="login-register">
                        <p style="color: rgba(245, 222, 179, 0.538); margin-top: 80px; font-size: small;">Already have an account?<a href="Login.php" class="login-page">Login Page</a></p>
                    </div>
                </form>
            </div>
        </div>

        
        
    </body>
</html>
