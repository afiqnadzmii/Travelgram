<?php
session_start();
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}

//postID should be edited bila dapat Profile daripada Hanis
//js file location for cancelEditPost should be edited bila dapat Profile daripada Hanis

 // Displaying current post
 // Retrieve the postID from the query parameters
    $user = 'root';
    $pass = '';
    $db = 'travelgram';

    $conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");
    
    if (isset($_GET['postID'])) {
        $postID = $_GET['postID'];

        $query = "SELECT image, title, description, location, username FROM post WHERE postID = $postID";
        $result = $conn->query($query);
    
        // Check if a matching row is found
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
    
            $post = new stdClass();
            $post->image = base64_encode($row['image']); // Convert the binary image data to base64
            $post->title = $row['title'];
            $post->description = $row['description'];
            $post->location = $row['location'];
            $post->username = $row['username'];
    
            $posts[] = $post;
    
            $posts_json = json_encode($posts);
            // Close the database connection
            $conn->close();
        }
    }

    //Deleting post
    $user = 'root';
    $pass = '';
    $db = 'travelgram';
    
    $conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");
    
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        // Delete data from the database
        $query = "DELETE FROM post WHERE postID = $postID";
        $result = $conn->query($query);

        $query2 = "DELETE FROM postcomrate WHERE postID = $postID";
        $result2 = $conn->query($query2);

        // Insert the data into the database
        $query6 = "UPDATE profile SET numPost = (SELECT COUNT(*) FROM post WHERE username = '$username') WHERE username = '$username'";
        $result6 = $conn->query($query6);
    
        if ($result && $result2 && $result6) {
            // Success! Data deleted from the database.
            // Redirect to the feed page to display the updated posts
            header("Location: Profile.php");
            exit();
        } else {
            // Error occurred while inserting data
            echo "Error: " . $conn->error;
        }
    }

    //Updating current post
    $user = 'root';
    $pass = '';
    $db = 'travelgram';

    $conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $user = 'root';
        $pass = '';
        $db = 'travelgram';

        $conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");
    
        // Retrieve the form data
        //$postID = $_GET['postID'];
        $title = $_POST['editTitle'];
        $location = $_POST['editLocation'];
        $description = $_POST['editDescription'];

        // Insert the data into the database
        $query = "UPDATE post SET title = '$title', location = '$location', description = '$description' WHERE postID = $postID";
        $result = $conn->query($query);

        if ($result) {
            // Success! Data updated into the database.
            // Redirect to the feed page to display the updated posts
            header("Location: Profile.php");
            exit();
        } else {
            // Error occurred while inserting data
            echo "Error: " . $conn->error;
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

<!DOCTYPE html>
<html>
    <head>
        <title>Manage Post</title>
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

        <div id="managePost" class="content">
            <div class="content" style="border: 1px solid #ddd; border-radius: 5px; margin: 20px; margin-top: 50px;">
                <!-- width: 325px; -->
                    
                <div class="box" style="width: 85%;">

                <div class="post">
                    <img src="data:image/jpeg;base64, <?php echo $post->image; ?>" style="height: 325px;">
                    
                    <!-- <input type="text" name="editTitle" style="width: 30rem;" placeholder="A Day in Taj Mahal"><br>
                    <input id="editLocation" type="text" style="width: 30rem; margin-top: 3px;" placeholder="Taj Mahal">
                    <textarea id="editDescription" type="text" style="width: 30rem; height: 17rem; margin-top: 3px;" placeholder="RioZ telah berhijrah ke Taj Mahal"></textarea>
                     -->
                     </div>

                    <form form id="manageposts-form" method="POST" enctype="multipart/form-data">
                    <input type="text" id="editTitle" name="editTitle" style="width: 30rem;" value="<?php echo $post->title ?>"><br>
                    <input type="text" id="editLocation" name="editLocation" style="width: 30rem; margin-top: 3px;" value="<?php echo $post->location ?>">
                    <input type="text" id="editDescription" name="editDescription" style="width: 30rem; height: 17rem; margin-top: 3px;" value="<?php echo $post->description ?>"></textarea>
                    <br>
                    <div class="button_">
                        <button type="submit" name="update" onclick="return confirmUpdatePost()">Update</button>

                        <a href="Profile.php"><button type="button" style="background-color: #ddd; color: black;">Cancel</button></a>
        
                        <button type="submit" style="background-color: #ddd; color: black;" name="delete" onclick="return confirmDeletePost()">Delete</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openNav(){
                document.getElementById("my_sidebar").style.width = "250px";
                document.getElementById("main").style.marginLeft = "250px";
            }
            function closeNav(){
                document.getElementById("my_sidebar").style.width = "0";
                document.getElementById("main").style.marginLeft = "0";
            }
        </script>

        <script type="text/javascript">

            function confirmDeletePost() {
                // Display a confirmation dialog
                var result = confirm("Are you sure you want to delete this post?");

                // If the user confirms, redirect to the logout page
                if (result) {
                    return true;
                }
                else{
                return false;
                }
            }

            function confirmUpdatePost() {
                // Display a confirmation dialog
                var result = confirm("Are you sure you want to update this post?");

                // If the user confirms, redirect to the logout page
                if (result) {
                    return true;
                }
                else{
                return false;
                }
            }

        </script>

        <script src="js_file.js"></script>
    
    </body>
</html>
