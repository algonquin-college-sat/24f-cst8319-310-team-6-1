
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
    echo $username;?></h1>

    <div class="container">

        <?php
            require_once('./dbconnection.php');

            $db = db_connect();          
               
                // Fetch the account with the same username as the logged in user and check the type of that account*
            $sql = "SELECT * FROM account where userName='$username'";
            $result = $db->query($sql);
            if ($result -> num_rows>0){
                $row = $result->fetch_assoc();
                $type=$row['userType'];
                $table='gigworker';
                $profile = $row['profile'];
                $document = $row['document'];
                $document2 = $row['document2'];

                //echo '***'.$profile;
                if ($type=='e'){
                 $table='employer'; 
                }
                // query different tables depending on the account type
                $sql = "SELECT * FROM $table where userName='$username'"; 
                $result = $db->query($sql);
                // Display the user profile*
                //Uses the result from sql to display employer information
                if ($result->num_rows > 0) {
                    echo '<br><div class="gig-container">';
                    while ($row = $result->fetch_assoc()) {
                        echo '<li class="gig">';
                        echo '<h3>validation ' . $row['userName']. '</h3>';
                        //echo '<h3><a href="validateprofile3.php?validation=' . $row['validation'] .'">'.'validate</a>'. '</h3>';
                        echo '<h3> Your Validation Code is ' . $row['validation']. '</h3>';
                        
                        if($profile!=""){
                            echo '<img height="350" src="uploads/'.$profile.'"><br>';
                        }
                        if($document!=""){
                            echo '<a href="uploads/'.$document.'">Download Resume</a><br>';
                        }
                        if($document2!=""){
                            echo '<a href="uploads/'.$document2.'">Download Certificate</a><br>';
                        }
                        

                        //Add city and comma if city is  ot empty
                        $city=$row['city'];
                        if ($city!= ""){
                            $city.=", ";
                        }
                        //Add province and comma if province not empty
                        $province=$row['province'];
                        if ($province!= ""){
                            $province.=", ";
                        }
                        echo '<p>Location: ' . $city . $province. $row['country'] . '</p>';
                        echo '<p>Phone: ' . $row['phone'] . '</p>';
                        echo '<p>Email: ' . $row['userEmail'] . '</p>';
                        $_SESSION['userEmail']=$row['userEmail'];
                        $_SESSION['phone']=$row['phone'];

                        echo '<p>Domain: ' . $row['domain'] . '</p>';
                        // To print different fields if the account is an employer account
                        if ($type=='e'){
                            echo '<p>Description: ' . $row['description1'] . '</p>';
                        }
                        else{
                            echo '<p>Skills: ' . $row['skills'] . '</p>';
                            echo '<p>Experience: ' . $row['experience'] . '</p>';
                            echo '<p>Availability: ' . $row['availability'] . '</p>'; 
                            echo '<p>Wages: '. $row['wage'] . '</p>';
                            echo '<p>Work History: '. nl2br($row['workhistory']) . '</p>';

                        }
                        echo '<br><a href="editprofile.php"><button id="editprofile">Edit Profile</button></a> ';
                        echo '</li>';
                    }
                     echo '</div>';
                } else {
                    echo "No user found.";
                }
            }
                else {
                echo "No user found.";
            }
            

        
           
            
        ?>
        <?php
      $sql = "SELECT * FROM gigworker WHERE userName='$username'";
$result = $db->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    echo '<div class="container">';
    echo '<h2>Availability</h2>';
    echo '<table>';
    foreach (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day) {
        echo '<tr><td>' . $day . '</td>';
        $day_column = strtolower($day);
        //$sql_availability = "SELECT * FROM gigworker_availability WHERE gigworker_id={$row['id']} AND day_of_week='$day'";
        $sql_availability = "SELECT id, TIME_FORMAT(available_from, '%H:%i') AS time1, TIME_FORMAT(available_to, '%H:%i') AS time2 FROM gigworker_availability WHERE gigworker_id={$row['id']} AND day_of_week='$day'";

        $result_availability = $db->query($sql_availability);
        if ($result_availability && $result_availability->num_rows > 0) {
            while ($row_availability = $result_availability->fetch_assoc()) {
                echo '<td>' . $row_availability['time1'] . ' to ' . $row_availability['time2'] . '</td>';
                // Add remove button
                echo '<td><form action="remove_availability.php" method="post">';
                echo '<input type="hidden" name="availability_id" value="' . $row_availability['id'] . '">';
                echo '<input type="submit" value="Remove">';
                echo '</form></td>';
            }
        } else {
            echo '<td>No availability</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    echo '<a href="editcalendarhtml.php">Add Availability</a>';
    echo '</div>';
} else {
    echo 'No gig worker found.';
}?>     
    </div>
    

    <?php include 'footer.php'; ?>

</body>
</html>
