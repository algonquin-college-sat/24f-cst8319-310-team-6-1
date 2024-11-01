
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
    <title>Display Employers</title>
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
            color: #084D6A;
        }

        /* Container for the employer cards */
.gig-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

/* Each individual employer card */
.gig {
    flex: 1 1 calc(33.333% - 20px); /* 3 cards per row */
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.2s;
    margin: 10px;
}

.gig:hover {
    transform: translateY(-5px); /* Lift effect on hover */
}

        .gig:nth-child(even) {
            background-color: #f1f1f1;
        }

        .gig h3 {
            margin: 0;
            font-size: 18px;
            color: #084D6A;
        }

        .gig p {
            margin: 5px 0;
        }

        #addgig, #chat, #review, #delete, #filter, #contact_form {
            border-radius: 5px;
            padding: 3px 12px;
            font-weight: 800;
            font-size: 18px;
            border: none;
            background-color: #084D6A;
            color: #97D779;
            cursor: pointer;
        }

        .filter-input {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 200px; 
        }

/* Buttons within each employer card */
.action-button, #chat, #review {
    border-radius: 5px;
    padding: 5px 10px;
    font-weight: 800;
    font-size: 14px;
    border: none;
    background-color: #084D6A;
    color: #97D779;
    cursor: pointer;
    margin-top: 10px;
}

/* Hover effect for buttons */
.action-button:hover, #chat:hover, #review:hover {
    background-color: #48BEC5;
}

/* Responsive design for smaller screens */
@media (max-width: 768px) {
    .gig {
        flex: 1 1 calc(50% - 20px); /* 2 cards per row on tablets */
    }
}

@media (max-width: 480px) {
    .gig {
        flex: 1 1 100%; /* Full width on mobile */
    }
}
    </style>
