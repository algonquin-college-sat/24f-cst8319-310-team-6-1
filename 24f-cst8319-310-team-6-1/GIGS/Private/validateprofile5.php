
<?php

#Cannot access the about page if you did not login
session_start();
if($_SESSION['username1']) {
}
else {
    header ("Location:  ../Public/index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="icon" href="icon/Picture4.ico"/>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <title>Display</title>
    <style>
        /*
        PROJECT COLORS:
        #084D6A - DARK BLUE
        #48BEC5 - LIGHT BLUE
        #F0F1B7 - BEIGE
        #97D779 - GREEN
        */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
        }

        h1 {
            text-align: center;
            color: #084D6A;
        }

        .gig {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            list-style-type: none;
        }

        .gig:nth-child(even) {
            background-color: #f1f1f1;
        }

        .gig h3 {
            margin: 0;
            font-size: 18px;
        }

        .gig p {
            margin: 5px 0;
        }

        #addgig, #review, #delete, #filter, #contact_form, #editprofile {
            border-radius: 5px;
            padding: 3px 12px;
            font-weight: 800;
            font-size: 18px;
            border: none;
            background-color: #084D6A;
            color: #97D779;
            cursor: pointer;
        }

        .filter-input {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 200px; 
        }
      
    </style>
</head>
<body>
    <?php include './navBar.php'; ?>
    
    <h1><?php $username= $_SESSION['username1']; 
    echo $username;?></h1>
    <div class="container">
        <?php
            require_once('./dbconnection.php');
            $db = db_connect();          
   if (isset($_POST['validation'])) {
    $validation=$_POST['validation'];
    $sql = "UPDATE  account SET validation='1' 
        WHERE validation='$validation' and userName='$username'"; 
         $result = $db->query($sql);
   }
            $sql = "SELECT * FROM account where userName='$username'";
            echo $username;
            $result = $db->query($sql);
            if ($result -> num_rows>0){
                $row = $result->fetch_assoc(); 
                $validation=$row['validation'];
                if ($validation==1) {
                    $_SESSION['username'] = $username;
                  echo '<script>window.location.href = "../Private/displayprofile.php"</script>';
                 
                } 
                else{

                            
                    $userName=$row['userName'];
                    $code=$validation;
                    $subject = "Created account ".$userName;
                    $msg = "Created Account $userName\nHello $code";
                    $receiver = $row['userEmail'];
                    mail($receiver, $subject, $msg);
                   /* echo $msg;
                        echo '<li class="gig">';
                        echo '<h3>' . $row['userName'] . '</h3>';
                        echo '<h3>validation ' . $validation. '</h3>';  */ 
                    }                    
                } else {
                    echo "No user found.";
                }           
        ?> 
        <form action="" method="POST">
        <label for="experience">Validation Code:</label>
            <input type="text" id="validation" name="validation" required>
            <br>
            <input type="submit" value="Validate Email" name="insert" style="font-size: 20px; ">
        </form>             
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
