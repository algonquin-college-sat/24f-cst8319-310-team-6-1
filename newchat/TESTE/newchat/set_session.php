<?php
    session_start();
    
    // Check if notification data is received
    if(isset($_POST['message']) && isset($_POST['type'])) {
        // Set session variables for notification
        $_SESSION['notification_message'] = $_POST['message'];
        $_SESSION['notification_type'] = $_POST['type'];
        
        // Optional: Return a response
        echo "Session variable set successfully!";
    } else {
        // Optional: Return an error response if needed
        echo "Error: Notification data not received!";
    }
?>
