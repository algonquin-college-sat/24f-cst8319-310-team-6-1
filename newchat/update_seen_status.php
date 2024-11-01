<?php
session_start();
require_once('./dbconnection.php');

if (isset($_POST['from']) && isset($_POST['toUser'])) {
    $from = $_POST['from'];
    $toUser = $_POST['toUser'];

    $stmt = $conn->prepare("UPDATE newchat SET seen = 1 WHERE `from` = ? AND `toUser` = ?");
    $stmt->bind_param("ss", $from, $toUser);
    $stmt->execute();
    $stmt->close();
}
?>