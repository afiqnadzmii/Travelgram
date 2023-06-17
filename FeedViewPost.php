<?php
session_start();

if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}
    // Retrieve the postID from the query parameters
    if (isset($_GET['postID'])) {
    $postID = $_GET['postID'];

    $user = 'root';
    $pass = '';
    $db = 'travelgram';

    $conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");
    
    $query = "SELECT image, title, description, location, username, averagerate FROM post WHERE postID = $postID";
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
        $post->averagerate = $row['averagerate'];

        $posts[] = $post;

        $posts_json = json_encode($posts);
        // Close the database connection
        $conn->close();
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $user = 'root';
        $pass = '';
        $db = 'travelgram';

        $conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");
    
        // Retrieve the form data
        $postID = $_GET['postID'];
        $comment = $_POST['comment'];
        $rating = $_POST['rating'];

        // Insert the data into the database
        $query = "INSERT INTO postcomrate (postID, username, comment, rate, datecreated) VALUES ('$postID', '$username', '$comment', '$rating', CURRENT_DATE)";
        $result = $conn->query($query);

        //second query to update averageRate
        $query2 = "UPDATE post SET averageRate = (SELECT AVG(rate) FROM postcomrate WHERE postID = '$postID') WHERE postId = '$postID'";
        $result2 = $conn->query($query2);

        //second query to update averageRating for user
        $query5 = "UPDATE profile SET avgRating = (SELECT AVG(averageRate) FROM post WHERE username = '$username') WHERE username = '$username'";
        $result5 = $conn->query($query5);

        if ($result2 && $result && $result5) {
            // Success! Data inserted into the database.
            // Redirect to the feed page to display the updated posts
            header("Location: Feed.php");
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
    
    $postID = $_GET['postID'];

    // Retrieve data from the database
    $query = "SELECT username, comment, rate FROM postcomrate where postid = '$postID' ORDER BY commentID DESC";
    $result = $conn->query($query);
    
    $comments = array();
    
    // Fetch each row from the result set and create objects
    while ($row = $result->fetch_assoc()) {
        $comment = new stdClass();
        $comment->postID = $_GET['postID'];
        $comment->username = $row['username'];
        $comment->comment = $row['comment'];
        $comment->rate = $row['rate'];
    
        // Add the post object to the array
        $comments[] = $comment;
    }
    
    // Convert the PHP array to JSON
    $comments_json = json_encode($comments);
    
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
        <title>View Post</title>
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
            <div class="post-container">
                <div class="post-image">
                  <img src="data:image/jpeg;base64, <?php echo $post->image; ?>" alt="Post Image">
                </div>
                <div class="post-details">
                  <h2 class="post-title"><?php echo $post->title?></h2>
                  <p class="post-description"><?php echo $post->description?></p>
                  <p class="post-location"><?php echo $post->location?></p>
                  <p class="post-user">Posted by: <span class="username"><?php echo $post->username?></span></p>
                  <p class="post-user">Average Rate: <span class="username" style="color: orange;"><?php echo $post->averagerate?></span></p>

                  <div class="comment-wrapper">
                    <h3>Comments</h3>
                    <div class="comments-container">
                        <ul class="comment-list">
                        <!-- Comment items will be dynamically added using JavaScript -->
                        <?php foreach ($comments as $comment) { ?>
                            <li class="comment-item">
                                <p>
                                <?php echo $comment->comment; ?>
                                <span class="comment-rating"><?php echo $comment->rate; ?> stars</span>
                                </p>
                                <p class="comment-user">Comment by <?php echo $comment->username; ?></p>
                            </li>
                        <?php } ?>
                        </ul>
                        
                    </div>
                    
                    </div>

                </div>
              </div>

             <div class="commentRating-container">
                <form id="comment-form" method="POST" enctype="multipart/form-data">
                    <textarea id="comment" name="comment" placeholder="Leave a comment..." style="height: 3rem; width: 50rem;"></textarea><br><br>
                    <label for="rating">Rate this post:</label>
                    <select id="rating" name="rating">
                        <option value="5">5 star</option>
                        <option value="4">4 stars</option>
                        <option value="3">3 stars</option>
                        <option value="2">2 stars</option>
                        <option value="1">1 stars</option>
                    </select>
                    <button type="submit" style="border-radius: 10px; background-color: #39375b; color: white; width: 80px; border-color: transparent;"  name="submit">Submit</button>
                </form>
             </div>
              

        </div>

        <script src="js_file.js"></script>

    </body>
</html>