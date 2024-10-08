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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=BioRhyme+Expanded:wght@300;700&display=swap" />
    <link rel="icon" href="icon/Picture4.ico"/>
    <script src="js/registration.js" defer ></script>
    <title>About Us</title>

</head>
<style>
    
#first a{
    color: #48BEC5;
}

#first a:hover{
    color: #97D779;
    font-weight: 800;
}

</style>
<body>    

<?php include '../Private/navBar.php'; ?>

    <div class="main_firstPage">        
        <div class="typewriter">    
        <h1>Contact FlexyGig <span style='font-size:70px;'>&#9993;</span></h1></div> <br>  
        
            <div id="wrapper" >
                    <div id="first" class="first">Any questions?
                    <a href = "mailto: abc@example.com" target="_blank">Contact Us</a>
                    </div><br>                    
                    
            </div><br>    
            <div id="second" class="logo"><a href="firstpage.php"><img src="../icon/FLEXYGIG_for light background.png" width=160 height=40></a></div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

        
</body>


</html>

