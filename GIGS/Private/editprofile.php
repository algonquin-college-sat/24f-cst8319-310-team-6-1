<?php

#Cannot access the about page if you did not login
session_start();
if($_SESSION['username']) {
}
else {
    header ("Location:  ../Public/index.php");
}
?>
<?php 
//include './loggedin.php'; ?>
<?php
   require_once('./dbconnection.php');

   $db = db_connect();


   // Check if the form is submitted
   
   if (isset($_POST['insert'])) {
    // Retrieve form data
    
    $userType=$_POST['userType'];
    $table=$_POST['table'];
    $username=$_POST['userName'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $domain = $_POST['domain'];
    $phone = $_POST['phone'];
    $province = $_POST['province'];
    $url=$_POST['url'];
    $email=$_POST['email'];
    $sql = "UPDATE  account SET url='$url',userEmail='$email' 

        WHERE userName='$username'"; 
         $result = $db->query($sql);
   
    if ($userType=='w'){
        $skills = $_POST['skills'];
        $experience = $_POST['experience'];
        $availability = $_POST['availability'];
        $wage= $_POST['wage'];
        $workhistory=$_POST['workhistory'];
      // Insert the gig into the database
    //$sql = "INSERT INTO gigs (country, city, domain, company, duration, description, hourly_paid)
           // VALUES ('$country', '$city', '$domain', '$company', '$duration', '$description', '$hourlyPaid')";
           $sql = "SELECT id FROM gigworker WHERE userName='$username'";
           $result = $db->query($sql);
           $row = $result->fetch_assoc(); 
           $id =$row ['id'];
            $sql = "UPDATE  gigworkert SET phone='$phone', country='$country', city='$city', province='$province',  skills='$skills', experience='$experience', domain='$domain', availability='$availability',wage='$wage', workhistory='$workhistory'
        WHERE id='$id'";  
    }
    else{
        $description1=$_POST['description1'];
// Insert the gig into the database
    //$sql = "INSERT INTO gigs (country, city, domain, company, duration, description, hourly_paid)
           // VALUES ('$country', '$city', '$domain', '$company', '$duration', '$description', '$hourlyPaid')";
           $sql = "SELECT id FROM employer WHERE userName='$username'";
           $result = $db->query($sql);
           $row = $result->fetch_assoc(); 
           $id =$row ['id'];
           
            $sql = "UPDATE  employert SET phone='$phone', country='$country', city='$city', province='$province', domain='$domain', description1='$description1'
        WHERE id='$id'";
    }

    
        
    $result = $db->query($sql);

if ($result) {
    header('Location: displayprofile.php');
    exit();
} else {
    echo "Error adding the profile: " . $db->error;
}
}

    
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="icon/Picture4.ico"/>
    <title>Edit Profile</title>
    <style>
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
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            padding: 5px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .country-dropdown{
            height: 32px;
        }

        .description{
            height: 100px;
        }
    </style>
</head>
<body>
    <?php include 'navBar.php'; ?>
    
    <h1><?php $username= $_SESSION['username']; 
    echo $username;?></h1>
    
    
    <?php
            require_once('./dbconnection.php');

            $db = db_connect();          
               
                // Fetch the account with the same username as the logged in user and check the type of that account*
            $sql = "SELECT * FROM account where userName='$username'";
            $result = $db->query($sql);
            $row=1;
            $profile="";
            $document="";
            $document2="";
            if ($result -> num_rows>0){
                $row2 = $result->fetch_assoc();
                $type=$row2['userType'];
                //echo $type."<br>";
                $table='gigworker';
                if ($type=='e'){
                 $table='employer'; 
                }
                // query different tables depending on the account type
                $sql = "SELECT * FROM $table where userName='$username'"; 
                $result = $db->query($sql);
                // Display the user profile*
                //Uses the result from sql to display employer information
                if ($result->num_rows > 0) {
                    //echo '<br><div class="gig-container">';
                    //$row=$result->fetch_assoc();
                   $row = $result->fetch_assoc(); 
                        //echo '<li class="gig">';
                        //echo '<h3>' . $row['userName'] . '</h3>';
                        //Add city and comma if city is  ot empty
                        $profile=$row['profile'];
                        $document=$row['document'];
                        $document2=$row['document2'];

                        $city=$row['city'];
                        if ($city!= ""){
                            $city.=", ";
                        }
                        //Add province and comma if province not empty
                        $province=$row['province'];
                        if ($province!= ""){
                            $province.=", ";
                        }
                        //echo '<p>Location: ' . $city . $province. $row['country'] . '</p>';
                        //echo '<p>Phone: ' . $row['phone'] . '</p>';
                        //echo '<p>Email: ' . $row['userEmail'] . '</p>';
                        //echo '<p>Domain: ' . $row['domain'] . '</p>';
                        // To print different fields if the account is an employer account
                        if ($type=='e'){
                            //echo '<p>Description: ' . $row['description1'] . '</p>';
                        }
                        
                        //echo '</li>';
                    

                     //echo '</div>';
                } else {
                    //echo "No user found.";
                }
            }
                else {
                //echo "No user found.";
            }
            

        
           
            
        ?>

    <div class="container">
        <h1>Edit Profile</h1>
        <?php 
        if($profile!=''){
        echo '<img height="350" src="uploads/'.$profile.'"><br>';
        }    
        ?> 
         <?php 
                $country= "'".$row['country']."'";
                if($country=="''"){
                    $country="'Canada'";
                }
                ?>
<form action="upload.php" method="post" enctype="multipart/form-data">
  Select image to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload Image" name="submit">
            </form> <br>
  <!--<label for="resume">Upload Resume (PDF, DOC, DOCX):</label>    
<input type="file" name="fileToUpload" id="resume" accept=".pdf,.doc,.docx">-->

<form action="uploadresume.php" method="post" enctype="multipart/form-data">
    <?php
    if($document!=''){
       echo '<a  href="uploads/'. $document.'"> Download Resume</a>' ;
    }
    ?>
Select resume to upload:
        <input type="file" name="fileToUpload2" id="fileToUpload2">
        <input type="submit" value="Upload Resume" name="submit">
</form>
<form action="uploadcertificate.php" method="post" enctype="multipart/form-data">
<?php
if ($document2!=''){
    echo '<a  href="uploads/'. $document2.'"> Download Certificate </a>';
    }
    ?>
Select certificate to upload:
        <input type="file" name="fileToUpload3" id="fileToUpload3">
        <input type="submit" value="Upload Certificate" name="submit">
</form>
        <form action="" method="POST">
            <label for="email">email:</label>
            <input type="text" id="email" name="email"  value="<?php echo $row["userEmail"]; ?>" required>

            <label for="phone">phone number:</label>
            <input type="text" id="phone" name="phone"  value="<?php echo $row["phone"]; ?>" required>
            <label for="city">City:</label>
            <input type="text" id="city" name="city" value="<?php echo $row["city"]; ?>"required>
            <label for="province">Province:</label>
            <input type="text" id="province" name="province"value="<?php echo $row["province"]; ?>" required>
            <label for="country">Country:</label>
            <select id="country-dropdown" class="country-dropdown" name="country"></select>
  
            <script>
            // Fetch the country data from the API
            fetch('https://restcountries.com/v3.1/all')
                .then(response => response.json())
                .then(data => {
                const countryDropdownElement = document.getElementById('country-dropdown');
        
                // Sort the country names alphabetically
                const sortedCountries = data
                    .map(country => country.name.common)
                    .sort();
        
                // Move Canada to the front
               <?php // $country="'Canada'";?>
                
                const canadaIndex = sortedCountries.indexOf(<?php   echo $country;?>);
                //const canadaIndex = sortedCountries.indexOf('Canada');
                if (canadaIndex !== -1) {
                    sortedCountries.splice(canadaIndex, 1);
                    sortedCountries.unshift(<?php echo $country;?>);
                }
        
                sortedCountries.forEach(countryName => {
                    const optionElement = document.createElement('option');
                    optionElement.textContent = countryName;
                    optionElement.value = countryName;
                    countryDropdownElement.appendChild(optionElement);
                });
                })
                .catch(error => console.error('Error:', error));
            </script><br>

            
            <label for="url">URL:</label>
            <input type="text" id="url" name="url" value="<?php echo $row["url"]; ?>"required>

            <label for="domain">Domain:</label>
            
            <?php
$domains = array(
    ""=>array(""),
    "Transportation and delivery services" => array(
        "package" => "Package delivery driver",
        "food" => "Food delivery driver",
        "grocery" => "Grocery delivery driver",
        "bicycle" => "Bicycle courier",
        "ride" => "Ride-share driver"
    ),
    "Personal services" => array(
        "dog" => "Dog walker",
        "Babysitter" => "Babysitter or nanny",
        "home" => "Home health aide",
        "tutor" => "Tutor",
        "massage" => "Massage therapist",
        "telehealth" => "Telehealth provider"
    ),
    "On-demand skilled work" => array(
        "photographer" => "Photographer",
        "graphic" => "Graphic designer",
        "content" => "Content writer or copywriter",
        "web" => "Web developer",
        "editor" => "Editor",
        "consultant" => "Consultant",
        "translator" => "Translator"
    ),
    "Home services" => array(
        "handyperson" => "Handyperson",
        "mover" => "Mover",
        "house" => "House sitter",
        "housekeeper" => "Housekeeper",
        "cook" => "Cook",
        "lawn" => "Lawn care technician or landscaper"
    ),
    "Internet-based gigs" => array(
        "survey" => "Survey taker",
        "transcriptionist" => "Transcriptionist",
        "virtual" => "Virtual assistant",
        "proofreader" => "Proofreader",
        "customer" => "Customer service representative",
        "data" => "Data entry clerk"
    ),
    
    "Rental services" => array(
        "survey" => "Survey taker",
        "transcriptionist" => "Transcriptionist",
        "virtual" => "Virtual assistant",
        "proofreader" => "Proofreader",
        "customer" => "Customer service representative",
        "data" => "Data entry clerk"
    )
);
?>
<select name="domain" id="domain" class="country-dropdown">
<?php
// Loop through the domains
foreach ($domains as $category => $options) {
    echo "<optgroup label=\"$category\">";
    // Loop through the options within each category

    foreach ($options as $value => $label) {
        $selected='';
        if ($value== $row['domain']){
            $selected='selected';
        }
        echo "<option value=\"$value\" $selected>$label</option>";

    }
    echo "</optgroup>";
}
?>

</select>
            

            
          <?php 
           if ($type=='w'){?>
            <label for="skills">Skills:</label>
            <input type="text" id="skills" name="skills" value="<?php echo $row["skills"]; ?>" required>
            <label for="experience">Experience:</label>
            <input type="text" id="experience" name="experience" value="<?php echo $row["experience"]; ?>" required>
            <label for="availability">Availability:</label>
            <input type="text" id="availability" name="availability" value="<?php echo $row["availability"]; ?>" required>
            <label for="wage">Wage:</label>
            <input type="number" id="wage" name="wage" value="<?php echo $row["wage"]; ?>" required>
            <label for="workhistory">Work History:</label>
            <textarea id="workhistory" name="workhistory" rows="12" cols="70"><?php
             echo $row ['workhistory']; 
             ?></textarea>
          <!--  <form action="upload.php" method="post" enctype="multipart/form-data">
        Select resume to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Resume" name="submit">
        </form>-->
            
            <?php }else {
                ?> <label for="description1">Description:</label>
                <textarea id="description1" name="description1" class="description" required><?php echo $row["description1"]; ?></textarea>
                <?php }?>
            
            <input type="hidden" id="userName" name="userName" value="<?php echo $username; ?>">
            <input type="hidden" id="userType" name="userType" value="<?php echo $row['userType']; ?>">
            <input type="hidden" id="table" name="table" value="<?php echo $table; ?>">
           

            <!--<label for="duration">Duration:</label>
            <input type="text" id="duration" name="duration" required>

            

            <label for="hourly">Hourly Paid:</label>
            <input type="text" id="hourly" name="hourly" required>-->
            <br>
            <input type="submit" value="Update Profile" name="insert" style="font-size: 20px; ">
        </form>
    </div>
    

    <?php include 'footer.php'; ?>
</body>
</html>
