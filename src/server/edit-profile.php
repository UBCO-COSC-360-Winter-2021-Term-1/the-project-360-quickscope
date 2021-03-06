<?php
session_start();
if (!isset($_SESSION['user'])) {
  exit(json_encode(["status" => "home", "message" => "You must be logged in to edit your profile."]));
}
// Check if request method is valid
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Store data in variables
  if ($_POST['username'] == "false")
    $username = false;
  else $username = $_POST['username'];

  if ($_POST['email'] === 'false')
    $email = false;
  else $email = $_POST['email'];

  if (isset($_FILES['profile_pic']))
    if (is_uploaded_file($_FILES['profile_pic']['tmp_name']))
      $file = $_FILES['profile_pic'];
} else {
  exit(json_encode(["status" => "error", "message" => "Invalid request method"]));
}
// Connect to database
include('db_connect.php');
if ($error) {
  exit(json_encode(["status" => "error", "message" => "Something went wrong, please try again."]));
}
// Check image and store it for upload to server
if (isset($file)) {
  $target_file = "avatars/" . basename($_FILES["profile_pic"]["name"]);
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  $uniqueDir = "../server/avatars/" . time() . uniqid(rand()) . "." . $imageFileType;
  // Check if image file is a actual image or fake image
  if (isset($_FILES['profile_pic'])) {
    $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
    if ($check === false) {
      mysqli_close($connection);
      exit(json_encode(["status" => 'error', "message" => 'File is not an image.']));
    }
  }
  // Check file size/type
  if ($_FILES["profile_pic"]["size"] > 1000000) {
    mysqli_close($connection);
    exit(json_encode(["status" => 'error', "message" => 'Sorry, your file is too large.']));
  }
  if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif" && $imageFileType != "jpeg") {
    mysqli_close($connection);
    exit(json_encode(["status" => "error", "message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."]));
  }
  // Upload file to server
  if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $uniqueDir)) {
    // Get current profile picture directory
    $sql = "SELECT avatar FROM users WHERE id = '{$_SESSION['user']}'";
    $result = mysqli_query($connection, $sql);
    if ($result) {
      // Delete the old profile picture
      if ($row = mysqli_fetch_assoc($result))
        if ($row['avatar'] != "../server/avatars/default_profile_pic.png")
          unlink($row['avatar']);
    } else {
      mysqli_close($connection);
      exit(json_encode(["status" => "error", "message" => "Something went wrong, please try again."]));
    }
    $sql = "UPDATE users SET avatar = '$uniqueDir' WHERE id = '{$_SESSION['user']}'";
    $result = mysqli_query($connection, $sql);
    if (!$result) {
      mysqli_close($connection);
      exit(json_encode(["status" => "error", "message" => "Something went wrong, please try again."]));
    } else {
      $_SESSION['avatar'] = $uniqueDir;
      // Update comments avatar if it is changed
      $result = mysqli_query($connection, "UPDATE comments SET user_avatar = '$uniqueDir' WHERE user_id = '{$_SESSION['user']}'");
      if (!$result) {
        mysqli_close($connection);
        exit(json_encode(["status" => "error", "message" => "Something went wrong, please try again."]));
      }
      if (!$username && !$email) {
        mysqli_close($connection);
        exit(json_encode(["status" => "success", "message" => "Profile updated successfully.", "image" => isset($uniqueDir) ? $uniqueDir : false]));
      }
    }
  } else {
    mysqli_close($connection);
    exit(json_encode(["status" => 'error', "message" => 'Something went wrong, please try again.']));
  }
}

// Check if username and email were receieved
if ($username && $email) {
  $sqlcheck = "SELECT id FROM users WHERE username = '$username' OR email = '$email'";
  $result = mysqli_query($connection, $sqlcheck);
  if (mysqli_num_rows($result) > 0) {
    mysqli_close($connection);
    exit(json_encode(["status" => "error", "message" => "Username or email already exists."]));
  }
  $sql = "UPDATE users SET username = '$username', email = '$email' WHERE id = '{$_SESSION['user']}'";
  $result = mysqli_query($connection, $sql);
  mysqli_close($connection);

  if ($result) {
    exit(json_encode([
      "status" => "success", "message" => "Profile updated successfully.",
      "image" => isset($uniqueDir) ? $uniqueDir : false, "username" => $username, "email" => $email
    ]));
  } else {
    exit(json_encode(["status" => "error", "message" => "Something went wrong, please try again."]));
  }
  // Check if username was received
} else if ($username) {
  $sqlcheck = "SELECT id FROM users WHERE username = '$username'";
  mysqli_query($connection, $sqlcheck);
  $result = mysqli_query($connection, $sqlcheck);
  if (mysqli_num_rows($result) > 0) {
    mysqli_close($connection);
    exit(json_encode(["status" => "error", "message" => "Username already exists."]));
  }
  $sql = "UPDATE users SET username = '$username' WHERE id = '{$_SESSION['user']}'";
  $result = mysqli_query($connection, $sql);
  mysqli_close($connection);
  if ($result) {
    exit(json_encode([
      "status" => "success", "message" => "Profile updated successfully.",
      "image" => isset($uniqueDir) ? $uniqueDir : false, "username" => $username
    ]));
  } else {
    exit(json_encode(["status" => "error", "message" => "Something went wrong, please try again."]));
  }
  // Check if email was received
} else if ($email) {
  $sqlcheck = "SELECT id FROM users WHERE email = '$email'";
  mysqli_query($connection, $sqlcheck);
  $result = mysqli_query($connection, $sqlcheck);
  if (mysqli_num_rows($result) > 0) {
    mysqli_close($connection);
    exit(json_encode(["status" => "error", "message" => "Email already exists."]));
  }
  $sql = "UPDATE users SET email = '$email' WHERE id = '{$_SESSION['user']}'";
  $result = mysqli_query($connection, $sql);
  mysqli_close($connection);

  if ($result) {
    exit(json_encode([
      "status" => "success", "message" => "Profile updated successfully.",
      "image" => isset($uniqueDir) ? $uniqueDir : false,
      "email" => $email
    ]));
  } else {
    exit(json_encode(["status" => "error", "message" => "Something went wrong, please try again."]));
  }
}
