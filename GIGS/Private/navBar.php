<?php
//session_start();
require_once 'dbconnection.php';

$hasNewMessages = false;
if (isset($_SESSION['username'])) {
    $db = db_connect();

    // Fetch users who sent new messages to the logged-in user
    $newMessagesQuery = "SELECT DISTINCT `from` FROM newchat WHERE `toUser` = ? AND seen = 0";
    $stmt = $db->prepare($newMessagesQuery);
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    $senders = [];
    while ($row = $result->fetch_assoc()) {
        $senders[] = $row['from'];
    }

    // Set flag if there are new messages
    if (!empty($senders)) {
        $hasNewMessages = true;
    }

    $stmt->close();
} else {
    $senders = [];
}
?>

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

    <style>
        /* Dropdown Menu Style */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        /* Show the dropdown when hovering over .dropdown */
        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Style adjustments for the nav bar */
        .nav-list {
            display: flex;
            gap: 15px;
            align-items: center;
            list-style: none;
            padding: 0;
        }

        .nav-list li {
            display: inline;
        }
    </style>

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
                
                <?php if (isset($_SESSION['username'])) { ?>
                    <!-- Message link with conditional styling for unseen messages -->
                <li class="dropdown">
                    <a href="javascript:void(0)" class="<?php echo $hasNewMessages ? 'new-message' : ''; ?>">Message</a>
                    <div class="dropdown-content">
                        <?php if (!empty($senders)) { ?>
                            <?php foreach ($senders as $sender) { ?>
                                <a href="../../newchat/indexchat.php?userName=<?php echo urlencode($sender); ?>" target="_blank">
                                    <?php echo htmlspecialchars($sender); ?>
                                </a>
                            <?php } ?>
                        <?php } else { ?>
                            <a href="#">No new messages</a>
                        <?php } ?>
                    </div>
                </li>
                    <li><a href="../Private/displayprofile.php"><?php echo $_SESSION['username']; ?></a></li>
                    <li><a href="../Private/logout.php">Logout</a></li>
                <?php } else { ?>
                    <li><a href="../Public/login.php">Login</a></li>
                <?php } ?>
                
            </ul>
            
        </nav>
    </header>
    
</body>
</html>