<?php

include('./dbconnection.php');
$db = db_connect();

$result = array();


$message = isset($_POST['message']) ? $_POST['message'] : null;
$from = isset($_POST['from']) ? $_POST['from'] : null;
$toUser = isset($_POST['toUser']) ? $_POST['toUser'] : null;

if(!empty($message) && !empty($from)) {
    $sql = "INSERT INTO `newchat` (`message`, `from`, `toUser`) VALUES ('" . $message . "', '" . $from . "', '" . $toUser . "')";
    $result['send_status'] = $db->query($sql);
}

//To print messages on screen
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$from2 = isset($_GET['from2']) ? $_GET['from2'] : null; 
$toUser2 = isset($_GET['toUser2']) ? $_GET['toUser2'] : null; 
$sql = "SELECT * FROM `newchat` WHERE id > $start and ((`from` = '$from2' AND `toUser` = '$toUser2') OR (`from` = '$toUser2' AND `toUser` = '$from2'))"; 
$items = $db->query($sql);
while($row = $items->fetch_assoc()) {
    $result['items'][] = $row;
}


$db->close();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

echo json_encode($result);

?>
