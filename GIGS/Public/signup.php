<?php include '../Private/navBar.php'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style_signup.css"/>
    <link rel="stylesheet" href="images/"/>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="icon/Picture4.ico"/>
    <title>Sign Up - GIGS</title>

</head>

<body>

<!--
    Added a new account button to implement the new unified account creation page
-->
    <div class="main_signup">        
        <h1>Join us as an employer or gig worker</h1> <br>        
                <div class="card">
                <div class="card_empgig">
                    <a href="reg_account.php">New Account</a>
                </div><br> 
                <div class="index">
                    <a href="reg_employer.php">I'm an employer</a>
                </div>
                <br>
                <div class="index">
                    <a href="reg_gigworker.php">I'm a gig worker</a>
                </div><br>
                <div class="index">
                    Already have an account? <br>
                    <a href="index.php"> Login</a>
                </div>
                </div>

    </div>

    

    
</body>
</html>


<?php include 'footer.php'; ?>
