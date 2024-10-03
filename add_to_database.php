<?php
$servername = "sql110.infinityfree.com";
    $username = "if0_37115834";
    $password = "JKIGuiBUdr";
    $dbname = "if0_37115834_lionfi";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user_id = $_POST['user_id'];
  $user_name = $_POST['user_name'];
  $profile_image_url = $_POST['profile_image_url'];
  $balance = $_POST['balance'];
  $date = $_POST['date'];

  $stmt = $conn->prepare("INSERT INTO lionstarairdrop (user_id, name, profile_image_url, balance, date) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssis", $user_id, $user_name, $profile_image_url, $balance, $date);

  if ($stmt->execute()) {
    echo "success";
  } else {
    echo "error: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
}
?>
