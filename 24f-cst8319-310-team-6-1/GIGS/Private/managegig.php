<?php
session_start();
require_once('dbconnection.php');

// Connect to the database
$db = db_connect();
$username = $_SESSION['username'];
// Fetch all gigs from the database
$sql = "SELECT * FROM gigs where company='$username'";
$result = $db->query($sql);

if(isset($_POST['delete'])){
    $id_to_delete = $_POST['id'];
    
    // Delete gig from the database
    $sql_delete = "DELETE FROM gigs WHERE id = '$id_to_delete'";
    $result_delete = $db->query($sql_delete);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="icon/Picture4.ico"/>
    <title>Manage Gigs</title>    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #084D6A;
        }
        .gig-container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
        }

         .edit-btn,
        .delete-btn {
            border-radius: 5px;
            padding: 3px 12px;
            font-weight: 800;
            font-size: 18px;
            border: none;
            background-color: #084D6A;
            color: #97D779;
            cursor: pointer;
            margin-top: 10px;
        }

        .edit-btn:hover,
        .delete-btn:hover {
            opacity: 0.8; 
        }

        .edit-btn {
            background-color: #007bff;
            color: #fff;
        }

        .delete-btn {
            background-color: #dc3545;
            color: #fff;
        }

        .edit-btn,
        .delete-btn,
        .delete-btn:focus {
            outline: none;
        }
    </style>
</head>
<body>
<?php include './navBar.php'; ?>
<h1>Manage Gigs</h1>
<div class="container">
    <?php
    // Check if there are gigs
    if ($result && $result->num_rows > 0) {
        // Output gigs
        while ($row = $result->fetch_assoc()) {
            echo "<div class='gig-container'>";
            echo "<p>Country: " . $row['country'] . "</p>";
            echo "<p>City: " . $row['city'] . "</p>";
            echo "<p>Domain: " . $row['domain'] . "</p>";
            echo "<p>Company: " . $row['company'] . "</p>";
            echo "<p>Duration: " . $row['duration'] . "</p>";
            echo "<p>Description: " . $row['description'] . "</p>";
            echo "<p>Hourly Paid: " . $row['hourly_paid'] . "</p>";
            
            // Edit button to redirect to editgig.php with gig ID
            echo "<a href='editgig.php?id=" . $row['id'] . "'><button class='edit-btn'>Edit</button></a>";
            
            // Delete button to delete the gig
            echo "<form method='post'>";
            echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
            echo "<input type='submit' name='delete' value='Delete' class='delete-btn'>";
            echo "</form>";
            
            echo "</div>";
        }
    } else {
        echo "No gigs found.";
    }
    ?>
</div>

    <?php include 'footer.php'; ?>
</body>
</html>
