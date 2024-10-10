
<?php

#Cannot access the about page if you did not login
session_start();
if($_SESSION['username']) {
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
   
    <h1><?php $username= $_SESSION['username']; 
    if (isset($_GET['userName'])) {
        // Retrieve form data
        $name=$_GET['userName'];
        $username=$name;
       }        
    echo $username;?></h1>

    <div class="container">

        <?php
            require_once('./dbconnection.php');

            $db = db_connect();  
            
               
                // Fetch the account with the same username as the logged in user and check the type of that account*
            
            $sql = "SELECT * FROM `newchat` WHERE `from` = '$username' or `toUser` = '$username'";
            $result = $db->query($sql);
            

            
                        
               
           
            
        
    echo '<h2>Chat</h2>';
    echo '<table>';
    
        
            while ($row = $result->fetch_assoc()) {
                $from = $row['from'];
                if ($from != $username) {
                    $from = '<a href="../../newchat/indexchat.php?userName='.$from.'" target="_blank">'.$from.'</a>';
                }
                $toUser = $row['toUser'];
                if ($toUser != $username) {
                    $toUser = '<a href="../../newchat/indexchat.php?userName='.$toUser.'" target="_blank">'.$toUser.'</a>';
                }
                echo "<tr> ";
                echo '<td>' . $from ."</td><td>" . $toUser . "</td><td>".$row['message'] . '</td>';
                echo "</tr>";
                
            }
        
    
    echo '</table>';
   
    echo '</div>';
 ?>     
    </div>
    

    <?php include 'footer.php'; ?>

</body>
</html>
