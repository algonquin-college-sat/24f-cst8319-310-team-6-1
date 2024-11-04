<?php
require_once('../Private/dbconnection.php');
require __DIR__ . '/../../autoload.php';;
use Twilio\Rest\Client;
$db = db_connect();
$email = isset($_GET['email']) ? $_GET['email'] : '';


// Handle form values sent by index.php
if (isset($_POST['insert'])) { //make sure we submit the data
    $userName = $_POST['company_name']; // access the form data
    $userEmail = $_POST['email'];
    $userPWD = $_POST['pass'];

    // Hash the password before inserting it into the database
    $hashedPassword = password_hash($userPWD, PASSWORD_DEFAULT);

    $phone = $_POST['phone'];
    $country= $_POST['country'];
    $city= $_POST['city'];
    $province = $_POST['province'];
    $skills = $_POST['skills'];
    $experience = $_POST['experience'];
    $domain = $_POST['domain'];
    $availability = $_POST['availability'];     

  
    $sql1 = "INSERT INTO gigworker (userName, userEmail, userPWD, phone, country, city, province, skills, experience, domain, availability) 
    VALUES ('$userName', '$userEmail', '$hashedPassword', '$phone', '$country', '$city', '$province', '$skills','$experience', '$domain', '$availability')";



    $result = mysqli_query($db, $sql1);

    if ($result) {
        // Insertion successful
        $id = mysqli_insert_id($db);
        
        $server_ip = $_SERVER['SERVER_ADDR'];
        $sid    = $_ENV["TWILIO_SID"];
        $token  = $_ENV["TWILIO_TOKEN"];
        $twilio = new Client($sid, $token);

        $messageBody = "Thank you for signing up! Your registration is successful. Click here to access the website: http://192.168.2.123/24wcst8319projectFinal/GIGS/Public/index.php. You are now signed in.";

        $message = $twilio->messages
            ->create($phone, // to
                array(
                    "from" => "+14243321496",
                    "body" => $messageBody
                )
            );

        print($message->sid);
        header("Location: index.php?id=$id");
    } else {
        // Insertion failed
        echo "Error: " . mysqli_error($db);
    }
  
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/employer.css"/>
    <link rel="stylesheet" href="../submission_gig.js"/>
    <link rel="stylesheet" href="images/"/>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="icon/Picture4.ico"/>
    <script src="../submission_gig.js" defer></script>
    <title>Worker Register - GIGS</title>

</head>


<body>
    
    <div class="container">
        <section class="header">
            <h1>Do your Gig Worker registration!</h1>
        </section>
            <hr>
            <form class="form form--hidden" id="createAccount" action = ""  onsubmit="return validate();"  method="POST">
           
                <div class="form_content">
                    <label for="email">Email Address</label><br>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Type your email..." required/>
                    <span class="alert" id="emailError"></span>
                </div>

                <div class="form_content">
                    <label for="login">User Name</label><br>
                    <input type="text" name="company_name" id="company_name" placeholder="User name" value="Ethan Yeganeh">
                    <span class="alert" id="loginError"></span>
                </div>

                <div class="form_content">
                    <label for="phone">Phone</label>
                    <input type="integer" id="phone" name="phone" placeholder="Type your phone..."/>
                    <a>...Message error here...</a>
                </div>

                <div class="form_content">
                    <label for="country">Country</label>
                    <select id="country-dropdown" name="country" class="country-dropdown"></select>
  
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
                        const canadaIndex = sortedCountries.indexOf('Canada');
                        if (canadaIndex !== -1) {
                            sortedCountries.splice(canadaIndex, 1);
                            sortedCountries.unshift('Canada');
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
                </div>

                <div class="form_content">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" placeholder="Type your city..."/>
                    <a>...Message error here...</a>
                </div>

                <div class="form_content">
                    <label for="province">Province</label>
                    <input type="text" id="province" name="province" placeholder="Type your province..."/>
                    <a>...Message error here...</a>
                </div>
                <div class="form_content">
                    <label for="skills">Skills</label>
                    <input type="text" id="skills" name="skills" placeholder="Type your skills..."/>
                    <a>...Message error here...</a>
                </div>
                <div class="form_content">
                    <label for="experience">Experience</label>
                    <input type="text" id="experience" name="experience" placeholder="Type your Experiences..."/>
                    <a>...Message error here...</a>
                </div>
                <div class="form_content">
                    <label for="domain">Domain</label><br>
                    <select id="domain" name="domain" style="width:562px; border-radius:8px; padding-bottom: 14px; border: 2px solid #48BEC5;">
                        <option value="" ></option>
                        <option value="package">Transportation and delivery services</option>
                        <option value="construction">Construction</option>
                        <option value="grocery">Restaurant</option>
                        <option value="bicycle">Rental Services</option>
                        <option value="consultant">Consultant</option>
                        <option value="bartender">Bartender</option>                    
                        <option value="other" >Other</option>
                    </select>
                </div>

                <div class="form_content">
                    <label for="availability">Availability</label>
                    <select id="availability" name="availability" style="width:562px; border-radius:8px; padding-bottom: 14px; border: 2px solid #48BEC5;">
                        <option value="" ></option>                    
                        <option value="all" >All Schedules</option>
                        <option value="morning">Morning</option>
                        <option value="evening">Evening</option>
                        <option value="night">Night</option>
                        <option value="overnight">Overnight</option>
                        <option value="morning_evening" >Morning and Evening</option>
                        <option value="morning_night" >Morning and Night</option>
                        <option value="morning_overnight" >Morning and Overnight</option>
                        <option value="evening_night" >Evening and Night</option>
                        <option value="evening_overnight" >Evening and Overnight</option>
                        <option value="night_overnight" >Night and Overnight</option>
                        <option value="morning_evening_night" >Morning, Evening and Night</option>
                        <option value="morning_evening_overnight" >Morning, Evening and Overnight</option>
                        <option value="evening_night_overnight" >Evening, Night and Overnight</option>
                        <option value="night_overnight_morning" >Night, Overnight and Morning</option>
                    </select>
                </div>

                <div class="form_content">
                    <label for="pass">Password</label><br>
                    <input type="password" name="pass" id="pass" placeholder="Password">
                    <span class="alert" id="passError"></span>
                </div>
            
                <div class="form_content">
                    <label for="pass2">Re-type Password</label><br>
                    <input type="password" name="pass2" id="pass2" placeholder="Password">
                    <span class="alert" id="pass2Error"></span>
                </div>

                <button type="submit" name = "insert" >Sign-Up</button>
                <button type="reset" id="clean" onclick="resetProfile();">Reset</button><br>                
                
                
                
                <div class="text_create">                                        
                    <br>Already have an account?
                </div>
                <div class="text_create">       
                    <a href="index.php"> Login</a>
                </div>
                
            </form>
            
    </div>

</body>
</html>
