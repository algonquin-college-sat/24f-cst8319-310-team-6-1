<?php
//Connect to Database using credentials provided
require('credentials.php');
$db1 = null;

function db_connect()
{
  global $db1;
  if ($db1 != null) {
    return $db1;
  }
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