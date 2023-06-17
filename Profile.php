<?php

session_start();
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}

$user = 'root';
$pass = '';
$db = 'travelgram';

$conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");

// Retrieve data from the database
$query = "SELECT postId, image, title, location FROM post WHERE username = '$username' ORDER BY postId DESC";
$result = $conn->query($query);

$posts = array();

// Fetch each row from the result set and create objects
while ($row = $result->fetch_assoc()) {
    $post = new stdClass();
    $post->postID = $row['postId'];
    $post->picture = base64_encode($row['image']); // Convert the binary image data to base64
    $post->title = $row['title'];
    $post->location = $row['location'];

    // Add the post object to the array
    $posts[] = $post;
}

// Convert the PHP array to JSON
$posts_json = json_encode($posts);

// Retrieve data from the database
$query2 = "SELECT profileImage, name, username, bio, numPost, avgRating FROM profile WHERE username = '$username'";
$result2 = $conn->query($query2);

// $profiles= array();

// Fetch each row from the result set and create objects
if ($result2->num_rows > 0) {
    $row = $result2->fetch_assoc();

    $profile = new stdClass();
    $profile->username = $row['username'];
    $profile->picture = base64_encode($row['profileImage']); // Convert the binary image data to base64
    $profile->fname = $row['name'];
    $profile->bio = $row['bio'];
    $profile->no_post = $row['numPost'];
    $profile->avg_rating = $row['avgRating'];

    // Add the post object to the array
    $profiles[] = $profile;
    // Convert the PHP array to JSON
    $profiles_json = json_encode($profiles);
}

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
    <title>Profile</title>
    <link rel="shortcut icon" type="image/x-icon" href="Pictures/travelgramIcon.svg">
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
                    <li><a href="Login.php" class="logout" onclick="return logout_alert()">LOG OUT</a></li>
                </ul>
            </nav>
        </div>

<div id="main" class="content">
    <p id="myProfile" style="padding-left: 30px;">MY PROFILE</p><br>

    <div class="MyProfile-item">
        <img id="profilePic" src="data:image/jpeg;base64, <?php echo $profile->picture; ?>" alt="profilepic" style="border-radius: 50%; padding: 20px;" width="230px" height="230px"><br><br>

        <div class="profile-details">
            <h2 class="profile-name" id="fname" name="name"><?php echo $profile->fname ?></h2> 
            <p class="profile-id" id="username" name="username">@<?php echo $profile->username ?></p>
            <p class="profile-bio" id="bio" name="bio"><?php echo $profile->bio ?></p>
            <p class="profile-post-rating" id="no_post" name="no_post">Posts: <span class="profile-value"><?php echo $profile->no_post ?></span></p>
            <p class="profile-post-rating" id="avg_rating" name="avg_rating">Average rating: <span class="profile-value"><?php echo $profile->avg_rating ?></span></p>
            <a href="EditProfile.php?username=<?php echo $username?>"><button type="button"  style="border-radius: 10px; background-color: #39375b; color: white; width: 120px; border-color: transparent;" name="edit">Edit Profile</button></a>
        </div>

    </div>

    <br>

    </div>
          <div class="post-grid-Feed">
            <?php foreach ($posts as $post) { ?>
                <div class="post-item-Feed">
                  <img src="data:image/jpeg;base64, <?php echo $post->picture; ?>" alt="Post image">
                  <h2><?php echo $post->title; ?></h2>
                  <p><?php echo $post->location; ?></p>
                  <a href="ManagePost.php?postID=<?php echo $post->postID; ?>"><button type="button"  style="border-radius: 10px; background-color: #39375b; color: white; width: 80px; border-color: transparent;" name="edit">Edit</button></a>
                  <a href="FeedViewPost.php?postID=<?php echo $post->postID; ?>"><button type="button"  style="border-radius: 10px; background-color: white; color: #39375b; width: 80px; border-color: #39375b;" name="view">View</button></a>
                </div>
            <?php } ?>
          </div>

        </div>

</div>

<script src="js_file.js"></script>

</body>
</html>
