<?php

session_start();
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}

$user = 'root';
$pass = '';
$db = 'travelgram';

$conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");

// Query to retrieve the notification date
$notificationQuery = "SELECT c.postID, u.username, p.title, c.datecreated FROM postcomrate c JOIN profile u ON c.username = u.username JOIN post p ON c.postID = p.postID WHERE p.username = '$username' ORDER BY c.datecreated DESC";
$notificationResult = mysqli_query($conn, $notificationQuery);

// Initialize an empty array to store the notification data
$notificationData = array();

// Check if the query was successful
if ($notificationResult && mysqli_num_rows($notificationResult) > 0) {
    // Fetch the rows from the result set and store them in the array
    while ($row = mysqli_fetch_assoc($notificationResult)) {
        $notificationData[] = $row;
    }
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

<html>
<head>
    <title>Notification</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="shortcut icon" type="image/x-icon" href="Pictures/travelgramIcon.svg">
    <link rel="stylesheet" href="css_file.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
        <p id="userNameNotification" style="padding-left: 30px; width: 1000px; font-family: 'Poppins'; font-style: normal; font-weight: 1000; font-size: 30px; padding-top: 20px;">NOTIFICATION</p><br>
        <div class="notifications-container">
            <div class="post-grid-Notification">
                <ul class="notifications-list">

                    <?php if (!empty($notificationData)): ?>
                        <?php foreach ($notificationData as $notification): ?>

                            <!-- Display each notification here -->
                            <li>
                                <div class="notification-details">
                                    <div class="notification-details">
                                        <p><strong><?php echo $notification['username']; ?></strong> interacted with your post:</p>
                                        <a href="FeedViewPost.php?postID=<?php echo $notification['postID'] ?>"><p><?php echo $notification['title']; ?></p></a>
                                        <p class="notification-timestamp"><?php echo $notification['datecreated']; ?></p>
                                        &nbsp;
                                    </div>
                                </div>
                            </li>

                        <?php endforeach; ?>

                    <?php else: ?>
                        <!-- Display a message when there are no notifications -->
                        <li>No notifications found.</li>

                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <script src="js_file.js"></script>

</body>
</html>
