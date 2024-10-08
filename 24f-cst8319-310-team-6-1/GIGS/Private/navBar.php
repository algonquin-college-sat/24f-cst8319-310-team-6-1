<?php //session_start(); ?>
<!DOCTYPE html>
<html lang="en" id="htmlTag">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="icon/Picture4.ico"/>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <title></title>    
    <link rel="stylesheet" type="text/css" href="../css/navBar.css">
    <link rel="stylesheet" type="text/css" href="../css/footer.css">
    <script src="../js/navBar.js"></script>

</head>
<body>
    
    <header>
        <nav> 
             <?php
             $folder="/24w-cst8319-300-team5/GIGS/Public/"
             ?>
              <?php // for displaying links if user is logged in*
                
                if(isset($_SESSION['username']) && $_SESSION['username']) {
                    
                    echo '<a href="../Public/firstpage.php"><img class="logo" src="../icon/FLEXYGIG_for dark background.png" width=90 height=20></a>      
                    <a href="../Public/firstpage.php" class="icon"></a>';

                } 
            ?>
            

            
            <div class="mobileMenu">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
            <ul class="nav-list">
            <?php // for displaying links if user is logged in*
                
                    if(isset($_SESSION['username']) && $_SESSION['username']) {
                        
                        echo '<li><a href="../Public/firstpage.php">Main</a></li>';

                    } 
                ?>
                
                <li><a href="../Public/about.php">About</a></li>
                <li><a href="../Public/contact.php">Contact</a></li>
                
                <?php // for displaying links if user is logged in*
                //echo $_SESSION;
                    if(isset($_SESSION['username']) && $_SESSION['username']) {
                        echo '<li><a href="../Private/displayprofile.php">'.$_SESSION['username'].'</a></li>';
                        echo '<li><a href="../Private/logout.php" >Logout</a></li>';
                    } 
                ?>
                
            </ul>
            
        </nav>
    </header>
    
</body>
</html>