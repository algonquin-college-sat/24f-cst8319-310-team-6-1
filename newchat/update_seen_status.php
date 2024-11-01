<?php
session_start();
require_once('../GIGS/Private/dbconnection.php');

// Use the correct database connection variable
$conn = db_connect();

$response = ["status" => "error", "message" => "Failed to update seen status."];

if (isset($_POST['from']) && isset($_POST['toUser'])) {
    $from = $_POST['from'];
    $toUser = $_POST['toUser'];

    // Prepare the SQL statement to update seen status
    $stmt = $conn->prepare("UPDATE newchat SET seen = 1 WHERE `from` = ? AND `toUser` = ? AND seen = 0");

    if ($stmt) {
        $stmt->bind_param("ss", $from, $toUser);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response = ["status" => "success", "message" => "Messages marked as seen."];
        } else {
            $response = ["status" => "error", "message" => "No messages to update or already marked as seen."];
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
