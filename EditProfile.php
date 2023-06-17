<?php
//postID should be edited bila dapat Profile daripada Hanis
//js file location for cancelEditPost should be edited bila dapat Profile daripada Hanis

 // Displaying current post
 // Retrieve the postID from the query parameter
 session_start();
 
    if (isset($_GET['username'])) {
        $username = $_GET['username'];

        $user = 'root';
        $pass = '';
        $db = 'travelgram';
    
        $conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");

        $query = "SELECT profileImage, name, username, email, phone, password, bio FROM profile WHERE username = '$username'";
        $result = $conn->query($query);

        // Check if a matching row is found
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $post = new stdClass();
            $post->profileImage = base64_encode($row['profileImage']); // Convert the binary image data to base64
            $post->name = $row['name'];
            $post->username = $row['username'];
            $post->email = $row['email'];
            $post->phone = $row['phone'];
            $post->password = $row['password'];
            $post->bio = $row['bio'];

            $posts[] = $post;

            $posts_json = json_encode($posts);
            // Close the database connection
            $conn->close();
        }
    }
    

    //Updating profile post
    
    // Updating profile post
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {

        $user = 'root';
        $pass = '';
        $db = 'travelgram';
    
        $conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");

        // Fetch the profile data from the database
        $query = "SELECT profileImage, name, username, email, phone, password, bio FROM profile WHERE username = '$username'";
        $result = $conn->query($query);

        // Check if a matching row is found
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $profileImage = $_FILES['image']['tmp_name']; // Convert the binary image data to base64
            $name = $_POST['name'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $password = $_POST['password'];
            $bio = $_POST['bio'];

            if(!empty($profileImage = $_FILES['image']['tmp_name'])){
                // Read the image file
                $imgData = file_get_contents($profileImage);
                $imgData = $conn->real_escape_string($imgData);

                $query = "UPDATE profile SET profileImage = '$imgData', name = '$name', username = '$username', email = '$email', phone = '$phone', password = '$password', bio = '$bio' WHERE username = '$username'";
            }
            else{
                $query = "UPDATE profile SET name = '$name', username = '$username', email = '$email', phone = '$phone', password = '$password', bio = '$bio' WHERE username = '$username'";
            }
            
            // Update the data in the database
            $result = $conn->query($query);

            if ($result) {
                // Success! Data updated into the database.
                // Redirect to the feed page to display the updated posts
                header("Location: Profile.php");
                exit();
            } else {
                // Error occurred while updating data
                echo "Error: " . $conn->error;
            }
        }
        $conn->close();
}

$user = 'root';
$pass = '';
$db = 'travelgram';
    
$conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");

//special for sidebar
$query3 = "SELECT profileImage, name, username, numPost, avgRating FROM profile WHERE username = '$username'";
$result3 = $conn->query($query3);

if ($result3->num_rows > 0) {
    $row = $result3->fetch_assoc();

    $nav = new stdClass();
    $nav->username = $row['username'];
    $nav->picture = base64_encode($row['profileImage']); // Convert the binary image data to base64
    $nav->fname = $row['name'];
    $nav->no_post = $row['numPost'];
    $nav->avg_rating = $row['avgRating'];

    // Add the post object to the array
    $navs[] = $nav;
    // Convert the PHP array to JSON
    $navs_json = json_encode($navs);
}
$conn->close();
?>

<html>
    <head>
        <title>Edit Profile</title>
        <link rel="shortcut icon" type="image/x-icon" href="Pictures/travelgramIcon.svg" >
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link rel="stylesheet" href="css_file.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <meta name="description" content="Our home page">
        <meta name="keywords" content="Web Prog Project">
    </head>

    <body>
        <div id="wrapper">
            <header>
                <button class="openbtn" onclick="openNav()">☰</button>
            </header>
        </div>

        <div id="my_sidebar" class="sidebar">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
            <div class="profileInfo" style="padding: 5%;">
                <img src="data:image/jpeg;base64, <?php echo $nav->picture; ?>" style="border-radius: 50%;">
                <h2><?php echo $nav->fname ?></h2>
                <h4>@<?php echo $nav->username ?></h4>
                
            </div>
            <nav class="sidebar_nav">
                <ul>
                    <li><a href="Feed.php" class="feed">FEED</a></li>
                    <li><a href="Explore.php" class="explore">EXPLORE</a></li>
                    <li><a href="Notification.php" class="notification">NOTIFICATION</a></li>
                    <li><a href="Profile.php" class="profile">PROFILE</a></li>
                    <li><a href="analyticsHTML.php" class="analytics">ANALYTICS</a></li>
                    <li><a href="Login.php" class="logout" onclick="logout_alert()">LOG OUT</a></li>
                </ul>
            </nav>
        </div>

        <div id="main" class="content">
            <p id="userNameEditProfile" style="padding-left: 30px;">EDIT PROFILE</p><br>

            <div class="editProfile-item">

            <form method="POST" enctype="multipart/form-data">
                <div>
                    <img src="data:image/jpeg;base64, <?php echo $post->profileImage; ?>" alt="profilepic" style="border-radius: 50%;" width="204px" height="204px"><br><br>
                    <label for="profile-image-input">Change Profile Picture</label><br>
                    <input type="file" id="profile-image-input" name="image" accept="image/*">
                </div>

                <br>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Name</label>
                          <input type="name" name="name" class="form-control" maxlength="10" value="<?php echo $post->name; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Username</label>
                          <input id="no-space" name="username" type="username" class="form-control" maxlength="10" value="<?php echo $post->username; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $post->email; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone number</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo $post->phone; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" value="<?php echo $post->password; ?>">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Bio</label>
                            <textarea class="form-control" name="bio" rows="4" maxlength="100"><?php echo $post->bio; ?></textarea>
                        </div>
                    </div>
                </div>

                <br>

                <div>
                    <button type="submit" style="border-radius: 10px; background-color: #39375b; color: white; width: 80px; border-color: transparent;" name="update">Update</button></a>
                    <a href="Profile.php"><button type="cancel" style="border-radius: 10px; background-color: #F4F3FF; width: 80px; border-color: transparent;">Cancel</button></a>
                </div>
                
            </form>

            </div>

        </div>

        <script src="js_file.js"></script>

    </body>
</html>
