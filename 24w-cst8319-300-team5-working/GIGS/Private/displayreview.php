<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="icon/Picture4.ico" />
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

        #addgig,
        #chat,
        #review,
        #sortStars,
        #sortLowStars {
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

    <h1>Feedback<br><a href="reviews.php"><button id="review">Leave Feedback</button><br></a></h1>

    <div class="container">
        <?php

        function displayStars($rating)
        {
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

        echo '<div class="gig-container">';

        // Check if the sort button is pressed
        $sortByStars = isset($_GET['sortStars']) ? $_GET['sortStars'] : false;
        $sortLowStars = isset($_GET['sortLowStars']) ? $_GET['sortLowStars'] : false;

        // Fetch gigs from the database
        $sql = "SELECT * FROM reviews";

        // Sort by stars if the button is pressed
        if ($sortByStars) {
            $sql .= " ORDER BY rating DESC";
        } elseif ($sortLowStars) {
            $sql .= " ORDER BY rating ASC";
        } else {
            $sql .= " ORDER BY id DESC";
        }

        $result = $db->query($sql);

        // Display the gigs
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<li class="gig">';
                echo '<h3>' . $row['company'] . '</h3>';
                echo '<p>Rated by: ' . $row['userName'] . '</p>';
                echo '<p>Description: ' . $row['description'] . '</p>';
                echo '<p>Rating: <span class="stars">' . displayStars($row['rating']) . '</span></p>';
                echo '</li>';
            }
        } else {
            echo "No review added yet.";
        }
        echo '</div>';
        ?>

        <!-- Add buttons to sort by stars and low stars -->
        <a href="?sortStars=true"><button id="sortStars">Sort by Stars</button></a>
        <a href="?sortLowStars=true"><button id="sortLowStars">Sort by Low Stars</button></a>
    </div>

    <?php include './footer.php'; ?>
</body>

</html>