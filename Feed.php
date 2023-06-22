<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: Login.php'); // Redirect to the login page or any other page you prefer
    exit;
}

if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}

$user = 'root';
$pass = '';
$db = 'travelgram';

$conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Retrieve the form data
    $title = $_POST['title'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $image = $_FILES['image']['tmp_name'];

    // Read the image file
    $imgData = file_get_contents($image);
    $imgData = $conn->real_escape_string($imgData);

    // Insert the data into the database
    $query = "INSERT INTO post (username, image, title, description, location) VALUES ('$username', '$imgData', '$title', '$description', '$location')";
    $result = $conn->query($query);

    // Insert the data into the database
    $query2 = "UPDATE profile SET numPost = (SELECT COUNT(*) FROM post WHERE username = '$username') WHERE username = '$username'";
    $result2 = $conn->query($query2);

    if ($result && $result2) {
        // Success! Data inserted into the database.
        // Redirect to the feed page to display the updated posts
        header("Location: Feed.php");
        exit();
    } else {
        // Error occurred while inserting data
        echo "Error: " . $conn->error;
    }
}

// Retrieve data from the database
$query = "SELECT postid, image, title, location FROM post ORDER BY postID DESC";
$result = $conn->query($query);

$posts = array();

// Fetch each row from the result set and create objects
while ($row = $result->fetch_assoc()) {
    $post = new stdClass();
    $post->postID = $row['postid'];
    $post->picture = base64_encode($row['image']); // Convert the binary image data to base64
    $post->title = $row['title'];
    $post->location = $row['location'];

    // Add the post object to the array
    $posts[] = $post;
}

// Convert the PHP array to JSON
$posts_json = json_encode($posts);

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
        <title>Feed</title>
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
                    <li><a href="Logout.php" class="logout" onclick="return logout_alert()">LOG OUT</a></li>
                </ul>
            </nav>
        </div>

        <div id="main" class="content">
            
            <button id="openForm" style="margin-bottom: 1rem; border-radius: 10px; background-color: #d9b8c4; color: black; border: transparent; font-weight: 600;" type="button" class="btn btn-secondary btn-lg">Create Post</button>
            <!-- The Modal -->
            <div id="myModal" class="modal">
 sdjnsjdssds
              <!-- Modal content -->
              <div class="modal-content">
                  <!-- <p>Some text in the Modal..</p> -->
                  <form id="myForm" method="POST" enctype="multipart/form-data">
                      <h3>New Journey?</h3><br>
                      <input id="choosefile" type="file" name="image" accept="image/" required ><br><br>
                      <input type="text" name="title" style="width: 30rem;" placeholder="Title" required><br><br>
                      <input type="text" name="location" style="width: 30rem;" placeholder="Location" required><br><br>
                      <textarea name="description" style="width: 30rem; height: 17rem;" placeholder="Description" required></textarea><br><br>
                      <button type="submit" style="border-radius: 10px; background-color: #39375b; color: white; width: 80px; border-color: transparent;"  name="submit">Post</button>
                      <button type="button" style="border-radius: 10px; background-color: #F4F3FF; width: 80px; border-color: transparent;" onclick="cancelAddPost()">Cancel</button>
                  </form>
              </div>
  
          </div>
          <div class="post-grid-Feed">
            <?php foreach ($posts as $post) { ?>
              <a href="FeedViewPost.php?postID=<?php echo $post->postID; ?>">
                <div class="post-item-Feed">
                  <img src="data:image/jpeg;base64, <?php echo $post->picture; ?>" alt="Post image">
                  <h2><?php echo $post->title; ?></h2>
                  <p><?php echo $post->location; ?></p>
                </div>
              </a>
            <?php } ?>
          </div>

        </div>

        <script src="js_file.js"></script>

    </body>
</html>
