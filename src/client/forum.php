<?php
if ($_SERVER['REQUEST_METHOD'] != "GET")
  header("Location: home.php");
require("../server/db_connect.php");
if ($error)
  die($error);
$id = $_GET['id'];
// Query for forum
$query = "SELECT * FROM forums WHERE id = '$id'";
$result = mysqli_query($connection, $query);
if (!$result)
  die("Error: " . mysqli_error($connection));
else {
  // Query for posts inside queried forum
  $row = mysqli_fetch_assoc($result);
  $title = $row['name'];
  $query = "SELECT * FROM posts WHERE forum_id = '$id'";
  $result = mysqli_query($connection, $query);
  if (!$result)
    die("Error: " . mysqli_error($connection));
  else {
    $posts = array();
    while ($row = mysqli_fetch_array($result)) {
      $posts[] = $row;
    }
  }
}
$forum = mysqli_fetch_assoc($result);
$title = $title;// . " | Quickscope 🎯";
require('components/head.php');
require('components/header.php');
?>

<div class="container-fluid post-container">
  <div class="row">
    <!-- This column shows most recent post from each forum -->
    <div class="col-lg-7 offset-lg-1">
      <!-- Create fake posts by changing $i threshold -->
      <?php
      
      echo'<div class="card mb-2">
      <div class="card-body"><h4>'.$title.'</h4></div>
      </div>';

      foreach ($posts as $post) {
          // Print the first post
          echo '
          <div class="card col-lg-12 mb-2">
            <div class="card-body">
              <h5 class="card-title">'.$post['title'].'</h5>
              <p class="card-text">'.$post['description'].'</p>
              <a href="#" class="btn btn-dark">Go somewhere</a>
            </div>
            <div class="col-10 offset-1">
              <img src="img/kermit.png" class="card-img-bottom" />
            </div>
            <div class="col-1 offset-1 comment-stuff d-flex mt-3">
              <span style="font-size: 1.25rem;" class="comment-count d-flex">
                <i class="bi bi-chat-left-text-fill"></i>
                <span>&nbsp;0</span>
              </span>
            </div>
          </div>';
        //  else {
        //   // Print the rest of the posts
        //   echo '<div class="card col-lg-12 mb-2">
        //       <div class="card-body">
        //         <h5 class="card-title">Card title</h5>
        //         <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>
        //         <a href="#" class="btn btn-dark">Go somewhere</a>
        //       </div>
        //       <div class="col-10 offset-1">
        //         <img src="img/kermit.png" class="card-img-bottom" />
        //       </div>
        //       <div class="col-1 offset-1 comment-stuff d-flex mt-3">
        //         <span style="font-size: 1.25rem;" class="comment-count d-flex">
        //           <i class="bi bi-chat-left-text-fill"></i>
        //           <span>&nbsp;0</span>
        //         </span>
        //       </div>
        //     </div>';
        // }
      }
      ?>
    </div>
    <!-- Recent posts card -->
    <div class="col-3 d-none d-lg-block">
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title">Recent Posts</h5>
          <ul class="list-group list-group-flush recent-list">
            <?php include('../server/get_recent_posts.php'); ?>
        </div>
      </div>
      <!-- Create a forum card -->
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title">Join or Create Your Own Community!</h5>
          <div class="post-forum-buttons d-flex flex-column">
            <a href="create-post.php" class="btn btn-primary mb-3">Create a Post</a>
            <a href="#" class="btn btn-danger">Create a Forum</a>
          </div>
        </div>
      </div>
      <div class="card about-us-home mb-3">
        <div class="card-body">
          <h5 class="card-title">Quickscope Information</h5>
          <div class="row">
            <a class="col text-decoration-none text-secondary" href="about.html">
              <p class="font-title-12 mt-3">About Us</p>
            </a>
            <a class="col text-decoration-none text-secondary" href="privacy.html">
              <p class="font-title-12 mt-3">Privacy Policy</p>
            </a>
          </div>
          <div class="row">
            <a class="col text-decoration-none text-secondary" href="terms.html">
              <p class="font-title-12">Terms</p>
            </a>
            <a class="col text-decoration-none text-secondary" href="contact.html">
              <p class="font-title-12">Contact</p>
            </a>
          </div>
          <div class="row">
            <a class="col text-decoration-none text-secondary" href="allforums.php">
              <p class="font-title-12">All Forums</p>
            </a>
            <a class="col text-decoration-none text-secondary" href="https://www.youtube.com/watch?v=Udvwea3YzZA">
              <p class="font-title-12">Our Origins</p>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $scripts = ['js/custom.js'];
require('components/scripts.php'); ?>