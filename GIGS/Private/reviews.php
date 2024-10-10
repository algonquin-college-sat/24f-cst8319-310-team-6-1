<?php
   require_once('./dbconnection.php');

   $db = db_connect();
   
   // Check if the form is submitted
   if (isset($_POST['insert'])) {
    // Retrieve form data
    $userName = $_POST['userName'];   
    $company = $_POST['company'];   
    $description = $_POST['description'];
    $rating = $_POST['rating'];

    
    // Insert the gig into the database
    $sql = "INSERT INTO reviews (userName, company, description, rating) 
            VALUES ('$userName', '$company', '$description', '$rating')";

    $result = $db->query($sql);

    if ($result) {
        header('Location: displayreview.php');
        exit();
    } else {
        echo "Error adding the review: " . $db->error;
    }
}
    
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="icon/Picture4.ico"/>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-w3PY0U2zjZQg/qELz0TguHgVT7PX8yHx/qigG+Ll2z+SCMQeIXgzjsFxX9iDfgzfo7h18vqRl/QDG/qG6vvBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Feedback Form</title>
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
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

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            padding: 5px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 5px 5px;
            border: none;
            cursor: pointer;
            font-size: 28px;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Additional CSS for the star rating display */
        select #rating {
            font-size: 24px; 
            width: 50px;
        }

        select #rating option {
            color: gold; 
        }

        .rating{
            height:35px;
            font-size: 17px;
        }
    </style>
</head>
<body>
    <?php include 'navBar.php'; ?>
    
    <div class="container">
    <h1>Leave Feedback</h1>
    <form action="" method="POST">
        <label for="userName">Rated by:</label>
        <input type="text" id="userName" name="userName" required>
        
        <label for="company">Rated user:</label>
        <input type="text" id="company" name="company" required>

        <label for="description">Feedback:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="rating">Star Rating:</label>
        <select id="rating" class="rating" name="rating" required>
            <option value="1">1 Star &#9733;</option>
            <option value="2">2 Stars &#9733;&#9733;</option>
            <option value="3">3 Stars &#9733;&#9733;&#9733;</option>
            <option value="4">4 Stars &#9733;&#9733;&#9733;&#9733;</option>
            <option value="5">5 Stars &#9733;&#9733;&#9733;&#9733;&#9733;</option>
        </select><br>

        <input type="submit" value="Add Review" name="insert">
    </form>
</div>

    <?php include 'footer.php'; ?>

    
</body>
</html>
