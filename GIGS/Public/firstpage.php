<?php
session_start();
require_once(__DIR__ . '/../Private/dbconnection.php');
require __DIR__ . '/../../autoload.php';;
use Twilio\Rest\Client;
$username = $_SESSION['username'];
$db = db_connect();



    $username = $_SESSION['username'];
    
    // Fetch user information and user type from the account table
    $accountSql = "SELECT * FROM account WHERE userName = ?";
    $accountStmt = $db->prepare($accountSql);
    $accountStmt->bind_param("s", $username);
    $accountStmt->execute();
    $accountResult = $accountStmt->get_result();

    if ($accountResult->num_rows > 0) {
        $accountRow = $accountResult->fetch_assoc();
        $userEmail=$accountRow["userEmail"];
        if (isset($_POST['verify']) && isset($_SESSION['username'])) {
        $userType = $accountRow['userType'];
        $employertKey = $accountRow['employertKey'];
        $gigworkertKey = $accountRow['gigworkertKey'];

        if ($userType === 'e') {
            // User is an employer, fetch phone number from employert table
            $phoneSql = "SELECT phone FROM employert WHERE id = ?";
            $phoneStmt = $db->prepare($phoneSql);
            $phoneStmt->bind_param("i", $employertKey);
        } elseif ($userType === 'w') {
            // User is a gig worker, fetch phone number from gigworkert table
            $phoneSql = "SELECT phone FROM gigworkert WHERE id = ?";
            $phoneStmt = $db->prepare($phoneSql);
            $phoneStmt->bind_param("i", $gigworkertKey);
        }

        // Execute the phone number query
        $phoneStmt->execute();
        $phoneResult = $phoneStmt->get_result();

        if ($phoneResult->num_rows > 0) {
            $phoneRow = $phoneResult->fetch_assoc();
            $phone = $phoneRow['phone'];
            $verifiedSql = "SELECT verified FROM user_verification WHERE userName = ?";
            $verifiedStmt = $db->prepare($verifiedSql);
            $verifiedStmt->bind_param("s", $username);
            $verifiedStmt->execute();
            $verifiedResult = $verifiedStmt->get_result();

            if ($verifiedResult->num_rows > 0) {
                $verifiedRow = $verifiedResult->fetch_assoc();
                $verified = $verifiedRow['verified'];

            // Only send verification SMS if user is not already verified
            if ($verified !== 'yes') {
                // Generate a verification token
                $verificationToken = bin2hex(random_bytes(16)); // Generate a random 32-character hex token

                // Store the verification token in the database
           

                // Send SMS with the verification link
                // $sid = "";
                // $token = "";
                $sid    = $_ENV["TWILIO_SID"];
                $token  = $_ENV["TWILIO_TOKEN"];
                $twilio = new Client($sid, $token);
                $hostname = getenv('HOSTNAME');

                // If HOSTNAME is not available, try COMPUTERNAME on Windows
                if (!$hostname) {
                    $hostname = getenv('COMPUTERNAME');
                }
                
                // Use the hostname to get the IP address
                $ipAddress = gethostbyname($hostname);
                
                // Display the IP address (IPv4)
                
                 // Example phone number
                $verificationLink = "http://".$ipAddress."/GIGS/Private/verification.php?username=$username&token=$verificationToken";

                    $verificationCode = rand(1000, 9999); // Generate a random 4-digit code

                    // Store the verification code in the database for this user
                    $updateCodeSql = "UPDATE user_verification SET verificationCode = ? WHERE userName = ?";
                    $updateCodeStmt = $db->prepare($updateCodeSql);
                    $updateCodeStmt->bind_param("ss", $verificationCode, $username);
                    $updateCodeStmt->execute();
        
                    
                    // Send SMS with the verification code
                    $messageBody = "Your verification code for GIGS: $verificationCode";

                // Send SMS message
                $message = $twilio->messages
                    ->create($phone,
                        array(
                            "from" => "+17064683484",
                            "body" => $messageBody
                        )
                    );
                    
            }
        }
        }
        // Redirect back to the FirstPage after sending SMS
        header("Location: ../Private/verification.php");
        exit();
    }
    }

    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="refresh" content="5">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style_firstPage.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="icon/Picture4.ico"/>
    <style>
        /* Style for the Verify Account button to match card_empgig */
        .verify-button {
            background-color: #007bff; /* Blue background color */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-bottom: 10px; /* Similar margin to the card_empgig */
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease; /* Smooth transition on hover */
        }

        .verify-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
    </style>
    <title>FirstPage - GIGS</title>
</head>

<body>

<?php include '../Private/navBar.php'; ?>


    <div class="main_firstPage">  <br><br>     
    <div class="typewriter">  
        <!-- Printed the username in typewriter*
-->  
    <h1><a href="../Public/about.php"><img class="Logo" src="../icon/Logo1.png" ><span class="largetext"><?php echo $_SESSION['username']; ?></span></h1></div> <br>

        <div id="wrapper" class="wrapper">
                <div id="first" class="first">Creating comprehensive connections between GIGS and Gig Workers.</div><br>
        </div><br>  

        <div class="card_empgig">
            <a href="../Private/displaygig.php">Gig Workers</a>
        </div><br>
        <div class="card_empgig">
            <a href="../Private/displayadvertisement.php">Display Gig Advertisements</a>
        </div><br>
        <div class="card_empgig">
            <a href="../Private/displayemployer.php">Employers</a>
        </div><br>
        <div class="card_empgig">
            <a href="../Private/displayjob.php">Display Jobs</a>
        </div><br>

        <div class="card_empgig">
            <a href="../Private/displayworkerreviews.php">Reviews of Gig Workers</a>
        </div><br>
        <div class="card_empgig">
            <a href="../Private/displayemployerreviews.php">Reviews of Gig Employers</a>
        </div><br>
        <div class="card_empgig">
        <?php
        
       
          $verifiedSql = "SELECT verified FROM user_verification WHERE userName = ?";
          $verifiedStmt = $db->prepare($verifiedSql);
          $verifiedStmt->bind_param("s", $username);
          $verifiedStmt->execute();
          $verifiedResult = $verifiedStmt->get_result();

          if ($verifiedResult->num_rows > 0) {
              $verifiedRow = $verifiedResult->fetch_assoc();
              $verified = $verifiedRow['verified'];

        if ($verified !== 'yes') {
            echo '   <a href="#" id="verifyLink">Verify Account Using Phone Number</a>';
        }
    }
        ?>
         <script>
        document.getElementById('verifyLink').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior (page navigation)

            // Create a hidden form and append it to the document body
            var form = document.createElement('form');
            form.setAttribute('method', 'post');
            form.setAttribute('action', '<?php echo $_SERVER['PHP_SELF']; ?>'); // Submit to the same page
            form.style.display = 'none'; // Hide the form
            
            // Create a hidden input field for 'verify' parameter
            var input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', 'verify');
            input.setAttribute('value', '1'); // Set value to trigger 'verify' condition
            form.appendChild(input);

            // Append form to document body and submit
            document.body.appendChild(form);
            form.submit();
        });
    </script>

    </div>
    </div>
  
    <?php include 'footer.php'; ?>

</body>
</html>