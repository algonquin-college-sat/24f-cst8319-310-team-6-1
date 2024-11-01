<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style_firstPage.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap">
    <link rel="icon" href="../icon/Picture4.ico"/>
    <script src="../js/registration.js" defer ></script>
    <title>About Us</title>

</head>

<body>    

<?php include '../Private/navBar.php'; ?>
    
    <div class="main_firstPage">        
        <div class="typewriter">    
        <h1>About FlexyGig...</h1></div> <br>        
        
            <div id="wrapper" class="wrapper">
                    <div id="first" class="first">We are a company that combines employers and gig workers, 
                        focused on connecting people with job opportunities in a simple and fast way. The gig 
                        economy provides a workforce capable of directly matching the company to the gig worker.<br>
                        Our operation involves direct contact between employer and gig worker, eliminating the 
                        hassle of dealing with a middleman throughout the hiring process, making it easier to 
                        find people to complete the job.</div><br>
                    
                    
            </div><br>    
            <div id="second" class="logo"><a href="firstpage.php"><img src="../icon/FLEXYGIG_for light background.png" width=160 height=40></a></div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

        
</body>


</html>

