<?php
$userName='Name';
$code='Code';
$subject = "Created account ".$userName;
$msg = "Created Account $userName\nActivation Code is $code";
$receiver = "iuliiasmith@mail.com";
mail($receiver, $subject, $msg);
echo $msg;
?>