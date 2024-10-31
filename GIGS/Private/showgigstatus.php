<?php
    #Cannot access the about page if you did not login
    require_once('./dbconnection.php');
    session_start();
    $db = db_connect();

    // Query to fetch gig works and interested jobs data
    $sql = "SELECT g.*, i.id AS interested_id, i.username FROM gigs g LEFT JOIN interested_jobs i ON g.country = i.country AND g.city = i.city AND g.domain = i.domain AND g.company = i.company AND g.duration = i.duration AND g.description = i.description AND g.hourly_paid = i.hourly_paid";
    $result = $db->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>GIG Work Status</title>
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
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
        }
        .message-btn,
        .profile-btn{
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
    </style>
</head>
<body>
    <?php include './navBar.php'; ?>
    <h1>GIG Work Status</h1>

    <div class="container">
    <?php
if ($result->num_rows > 0) {
    $groupedGigs = array();

    // Loop through the results
    while ($row = $result->fetch_assoc()) {
        $key = $row['company'] . $row['city'] . $row['country'] . $row['domain'] . $row['duration'] . $row['description'] . $row['hourly_paid'];
        
        // Check if the gig has interested users
        if (!empty($row['username'])) {
            // If it has interested users, add it to the groupedGigs array with the username
            if (array_key_exists($key, $groupedGigs)) {
                $groupedGigs[$key]['usernames'][] = $row['username'];
            } else {
                $groupedGigs[$key] = array(
                    'company' => $row['company'],
                    'location' => $row['city'] . ', ' . $row['country'],
                    'domain' => $row['domain'],
                    'duration' => $row['duration'],
                    'description' => $row['description'],
                    'hourly_paid' => $row['hourly_paid'],
                    'usernames' => array($row['username']) 
                );
            }
        } else {
            // If it doesn't have interested users, add it to the groupedGigs array without the username
            if (!array_key_exists($key, $groupedGigs)) {
                $groupedGigs[$key] = array(
                    'company' => $row['company'],
                    'location' => $row['city'] . ', ' . $row['country'],
                    'domain' => $row['domain'],
                    'duration' => $row['duration'],
                    'description' => $row['description'],
                    'hourly_paid' => $row['hourly_paid'],
                    'usernames' => array() 
                );
            }
        }
    }

    // Iterate through the grouped gigs and display them
    foreach ($groupedGigs as $entry) {
        echo '<div>';
        echo '<h2>' . $entry['company'] . '</h2>';
        echo '<p>Location: ' . $entry['location'] . '</p>';
        echo '<p>Domain: ' . $entry['domain'] . '</p>';
        echo '<p>Duration: ' . $entry['duration'] . '</p>';
        echo '<p>Description: ' . $entry['description'] . '</p>';
        echo '<p>Hourly Paid: $' . $entry['hourly_paid'] . '</p>';
        
        if (!empty($entry['usernames'])) {
            foreach ($entry['usernames'] as $username) {
                echo '<div class="button-container">';
                echo '<p>Interested User: ' . $username . '</p>';
                echo '<a href="../../newchat/indexchat.php?userName=' . $username . '" target="_blank"><button id="chat" class="message-btn">Chat</button></a>';
                echo '<button type="button" class="profile-btn">Profile</button>';
                echo '</div>';
                echo '<script>
                      document.querySelectorAll(".profile-btn").forEach(function(button) {
                          button.addEventListener("click", function(event) {
                              var username = "' . $username . '";
                              window.location.href = "displayprofile.php?userName=" + username;
                          });
                      });
                  </script>';
            }
        } else {
            // If there are no interested users, display a message
            echo '<p>No interested employers.</p>';
            // No buttons will be displayed for gigs without interested users
        }
        echo '</div>';
    }
} else {
    echo "No gigs found."; // Display a message if no gigs are found
}
?>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
