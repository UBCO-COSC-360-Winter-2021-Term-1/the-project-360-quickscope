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
  $query = "SELECT * FROM posts WHERE forum_id = '$id' ORDER BY created_at DESC";
  $result = mysqli_query($connection, $query);
  $isArray = false;
  if (mysqli_num_rows($result) != 0) {
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $isArray = true;
    mysqli_free_result($result);
    mysqli_close($connection);
  }
}
$title = $title . " | Quickscope 🎯";
require('components/head.php');
require('components/header.php');
?>

<div class="container-fluid post-container">
  <div class="row">
    <div class="col-lg-7 offset-lg-1">
      <div class="h2 text-white">
        All Activity
      </div>
      <?php

      echo '<div class="card mb-2">
      <div class="card-body"><h4>' . $title . '</h4></div>
      </div>';
      if ($isArray) {
        foreach ($posts as $post) {
          $hasImage = isset($post['image']) ? '' : 'd-none';
          echo '
          <div class="card col-lg-12 mb-2">
            <div class="card-body">
              <h5 class="card-title">' . $post['title'] . '</h5>
              <p class="card-text">' . $post['description'] . '</p>
              <a href="post.php?id=' . $post['id'] . ' " class="btn btn-dark">See Post</a>
            </div>
            <div class="col-10 offset-1 ' . $hasImage . '">
              <img src="' . $post['image'] . '" class="card-img-bottom" />
            </div>
            <div class="col-1 comment-stuff d-flex">
              <a href="post.php?id=' . $post['id'] . '" class="comment-count fs-4 text-decoration-none text-dark d-flex">
                <i class="bi bi-chat-square-dots"></i>
                <span>&nbsp;0</span>
              </a>
            </div>
          </div>';
        }
      } else {
        echo '<div class="card h2 text-center mt-5 py-3">No posts yet, be the first!</div>';
      }
      ?>
    </div>
    <!-- Recent posts card -->
    <div class="col-3 d-none d-lg-block">
      <div class="h2" style="visibility:hidden">placeholder</div>
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