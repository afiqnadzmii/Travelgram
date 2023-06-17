
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="Pictures/travelgramIcon.svg" >
    <title>Register</title>
    <link rel="stylesheet" href="css_registration.css">
    <!-- Add Bootstrap CSS file -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet1'>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet2'>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="content">
                    <img src="Pictures/Img_Travelgram_logo.png" alt="logo">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="logreg-box">
                    <div class="form-box login">
                        <form method="POST" action="registerProcess.php">
                            <h2>SIGN UP</h2>
                            <div class="input-box">
                                <!-- <span class="icon"><box-icon name='user' type='solid' color='#d9b8c4' ></box-icon></span> -->
                                <input type="username" placeholder="Username" name="username" id="username" style="color: #f4f7be;" required> 
                            </div>
                            <div class="input-box">
                                <!-- <span class="icon"><box-icon name='envelope' type='solid' color='#d9b8c4' ></box-icon></span> -->
                                <input type="email" placeholder="Email" name="email" id="email" style="color: #f4f7be;" required> 
                            </div>
                            <div class="input-box">
                                <!-- <span class="icon"><box-icon name='lock' type='solid' color='#d9b8c4' ></box-icon></span> -->
                                <input type="password" placeholder="Password" name="password" id="password" style="color: #f4f7be;" required> 
                            </div>
                            <div class="input-box">
                                <!-- <span class="icon"><box-icon name='lock' type='solid' color='#d9b8c4' ></box-icon></span> -->
                                <input type="password" placeholder="Confirm Password" name="confirmpassword" id="confirmpassword" style="color: #f4f7be;" required> 
                            </div>
                            <div class="remember-forget" style="position: absolute; top: 70%; left: 8%;">
                                <!-- <button id="terms_privacy" type="button" class="btntick btn-secondary btn-lg" style="background-color: transparent; border: transparent; color: #f4f7be;"> <input type="checkbox">I Agree to Terms & Privacy</button> -->
                                <!-- The Modal -->
                                <!-- <div id="myModal" class="modal"> -->
                                    <!-- Modal content -->
                                    <!-- <div class="modal-content">
                                        <span class="close">&times;</span>
                                        <h3>Terms and Privacy Policy</h3>
                                        <p style="font-size: small;">Welcome to our travel blog website! These terms and conditions outline the rules and regulations for the use of our website.
                                            By accessing our website, we assume you accept these terms and conditions in full. If you do not agree to these terms and conditions or any part of these terms and conditions, you must not use our website.
                                            Intellectual Property Rights Unless otherwise stated, we or our licensors own the intellectual property rights in the website and material on the website. All these intellectual property rights are reserved.
                                            License to Use Website You may view, download, and print pages from our website for your personal use, subject to the restrictions set out in these terms and conditions.
                                            User Content By posting or submitting any content on our website, you grant us a non-exclusive, transferable, sub-licensable, royalty-free, worldwide license to use any such content for any purpose.
                                            User Conduct You agree to use our website only for lawful purposes and in a way that does not infringe on the rights of, restrict or inhibit anyone else's use of the website.
                                            Links to Other Websites Our website may contain links to other websites which are not under our control. We have no control over the nature, content, and availability of those sites. The inclusion of any links does not necessarily imply a recommendation or endorsement of the views expressed within them.
                                            Liability We make every effort to ensure that the information provided on our website is accurate and up-to-date. However, we cannot guarantee that the information will always be completely free from errors or omissions. We will not be liable for any loss or damage of any nature arising from the use of our website.
                                            Termination We may terminate your access to our website at any time without notice and without reason.
                                            Changes to These Terms and Conditions We reserve the right to change these terms and conditions at any time without notice. Your continued use of our website after any changes indicates your acceptance of these changes.
                                            PRIVACY POLICY
                                            We respect your privacy and are committed to protecting your personal information. This policy explains how we collect, use, and protect your personal data.
                                            Personal Data We Collect We may collect the following personal data from you:
                                            Name
                                            Email address
                                            IP address
                                            Location data
                                            Information about your computer and your visits to our website
                                            Information that you provide to us when subscribing to our newsletter
                                            Use of Personal Data We may use your personal data for the following purposes:
                                            To provide you with our newsletter
                                            To improve our website and your user experience
                                            To analyze trends and usage of our website
                                            To comply with any legal obligations
                                            Protection of Personal Data We take the protection of your personal data seriously and have implemented appropriate technical and organizational measures to ensure its security.
                                            Disclosure of Personal Data We may disclose your personal data to third-party service providers who assist us in providing our services or who perform functions on our behalf.
                                            Cookies Our website uses cookies to improve your user experience. By using our website, you consent to our use of cookies.
                                            Your Rights You have the right to access, correct, and delete your personal data. You may also object to the processing of your personal data or request that we restrict the processing of your personal data.
                                            Changes to This Privacy Policy We reserve the right to change this privacy policy at any time without notice. Your continued use of our website after any changes indicates your acceptance of these changes.</p>
                                    </div>
                    
                                </div> -->
                            </div>

                            <!-- <button id="terms_privacy" type="button" class="btn btn-secondary btn-lg">Terms & Privacy</button> -->
                            <!-- The Modal -->
                            <!-- <div id="myModal" class="modal"> -->
                
                            <!-- Modal content -->
                            <!-- <div class="modal-content">
                                <span class="close">&times;</span>
                                <p>Some text in the Modal..</p>
                            </div>
                
                        </div> -->

                            <button type="submit" class="btn">NEXT</button>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
        

    <script>
        // Get the modal
        var modal = document.getElementById("myModal");
        
        // Get the button that opens the modal
        var btn = document.getElementById("terms_privacy");
        
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        
        // When the user clicks the button, open the modal 
        btn.onclick = function() {
          modal.style.display = "block";
        }
        
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
          modal.style.display = "none";
        }
        
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        }

        //to check password entered both form is match
        var passwordField = document.getElementById("password");
        var confirmPasswordField = document.getElementById("confirmpassword");

        //add an event listener to the form submission
        document.querySelector("form").addEventListener("submit", function(event){
            //check if the password match
            if(passwordField.value !== confirmPasswordField.value){
                event.preventDefault();//prevent from submission
                alert("Password do not match. Please try again.");
            }
        });
        
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>
</html>

