<?php
session_start();
if (isset($_SESSION['username'])) {
    // User is logged in
    // Access and actions for logged-in users can go here

} else {
    // Neither user is logged in
    // Redirect to the login page
    header("Location: /index.php");
}
?>