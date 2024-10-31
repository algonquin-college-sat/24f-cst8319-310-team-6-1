<?php
// save_availability.php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['username'])) {
        header("Location: ../Public/index.php");
        exit();
    }

    require_once('./dbconnection.php');
    
    $username = $_SESSION['username'];
    $day = $_POST['day'];
    $from = $_POST['from'];
    $to = $_POST['to'];

    $db = db_connect();

    $sql = "SELECT id FROM gigworker WHERE userName='$username'";
    $result = $db->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $gigworker_id = $row['id'];

        $insert_sql = "INSERT INTO gigworker_availability (gigworker_id, day_of_week, available_from, available_to) VALUES ('$gigworker_id', '$day', '$from', '$to')";
        if ($db->query($insert_sql) === TRUE) {
            $_SESSION['notification_message'] = 'Availability added successfully.';
            $_SESSION['notification_type'] = 'success';
        } else {
            $_SESSION['notification_message'] = 'Error adding availability: ' . $db->error;
            $_SESSION['notification_type'] = 'error';
        }
    } else {
        $_SESSION['notification_message'] = 'Gig worker not found.';
        $_SESSION['notification_type'] = 'error';
    }

    header("Location: displayprofile.php");
    exit();
} else {
    header("Location: ../Public/index.php");
    exit();
}
?>
