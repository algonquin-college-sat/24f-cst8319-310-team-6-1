<?php
//Connect to Database using credentials provided
require_once('credentials.php');

function db_connect()
{
$db1 = mysqli_connect(serverName, dBUserName, dBPassword, dBName);
if (mysqli_connect_errno()) {
  $ech = "Database connection failed: ";
  $msg .= mysqli_connect_error();
  $msg .= " (" . mysqli_connect_errno() . ")";
  exit($msg);
}

return $db1;
}

function db_disconnect($db1)
{
if (isset($db1)) {
  mysqli_close($db1);
}
}