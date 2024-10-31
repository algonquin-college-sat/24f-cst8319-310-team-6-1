<?php

#Cannot access the about page if you did not login
session_start();
if($_SESSION['username']) {
}
else {
    header ("Location:  ../Public/index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="icon/Picture4.ico"/>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <title>Display Reviews</title>
    <style>
        /*
        PROJECT COLORS:
        #084D6A - DARK BLUE
        #48BEC5 - LIGHT BLUE
        #F0F1B7 - BEIGE
        #97D779 - GREEN
        */
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

        .gig {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            list-style-type: none;
        }

        .gig:nth-child(even) {
            background-color: #f1f1f1;
        }

        .gig h3 {
            margin: 0;
            font-size: 18px;
        }

        .gig p {
            margin: 5px 0;
        }

        #addgig, #chat, #review {
            border-radius: 5px;
            padding: 3px 12px;
            font-weight: 800;
            font-size: 18px;
            border: none;
            background-color: #084D6A;
            color: #97D779;
            cursor: pointer;
        }

        .gig-container p .stars {
            color: #97D779;
            font-size: 22px;
        }
    </style>
</head>
<body>
    <?php include './navBar.php'; ?>


    <div class="container">
        <?php
        function displayStars($rating) {
            $starsHtml = '';
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $rating) {
                    $starsHtml .= '&#9733; '; // Filled star
                } else {
                    $starsHtml .= '&#9734; '; // Empty star
                }
            }
            return $starsHtml;
        }

        require_once('./dbconnection.php');
        $db = db_connect();

        $companyFilter = isset($_GET['company']) ? $_GET['company'] : '';

        // Build the SQL query
        $sql = "SELECT r.*, e.phone AS employer_phone, e.domain AS employer_domain
        FROM reviews r
        INNER JOIN employer e ON r.company = e.userName";
        // Apply sorting
        $sortByStars = isset($_GET['sortStars']) ? true : false;
        $sortLowStars = isset($_GET['sortLowStars']) ? true : false;
        if ($sortByStars) {
            $sql .= " ORDER BY r.rating DESC";
        } elseif ($sortLowStars) {
            $sql .= " ORDER BY r.rating ASC";
        } else {
            $sql .= " ORDER BY r.id DESC";
        }

        // Execute the SQL query
        $result = $db->query($sql);

        if ($result && $result->num_rows > 0) {
            echo '<div class="gig-container">';
            while ($row = $result->fetch_assoc()) {
                echo '<li class="gig">';
                echo '<h3>' . htmlspecialchars($row['company']) . '</h3>';
                echo '<p>Rated by: ' . htmlspecialchars($row['userName']) . '</p>';
                echo '<p>Description: ' . htmlspecialchars($row['description']) . '</p>';
                echo '<p>Rating: <span class="stars">' . displayStars($row['rating']) . '</span></p>'; 
                echo '</li>';
            }
            echo '</div>';
        } else {
            echo "No reviews found.";
        }
        ?>
        
        <!-- Sorting buttons -->
        <a href="?company=<?php echo urlencode($companyFilter); ?>&sortStars=true"><button class="button">Sort by Stars</button></a>
        <a href="?company=<?php echo urlencode($companyFilter); ?>&sortLowStars=true"><button class="button">Sort by Low Stars</button></a>
    </div>

    <?php include './footer.php'; ?>
</body>
</html>
