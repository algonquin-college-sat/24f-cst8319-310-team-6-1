<?php
session_start();
require_once (__DIR__ . '/../Private/dbconnection.php');
require __DIR__ . '/../../vendor/autoload.php';
use Twilio\Rest\Client;

// Load the .env file
Dotenv\Dotenv::createImmutable(__DIR__ . '/../..')->load();

unset($_SESSION['username']);

// This class has two boolean variables indicating how the user's password might be incorrect
class PasswordValidation
{
    public $notMatch;
    public $tooShort;
    public function __construct()
    {
        $this->notMatch = false;
        $this->tooShort = false;
    }
}

// Check the user's password
function checkPassword($userPWD, $userPWD2)
{
    $passwordValidation = new PasswordValidation();

    if ($userPWD != $userPWD2) {
        $passwordValidation->notMatch = true;
    }
    if (strlen($userPWD) < 8) {
        $passwordValidation->tooShort = true;
    }
    return $passwordValidation;
}

$db = db_connect();
$email = isset($_GET['email']) ? $_GET['email'] : '';
$tooShort = false;
$notMatch = false;
$invalidEmail=false;

// Handle form values sent by index.php
if (isset($_POST['insert']) || isset($_POST['questionaire'])) {
    $type = $_POST['type'];
    $userEmail = $_POST['email'];
    $phone = $_POST['phone'];
    $userName = $_POST['company_name'];
    $userPWD = $_POST['pass'];
    $userPWD2 = $_POST['pass2'];

    // Check the user's passwords, store the result of validation
    $validation = checkPassword($userPWD, $userPWD2);
    $notMatch = $validation->notMatch;
    $tooShort = $validation->tooShort;

    if (!$notMatch && !$tooShort) {
        // Hash the password before inserting it into the database
        $hashedPassword = password_hash($userPWD, PASSWORD_DEFAULT);

        $table = "gigworkert";
        if ($type == "e") {
            $table = 'employert';
        }

        $sql1 = "INSERT INTO $table (phone) VALUES ('$phone')";
        $result = mysqli_query($db, $sql1); 
        if ($result) {
            $newEmail = bin2hex(random_bytes(16)) . $userEmail;
            $sql = "UPDATE account set useremail = '$newEmail' where userEmail = '$userEmail'";
            //mysqli_query($db, $sql);
            // Insertion successful
            $id = mysqli_insert_id($db);
            $validationCode = time().hash('sha256', $userName.$userEmail);
            $sql2 = "INSERT INTO account (userName, userEmail, userPWD, userType, validation, ".$table."Key) VALUES ('$userName', '$userEmail', '$hashedPassword', '$type', '$validationCode', '$id')";
            try{
                $result = mysqli_query($db, $sql2);
            }
            catch(Exception $e) {
                $result=false;
                $invalidEmail=true;
                echo"".$e->getMessage()."";
            }
            if ($result) {
                // Sending an email
                $subject = "Created account ".$userName;
                $msg = '<html><body>Hey!<br><a href="www.google.com">Google</a></body></html>';
                $receiver = "iuliiasmith@mail.com";
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                mail($receiver, $subject, $msg, $headers);
                
                // Sending a SMS message
                $sid    = getenv('TWILIO_SID');
                $token  = getenv('TWILIO_TOKEN');
                $twilio = new Client($sid, $token);
                $verificationToken = bin2hex(random_bytes(16));
                $hostname = getenv('HOSTNAME');

                // If HOSTNAME is not available, try COMPUTERNAME on Windows
                if (!$hostname) {
                    $hostname = getenv('COMPUTERNAME');
                }
                
                // Use the hostname to get the IP address
                $ipAddress = gethostbyname($hostname);
                
                // Display the IP address (IPv4)
                
                 // Example phone number
                $messageBody = "http://".$ipAddress."/GIGS/Private/verification.php?username=$username&token=$verificationToken";
                //$message = $twilio->messages->create($phone, [
                    //"from" => "+17064683484",
                    //"body" => $messageBody
               // ]);

                $_SESSION['username1'] = $userName;
                if (isset($_POST['questionaire'])) {
                    header("Location: ../Private/questionaire.php?id=questionaire");
                } else {
                    header("Location: index.php?id=$id");
                }
            } else {
                // Insertion failed, attempt to roll back
                $sql2 = "DELETE FROM $table WHERE id='$id'";
                mysqli_query($db, $sql2);
                //echo "Error: " . mysqli_error($db);
            }
        } else {
            // Insertion failed
           // echo "Error: " . mysqli_error($db);
        }
    }
}
$email = isset($_GET['email']) ? $_GET['email'] : '';
$companyName = isset($_GET['company_name']) ? $_GET['company_name'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/employer.css" />
    <link rel="stylesheet" href="../submission_gig.js" />
    <link rel="stylesheet" href="images/" />
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="icon/Picture4.ico" />
    <script src="../submission_gig.js" defer></script>
    <title>Worker Register - GIGS1</title>
</head>
<body>
    <div class="container">
        <section class="header">
            <h1>Create an Account</h1>
        </section>
        <hr>
        <form class="form form--hidden" id="createAccount" action="" onsubmit="return validate();" method="POST">
            <div class="form_content">
                <label for="type">Account Type</label><br>
                <select id="type" name="type" style="width:562px; border-radius:8px; padding-bottom: 14px; border: 2px solid #48BEC5;">
                    <option value="w">Gig Worker</option>
                    <option value="e">Employer</option>
                </select>
            </div>
            <div class="form_content">
                <label for="email">Email Address</label><br>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Type your email..." required />
                <span class="alert" id="emailError"><?php if($invalidEmail){ echo "The email invalid."; } ?></span>
            </div>
            <div class="form_content">
                <label for="phone">Phone</label>
                <input type="integer" id="phone" name="phone" placeholder="Type your phone..." />
                <!-- Placeholder for potential error message -->
            </div>
            <div class="form_content">
                <label for="login">User Name</label><br>
                <input type="text" name="company_name" id="company_name" value="<?php echo htmlspecialchars($companyName); ?>" placeholder="User name" />
                <span class="alert" id="loginError"></span>
            </div>
            <div class="form_content">
                <label for="pass">Password</label><br>
                <input type="password" name="pass" id="pass" placeholder="Password">
                <span class="alert" id="passError"><?php if ($tooShort) { echo "The password should be at least 8 characters long."; } ?></span>
            </div>
            <div class="form_content">
                <label for="pass2">Re-type Password</label><br>
                <input type="password" name="pass2" id="pass2" placeholder="Password">
                <span class="alert" id="pass2Error"><?php if ($notMatch) { echo "The passwords are not matching."; } ?></span>
            </div>
            
            <button type="submit" name="questionaire">Sign-Up</button>
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