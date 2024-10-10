<?php
// remove_availability.php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['username'])) {
        header("Location: ../Public/index.php");
        exit();
    }

    require_once('./dbconnection.php');

    $availability_id = $_POST['availability_id'];

    $db = db_connect();

    $sql_delete = "DELETE FROM gigworker_availability WHERE id='$availability_id'";
    if ($db->query($sql_delete) === TRUE) {
        $_SESSION['notification_message'] = 'Availability removed successfully.';
        $_SESSION['notification_type'] = 'success';
    } else {
        $_SESSION['notification_message'] = 'Error removing availability: ' . $db->error;
        $_SESSION['notification_type'] = 'error';
    }

    header("Location: displayprofile.php");
    exit();
} else {
    header("Location: ../Public/index.php");
    exit();
}
?>