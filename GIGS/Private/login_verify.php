<?php
require_once('./dbconnection.php');

session_start();
$db = db_connect();

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt1 = mysqli_prepare($db, "SELECT validation FROM account WHERE userName = ? OR userName = ?");
    mysqli_stmt_bind_param($stmt1, "ss", $username, $username);
    mysqli_stmt_execute($stmt1);
    mysqli_stmt_bind_result($stmt1, $validated);
    mysqli_stmt_fetch($stmt1);
    mysqli_stmt_close($stmt1);
   if($validated!=1) {
        $_SESSION['username1'] = $username;
        echo ("<script>
        
        window.location.href='../Private/validateprofile5.php';</script>");
        
        exit();
    }


    // Prepare the query to select the encrypted password from the database based on the username
    $stmt = mysqli_prepare($db, "SELECT userPWD FROM employer WHERE userName = ? OR userName = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $hashedPassword);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (empty($hashedPassword)) {
    $stmt1 = mysqli_prepare($db, "SELECT userPWD FROM gigworker WHERE userName = ? OR userName = ?");
    mysqli_stmt_bind_param($stmt1, "ss", $username, $username);
    mysqli_stmt_execute($stmt1);
    mysqli_stmt_bind_result($stmt1, $hashedPassword);
    mysqli_stmt_fetch($stmt1);
    mysqli_stmt_close($stmt1);
    }
   

    // Verify the user password using password_verify()
    if (password_verify($password, $hashedPassword)) {
        $_SESSION['username'] = $username;
        header("Location: ../Public/firstpage.php");
        exit();
    } else {
        $_SESSION['username'] = $username;
        echo ("<script> window.alert('Invalid username or password')
        window.location.href='../Public/index.php';</script>");
        session_destroy();
        exit();
    }
}

// Google login flow
if (isset($_GET['email']) && isset($_GET['name'])) {
    $email = $_GET['email'];
    $name = $_GET['name'];

    // Check if the email exists in the account table
    $stmt = mysqli_prepare($db, "SELECT userName FROM account WHERE userEmail = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $username);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($username) {
        // Email exists, log the user in
        $_SESSION['username'] = $username;
        header("Location: ../Public/firstpage.php");
        exit();
    } else {
        // Email does not exist, redirect to registration
        header("Location: ../Public/reg_account.php?email=" . urlencode($email) . "&company_name=" . urlencode($name));
        exit();
    }
}
?>
