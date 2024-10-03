<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "sql110.infinityfree.com";
    $username = "if0_37115834";
    $password = "JKIGuiBUdr";
    $dbname = "if0_37115834_lionfi";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        echo json_encode(['error' => "Connection failed: " . $conn->connect_error]);
        exit();
    }

    $user_id = $conn->real_escape_string($_POST['user_id']);

    $sql = "SELECT * FROM lionstarairdrop WHERE user_id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }

    $conn->close();
    exit();
}
?>