</head>
<body>
    <?php include './navBar.php'; ?>
 
    <h1>Employer Profiles<br><a href="addadvertisement.php"><button id="addgig">Add New Gig Worker Advertisement</button><br></a></h1>
    
    <div class="container">
    <div class="gig-container">
        <?php
            require_once('./dbconnection.php');

            $db = db_connect();
            $searcha=false;
            // Check if the form is submitted using GET*
            
                if (array_key_exists('domainFilter', $_GET)){
                    $domainFilter = $_GET['domainFilter'];
                    $searcha=true;
                }
                if (array_key_exists('skills', $_GET)){
                    $skillsFilter = $_GET['skills'];
                    $searcha=true;
                }
                if (array_key_exists('countryFilter', $_GET)){
                    $countryFilter = $_GET['countryFilter'];
                    $searcha=true;
                }
                if (array_key_exists('cityFilter', $_GET)){
                    $cityFilte = $_GET['cityFilter'];
                    $searcha=true;
                }
                if (array_key_exists('availabilityFilter', $_GET)){
                    $availabilityFilter = $_GET['availabilityFilter'];
                    $searcha=true;
                }  
                //if the form was submitted using GET, then display only employers that match the search criteria *           
            if ($searcha) {
                $filterConditions = array();
                $filterValues = array();

                // Construct the filter conditions and values based on the submitted data
                if (!empty($domainFilter)) {
                    $filterConditions[] = "domain = ?";
                    $filterValues[] = $domainFilter;
                }

                if (!empty($countryFilter)) {
                    $filterConditions[] = "country = ?";
                    $filterValues[] = $countryFilter;
                }

                if (!empty($cityFilter)) {
                    $filterConditions[] = "city = ?";
                    $filterValues[] = $cityFilter;
                }
                
                /**if (!empty($availabilityFilter)) {
                    $filterConditions[] = "availability = ?";
                    $filterValues[] = $availabilityFilter;
                }                

                if(!empty($skillsFilter)){
                    $filterConditions[] = "skills = ?";
                    $filterValues[] = $skillsFilter;
                }
**/
                if (!empty($filterConditions)) {
                    // Construct the SQL query with the filter conditions*
                    $sql = "SELECT * FROM employer WHERE " . implode(" AND ", $filterConditions) . " ORDER BY id DESC";
               
                    $stmt = $db->prepare($sql);

                    // Bind the filter values to the statement
                    $types = str_repeat('s', count($filterValues)); 
                    $stmt->bind_param($types, ...$filterValues);

                    $stmt->execute();

                    $result = $stmt->get_result();
                } 
            } else {
                // Fetch all employers from the database without a filter*
                $sql = "SELECT * FROM employer ORDER BY id DESC";
                $result = $db->query($sql);
            }
            function generateFeedbackButton($company) {
                return '<button id="review">' . generateFeedbackLink($company) . '</button>';
            }
            
            function generateFeedbackLink($company) {
                return '<a href="./employerreviews.php?company=' . urlencode($company) . '" target="_blank">Leave Feedback</a>';
            }
            function generateViewReviewsButton($company) {
                return '<form method="GET" action="displayreview.php" style="margin-top: 10px;">' .
                       '<input type="hidden" name="company" value="' . htmlspecialchars($company) . '">' .
                       '<button type="submit" class="action-button">View Reviews</button>' .
                       '</form>';
            }
            echo '<br><div class="gig-container">';

            // Display the filter form
            echo '
            <form method="GET" action="">
                <!--<h4>Filter:</h4>-->
                <label for="domainFilter">Domain:</label><br>
                <select id="domainFilter" name="domainFilter" style="width: 207px; height: 22px;" placeholder="Enter a domain">
                    <option value="" ></option>
                    <option value="Transportation">Transportation and delivery services</option>
                    <option value="Construction">Construction</option>
                    <option value="Restaurant">Restaurant</option>
                    <option value="Rental">Rental Services</option>
                    <option value="Consultant">Consultant</option>
                    <option value="Bartender">Bartender</option>                    
                    <option value="Other" >Other</option>
                </select><br>
                <br>
                <div class="form_content">
                <label for="countryFilter">Country:</label><br>
                <select id="countryFilter" name="countryFilter" style="width: 207px; height: 22px;"></select>

                    <script>
                    // Fetch the country data from the API
                    fetch("https://restcountries.com/v3.1/all")
                        .then(response => response.json())
                        .then(data => {
                            const countryDropdownElement = document.getElementById("countryFilter");

                            // Sort the country names alphabetically
                            const sortedCountries = data
                                .map(country => country.name.common)
                                .sort();

                            // Move Canada to the front
                            const canadaIndex = sortedCountries.indexOf("Canada");
                            if (canadaIndex !== -1) {
                                sortedCountries.splice(canadaIndex, 1);
                                sortedCountries.unshift("Canada");
                            }

                            sortedCountries.forEach(countryName => {
                                const optionElement = document.createElement("option");
                                optionElement.textContent = countryName;
                                optionElement.value = countryName;
                                countryDropdownElement.appendChild(optionElement);
                            });
                        })
                        .catch(error => console.error("Error:", error));
                    </script><br>
                </div><br>
                <label for="cityFilter">City:</label>
                <input type="text" id="cityFilter" name="cityFilter" class="filter-input" style="height: 18px;" placeholder="Enter a city name">
                <br>
                <label for="availabilityFilter">Availability:</label><br>
                <select id="availabilityFilter" name="availabilityFilter" style="width: 207px; height: 22px;">
                        <option value="" >Enter an availability</option>                    
                        <option value="all" >All Schedules</option>
                        <option value="morning">Morning</option>
                        <option value="evening">Evening</option>
                        <option value="night">Night</option>
                        <option value="overnight">Overnight</option>
                        <option value="morning_evening" >Morning and Evening</option>
                        <option value="morning_night" >Morning and Night</option>
                        <option value="morning_overnight" >Morning and Overnight</option>
                        <option value="evening_night" >Evening and Night</option>
                        <option value="evening_overnight" >Evening and Overnight</option>
                        <option value="night_overnight" >Night and Overnight</option>
                        <option value="morning_evening_night" >Morning, Evening and Night</option>
                        <option value="morning_evening_overnight" >Morning, Evening and Overnight</option>
                        <option value="evening_night_overnight" >Evening, Night and Overnight</option>
                        <option value="night_overnight_morning" >Night, Overnight and Morning</option>
                    </select><br>
                <br>
                <label for="skillsFilter">Skill:</label>
                <input type="text" id="skillsFilter" name="skills" class="filter-input" placeholder="Enter a skill">
                <br>
                <button type="submit" id="filter">Filter</button><br>
            </form><br>';

            // Display the gigs
            //Uses the result from sql to display employer information
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="gig">';
                    echo '<h3><a href="displayprofile.php?userName=' . $row['userName'].'">'. $row['userName'] . '</a></h3>';
                    echo '<p>Location: ' . $row['city'] . ', ' . $row['country'] . '</p>';
                    echo '<p>Phone: ' . $row['phone'] . '</p>';
                    echo '<p>Email: ' . $row['userEmail'] . '</p>';
                    echo '<p>Domain: ' . $row['domain'] . '</p>';
                    echo '<br><a href="../../newchat/indexchat.php?userName='.$row['userName'].'" target="_blank"><button id="chat">Chat</button></a> ';
                    echo '<br>' . generateFeedbackButton($row['userName']);
                    echo generateViewReviewsButton($row['userName']);                    
                    //echo '<button class="delete-button" data-id="' . $row['id'] . '" target="_blank" id="delete">Delete</button>';
                    echo '</div>';
                }
            } else {
                echo "No employers found.";
            }
            echo '</div>';
        ?>
    </div>       
    </div>
    

    <?php include 'footer.php'; ?>

    <script>
    // Get all delete buttons by their class name
    const deleteButtons = document.getElementsByClassName('delete-button');

    // Attach click event listener to each delete button
    Array.from(deleteButtons).forEach(button => {
        button.addEventListener('click', deleteGig);
    });

    // Function to handle the delete button click event
    function deleteGig(event) {
        const gigId = event.target.dataset.id;
        const confirmation = confirm("Are you sure you want to delete this gig?");

        if (confirmation) {
            // Send a POST request to delete.php to delete the gig
            fetch('delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${gigId}`,
            })
                .then(response => response.text())
                .then(result => {
                    console.log(result); // You can handle the response here if needed
                    // Update the gig list after successful deletion
                    event.target.closest('.gig').remove();
                })
                .catch(error => console.error(error));
        }
    }

    
        </script>


</body>
</html>
