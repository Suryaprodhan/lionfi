<?php
header('Content-Type: application/json');

error_log("check_user.php accessed");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST request received");

    $servername = "sql110.infinityfree.com";
    $username = "if0_37115834";
    $password = "JKIGuiBUdr";
    $dbname = "if0_37115834_lionfi";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        echo json_encode(['error' => "Connection failed: " . $conn->connect_error]);
        exit();
    }

    $user_id = $conn->real_escape_string($_POST['user_id']);

    error_log("User ID: " . $user_id);

    $sql = "SELECT * FROM lionstarairdrop WHERE user_id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        error_log("User found in database");
        echo json_encode(['exists' => true]);
    } else {
        error_log("User not found in database");
        echo json_encode(['exists' => false]);
    }

    $conn->close();
    error_log("Connection closed");
    exit();
} else {
    error_log("Invalid request method");
    echo json_encode(['error' => "Invalid request method"]);
    exit();
}
?>
