<?php
require_once('./dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gigId = $_POST['id'];

    $db = db_connect();

    $sql = "DELETE FROM gigworker WHERE id = '$gigId'";
    $result = $db->query($sql);

    if ($result) {
        echo "Gig deleted successfully";
    } else {
        echo "Error deleting the gig: " . $db->error;
    }

    db_disconnect($db);
}
?>
