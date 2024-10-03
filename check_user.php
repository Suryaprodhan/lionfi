<?php
header('Content-Type: application/json');

// Function to log messages to a file for debugging
function logMessage($message) {
    $logfile = 'log.txt';
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($logfile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "sql206.infinityfree.com";
    $username = "if0_37403816";
    $password = "AsdfghjKl";
    $dbname = "if0_37403816_lionstar";
    $bot_token = "7639835056:AAF6F1Pos-mKnjXsrSVQMk9JyKsFcKnArHQ"; // Replace with your bot token

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        logMessage("Connection failed: " . $conn->connect_error);
        echo json_encode(['error' => "Connection failed: " . $conn->connect_error]);
        exit();
    }

    $user_id = $conn->real_escape_string($_POST['user_id']);
    $user_name = $conn->real_escape_string($_POST['user_name']);

    logMessage("Received user_id: $user_id, user_name: $user_name");

    // Check if user exists
    $sql = "SELECT * FROM lionstarairdrop WHERE user_id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User exists
        $user_exists = true;
        logMessage("User exists in the database.");
    } else {
        // User does not exist
        $user_exists = false;
        logMessage("User does not exist in the database.");
    }

    // Fetch user profile photo
    $url = "https://api.telegram.org/bot$bot_token/getUserProfilePhotos?user_id=$user_id";
    logMessage("Fetching profile photos with URL: $url");
    $response = file_get_contents($url);
    $profile_data = json_decode($response, true);
    logMessage("Profile data response: " . print_r($profile_data, true));

    if ($profile_data['ok'] && count($profile_data['result']['photos']) > 0) {
        $photos = $profile_data['result']['photos'];
        $profile_photo = end($photos[0]); // Get the highest resolution photo
        $file_id = $profile_photo['file_id'];

        // Get file path
        $file_path_url = "https://api.telegram.org/bot$bot_token/getFile?file_id=$file_id";
        logMessage("Fetching file path with URL: $file_path_url");
        $file_path_response = file_get_contents($file_path_url);
        $file_path_data = json_decode($file_path_response, true);
        logMessage("File path data response: " . print_r($file_path_data, true));
        if ($file_path_data['ok']) {
            $file_path = $file_path_data['result']['file_path'];
            $profile_image_url = "https://api.telegram.org/file/bot$bot_token/$file_path";
            logMessage("Profile image URL: $profile_image_url");
        } else {
            $profile_image_url = null;
            logMessage("Failed to get file path.");
        }
    } else {
        $profile_image_url = null;
        logMessage("No profile photos found or failed to fetch profile photos.");
    }

    // Prepare the response
    $response = [
        'exists' => $user_exists,
        'profile_image_url' => $profile_image_url
    ];

    echo json_encode($response);
    $conn->close();
    exit();
}
?>
