<?php
session_start();
require_once('../GIGS/Private/dbconnection.php');

$conn = db_connect();

$response = ["status" => "error", "message" => "Failed to update seen status."];

// Optional: Check if the user is logged in (modify as per your session structure)
if (!isset($_SESSION['username'])) {
    $response = ["status" => "error", "message" => "User not logged in."];
    echo json_encode($response);
    exit;
}

if (isset($_POST['from']) && isset($_POST['toUser'])) {
    $from = $_POST['from'];
    $toUser = $_POST['toUser'];

    // Prepare the SQL statement to update seen status only for unseen messages
    $stmt = $conn->prepare("UPDATE newchat SET seen = 1 WHERE `from` = ? AND `toUser` = ? AND seen = 0");

    if ($stmt) {
        $stmt->bind_param("ss", $from, $toUser);
        $stmt->execute();

        // Check if any rows were updated
        if ($stmt->affected_rows > 0) {
            $response = ["status" => "success", "message" => "Messages marked as seen."];
        } else {
            $response = ["status" => "info", "message" => "No messages to update or already marked as seen."];
        }

        $stmt->close();
    } else {
        $response = ["status" => "error", "message" => "Database query failed: " . $conn->error];
    }
} else {
    $response = ["status" => "error", "message" => "Invalid parameters."];
}

echo json_encode($response);
$conn->close();
?>
