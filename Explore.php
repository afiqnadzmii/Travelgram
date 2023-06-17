<?php
session_start();

$user = 'root';
$pass = '';
$db = 'travelgram';

$conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");

if(isset($_SESSION['username'])){
  $username = $_SESSION['username'];

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
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    // Retrieve the form data
    $searchItem = $_POST['searchitem'];

    // Retrieve filter options
    $filterOptions = isset($_POST['filter-group']) ? $_POST['filter-group'] : array();
    
    // Construct the filter condition
    $filterCondition = "";
    if (in_array("topic", $filterOptions)) {
      $filterCondition .= " title LIKE '%$searchItem%'";
    }
    if (in_array("people", $filterOptions)) {
      $filterCondition .= " username LIKE '%$searchItem%'";
    }
    if (in_array("tags", $filterOptions)) {
      $filterCondition .= " description LIKE '%$searchItem%'";
    }
    if (in_array("places", $filterOptions)) {
      $filterCondition .= " location LIKE '%$searchItem%'";
    }
    if (in_array("ratings", $filterOptions)) {
      $filterCondition .= " averageRate >= '$searchItem'";
    }

    if(!empty($searchItem) && empty($filterCondition)){
      // Retrieve data from the database
    $query = "SELECT postid, image, title, location FROM post WHERE username LIKE '%$searchItem%' OR title LIKE '%$searchItem%' OR description LIKE '%$searchItem%' OR location LIKE '%$searchItem%' OR averageRate LIKE '%$searchItem%' ORDER BY postID DESC";

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
  }
  elseif(!empty($searchItem) && !empty($filterCondition)){
      // Retrieve data from the database
      $query = "SELECT postid, image, title, location FROM post WHERE $filterCondition ORDER BY postID DESC";

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
  }

}

$conn->close();
?>

<html>
    <head>
        <title>Explore</title>
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

            <div class="search-bar" style="margin-bottom: 2rem;">
              <form id="searchForm" method="POST" enctype="multipart/form-data">
                <input id="search_form" name="searchitem" type="text" placeholder="Search...">
                <button id="search" name="search" type="submit">Search</button>
                <div class="filters">
                  <label>
                    <input type="checkbox" name="filter-group[]" value="topic" onclick="handleCheckboxClick(this)">
                    Topic
                  </label>
                  <label>
                    <input type="checkbox" name="filter-group[]" value="people" onclick="handleCheckboxClick(this)">
                    People
                  </label>
                  <label>
                    <input type="checkbox" name="filter-group[]" value="tags" onclick="handleCheckboxClick(this)">
                    Tags
                  </label>
                  <label>
                    <input type="checkbox" name="filter-group[]" value="places" onclick="handleCheckboxClick(this)">
                    Places
                  </label>
                  <label>
                    <input type="checkbox" name="filter-group[]" value="ratings" onclick="handleCheckboxClick(this)">
                    Ratings
                  </label>
                </div>
              </form>  
            </div>

            <div id="searching">
              <?php if (isset($posts)) { ?>
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
              <?php } ?>
            </div>

        </div>

        <script src="js_file.js"></script>

    </body>
</html>
