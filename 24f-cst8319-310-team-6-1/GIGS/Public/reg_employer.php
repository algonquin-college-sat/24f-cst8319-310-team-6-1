
<?php
require_once('../Private/dbconnection.php');
require __DIR__ . '/../../vendor/autoload.php';
use Twilio\Rest\Client;
$db = db_connect();
$email = isset($_GET['email']) ? $_GET['email'] : '';

// Load the .env file
Dotenv\Dotenv::createImmutable(__DIR__ . '/../..')->load();

// Handle form values sent by index.php
if (isset($_POST['insert'])) {
    $userName = $_POST['company_name'];
    $userEmail = $_POST['email'];
    $userPWD = $_POST['password']; 

    // Hash the password before inserting it into the database
    $hashedPassword = password_hash($userPWD, PASSWORD_DEFAULT);

    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $province = $_POST['province'];

    $sql = "INSERT INTO employer (userName, userEmail, userPWD, phone, country, city, province) 
            VALUES ('$userName', '$userEmail', '$hashedPassword', '$phone', '$country', '$city', '$province')";
    
    $result = mysqli_query($db, $sql);

    if ($result) {
        // Insertion successful
        $id = mysqli_insert_id($db);
        $server_ip = $_SERVER['SERVER_ADDR'];
        $sid    = getenv('TWILIO_SID');
        $token  = getenv('TWILIO_TOKEN');        
        $twilio = new Client($sid, $token);

        $messageBody = "Thank you for signing up! Your registration is successful. Click here to access the website: http://192.168.2.123/24w-cst8319-300-team5-working/GIGS/Public/index.php. You are now signed in.";

        $message = $twilio->messages
            ->create($phone, // to
                array(
                    "from" => "+17064683484",
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
    <link rel="stylesheet" href="../assets/style_index.css"/>
    <link rel="stylesheet" href="images/"/>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="icon/Picture4.ico"/>
    <script src="../submission_form.js" defer></script>
    <title>Employer Register - GIGS</title>

</head>


<body>



	<div class="container">
	    <section class="header">
	        <h1>New Employer</h1>
	    </section>
        
        <form action = "" class="form form--hidden" id="createAccount" onsubmit="return validate();" method="POST" >
            <div class="form_content">
                <label for="company_name">Company / Username</label>
                <input type="text" id="company_name" name="company_name" placeholder="Type Company Name..." required/>
                <a>...Message error here...</a>
            </div>

            <div class="form_content">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Type your email..." required/>
                <a>...Message error here...</a>
            </div>

            <div class="form_content">
                <label for="phone">Phone</label>
                <input type="integer" id="phone" name="phone" placeholder="Type your phone..." required/>
                <a>...Message error here...</a>
            </div>

            <div class="form_content">
                    <label for="country">Country</label>
                    <select id="country-dropdown" name ="country" class="country-dropdown"></select>
  
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
                <input type="text" id="city" name="city" placeholder="Type your city..." required/>
                <a>...Message error here...</a>
            </div>

            <div class="form_content">
                <label for="province">Province</label>
                <input type="text" id="province" name="province" placeholder="Type your province..." required/>
                <a>...Message error here...</a>
            </div>

            <div class="form_content">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Type your password..." required/>
                <a>...Message error here...</a>
            </div>

            <div class="form_content">
                <label for="password_confirmation">Re-type Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Re-type your password..." required/>
                <a>...Message error here...</a>
            </div>            
            
            <button type="submit" class="blue_button" name = "insert" >Sign-Up</button>
            <button type="reset" id="clean" class="red_button" onclick="resetProfile();">Reset</button>         
            
                        
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


