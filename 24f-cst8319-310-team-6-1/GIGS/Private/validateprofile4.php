<?php
session_start();
if ($_SESSION['username']) {
} else {
    header("Location:  ../Public/index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="icon/Picture4.ico" />
    <title>Display</title>
</head>
<body>
    <h1>
        <?php $username = $_SESSION['username'];
        echo $username; ?>
    </h1>
    <div class="container">
        <?php
        require_once ('./dbconnection.php');
        $db = db_connect();
        $sql = "SELECT * FROM account where userName='$username'";
        $result = $db->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<h3>validation ' . $row['userName'] . '</h3>';     
                echo '<h3> Your Validation Code is ' . $row['validation'] . '</h3>';
            }
        } else {
            echo "No user found.";
        }
        ?>
    </div>
</body>
</html>