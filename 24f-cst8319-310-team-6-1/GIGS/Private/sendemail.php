<?php
$userName='Hello';
$subject = "Created account ".$userName;
$msg = '<html><body>Hey!<br><a href="www.google.com">Google</a></body></html>';
$receiver = "iuliiasmith@mail.com";
$headers['MIME-Version'] = 'MIME-Version: 1.0';
$headers['Content-type'] = 'text/html; charset=iso-8859-1';
mail($receiver, $subject, $msg, $headers);
?>