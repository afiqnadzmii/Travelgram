<?php

session_start();
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}

$user = 'root';
$pass = '';
$db = 'travelgram';

$conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");

// Query to retrieve the number of posts
$postQuery = "SELECT COUNT(*) AS numPosts FROM post WHERE username = '$username'";
$postResult = mysqli_query($conn, $postQuery);
if ($postResult) {
    $row = mysqli_fetch_assoc($postResult);
    $numPosts = $row['numPosts'];
} else {
    $numPosts = "N/A"; // Set a default value if the query fails
}

// Query to calculate the rating ratio
$ratingQuery = "SELECT AVG(rate) AS ratingRatio FROM postcomrate WHERE postID IN (SELECT postID FROM Post WHERE username = '$username')";

$ratingResult = mysqli_query($conn, $ratingQuery);
if ($ratingResult) {
    $row = mysqli_fetch_assoc($ratingResult);
    $ratingRatio = $row['ratingRatio'];
    $ratingRatio = round($ratingRatio, 2); // Round the rating ratio to 2 decimal places
} else {
    $ratingRatio = "N/A"; // Set a default value if the query fails
}

// Query to retrieve the most popular post
$popularPostQuery = "
    SELECT p.title 
    FROM post p 
    JOIN postcomrate r ON p.postID = r.postID 
    WHERE p.username = '$username' 
    ORDER BY r.rate DESC 
    LIMIT 1
";

$popularPostResult = mysqli_query($conn, $popularPostQuery);
if ($popularPostResult) {
    $row = mysqli_fetch_assoc($popularPostResult);
    $mostPopularPost = $row['title'];
} else {
    $mostPopularPost = "N/A"; // Set a default value if the query fails
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

?>

<html>
    <head>
        <title>Analytics</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link rel="shortcut icon" type="image/x-icon" href="Pictures/travelgramIcon.svg" >
        <link rel="stylesheet" href="css_file.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <meta name="description" content="Our home page">
        <meta name="keywords" content="Web Prog Project">
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
            <p id="userNameAnalytics" style="padding-left: 30px;"><?php echo $nav->fname ?>'s ANALYTICS</p><br>
            <div class="post-grid-Analytics">
                <div class="post-item-Analytics">
                    <div class = "overview">                        
                        <h3><strong>OVERVIEW</strong></h3><br>
                        <div class="overviewData">
                            <p>POSTS: <span id="numPosts"><strong><?php echo $numPosts; ?></strong></span></p>
                            <p>RATING RATIO: <span id="numRatingRatio"><strong><?php echo $ratingRatio; ?></strong></span></p>
                            <p>MOST POPULAR POST: <span id="numMostPopularPosts"><strong><?php echo $mostPopularPost; ?></strong></span></p>                            
                        </div>
                    </div>
                </div>

                <div class="post-item-Analytics">
                    <div class="ratingRatio">
                        <strong>RATING RATIO</strong><br><br>
                        <div id="ratingRatioChart" style="width:200px; height:200px" ></div>
                        <div class="ratingRatioData">
                            
                            <?php
                            // Query to get the number of ratings for the user
                            $numRatingsQuery = "SELECT COUNT(*) AS numRatings FROM PostComRate WHERE postID IN (SELECT postID FROM Post WHERE username = '$username')";
                            $numRatingsResult = mysqli_query($conn, $numRatingsQuery);

                            if ($numRatingsResult && mysqli_num_rows($numRatingsResult) > 0) {
                                $row = mysqli_fetch_assoc($numRatingsResult);
                                $numRatings = $row['numRatings'];
                            } else {
                                $numRatings = 0; // Set a default value if the query fails or there are no ratings
                            }

                            // Query to get the number of specific rating values for the user
                            $ratingValuesQuery = "SELECT rate, COUNT(*) AS count FROM PostComRate WHERE postID IN (SELECT postID FROM Post WHERE username = '$username') GROUP BY rate";
                            $ratingValuesResult = mysqli_query($conn, $ratingValuesQuery);

                            $ratingData = array(); //initialize an array to hold the rating data

                            if ($ratingValuesResult && mysqli_num_rows($ratingValuesResult) > 0) {
                                while ($row = mysqli_fetch_assoc($ratingValuesResult)) {
                                    $ratingValue = $row['rate'];
                                    $count = $row['count'];
                                    $percentage = ($count / $numRatings) * 100;
            
                                    // Output the rating value and percentage
                                    // echo "<p><strong>" . round($percentage) . "%</strong> $ratingValue Stars</p>";

                                    // add the rating value, count, and percentage to the rating data array
                                    $ratingData[] = array(
                                        'rating'=> $ratingValue,
                                        'count' => $count,
                                        'percentage' => round($percentage)
                                    );
                                }

                            } else {

                                // Set default values if the query fails or there are no ratings
                                $ratingValues = array(
                                '5' => 0,
                                '4' => 0,
                                '3' => 0,
                                '2' => 0,
                                '1' => 0
                                );
        
                                foreach ($ratingValues as $ratingValue => $count) {
                                    $percentage = ($count / $numRatings) * 100;
            
                                    // Output the rating value and percentage
                                    // echo "<p><strong>" . round($percentage) . "%</strong> $ratingValue Stars</p>";

                                    // add the rating value, count and percentage to the rating data array 
                                    $ratingData[] = array(
                                        'rating' => $ratingValue,
                                        'count' => $count,
                                        'percentage' => round($percentage)
                                    );
                                }
                            }

                            ?>
                            <script type="text/javascript">
                                google.charts.load('current', {'packages':['corechart']});
                                google.charts.setOnLoadCallback(drawChart);

                                function drawChart() {
                                    var data = new google.visualization.DataTable();
                                    data.addColumn('string', 'Rating');
                                    data.addColumn('number', 'Percentage');
                                    
                                    <?php
                                    // Loop through the rating data array and output the data to JavaScript
                                    foreach ($ratingData as $rating) {
                                        echo "data.addRow(['" . $rating['rating'] . "', " . $rating['percentage'] . "]);";
                                    }
                                    ?>
                                    
                                    var options = {
                                        backgroundColor: 'transparent',
                                        width: 400,
                                        height: 200
                                    };
                                    
                                    var chart = new google.visualization.PieChart(document.getElementById('ratingRatioChart'));
                                    chart.draw(data, options);
                                }
                            </script>

                            
                        </div>
                    </div>
                </div>  

                <div class="post-item-Analytics">
                    <div class = "mostPopularPosts"><strong>MOST POPULAR POSTS</strong><br><br>
                        <div class="mostPopularPostsData">

                            <?php

                            // Query to retrieve the top 5 most popular posts for the specific user
                            $popularPostsQuery = "
                                SELECT title 
                                FROM Post 
                                WHERE username = '$username' 
                                ORDER BY (
                                    SELECT AVG(rate) 
                                    FROM PostComRate 
                                    WHERE PostComRate.postID = Post.postID
                                ) DESC 
                                LIMIT 5";
                            
                            $popularPostsResult = mysqli_query($conn, $popularPostsQuery);
                            
                            if ($popularPostsResult) {
                                $counter = 1;
                                while ($row = mysqli_fetch_assoc($popularPostsResult)) {
                                    $title = $row['title'];
                                    echo "<p>$counter. <strong>$title</strong></p>";
                                    $counter++;
                                }
                            } else {
                                echo "No popular posts found.";
                            }
                            ?>
                              
                        </div>
                    </div>
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
    
                function logout_alert(){
                    alert("Are you sure you want to logout?");
                }
    
        </script>

        <script src="js_file.js"></script>

    </body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
