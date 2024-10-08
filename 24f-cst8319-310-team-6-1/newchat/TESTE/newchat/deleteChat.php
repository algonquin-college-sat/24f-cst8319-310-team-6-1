<?php

include('./dbconnection.php');

// Check if the message IDs are provided
if(isset($_POST['id'])) {
    $messageIds = $_POST['id'];

    // Connect to the database
    $db = db_connect();

    // Prepare the SQL query to delete messages
    $sql = "DELETE FROM `newchat` WHERE `id` IN (";
    $sql .= implode(",", array_fill(0, count($messageIds), "?"));
    $sql .= ")";

    // Prepare the statement
    $stmt = $db->prepare($sql);

    // Bind parameters
    $types = str_repeat("i", count($messageIds));
    $stmt->bind_param($types, ...$messageIds);

    // Execute the query
    if($stmt->execute()) {
        // Deletion successful
        $response = array('success' => true, 'message' => 'Messages deleted successfully');
    } else {
        // Deletion failed
        $response = array('success' => false, 'message' => 'Failed to delete messages: ' . $stmt->error);
    }

    // Close the statement and connection
    $stmt->close();
    $db->close();
} else {
    // If message IDs are not provided
    $response = array('success' => false, 'message' => 'Message IDs not provided');
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

?>
