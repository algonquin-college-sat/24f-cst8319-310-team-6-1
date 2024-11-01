
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
    <title>Display Gigs</title>
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

        /* Container for the gig cards */
.gig-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

/* Each individual gig card */
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

        #addgig, #showgig, #managegig, #chat, #review, #delete, #filter, #contact_form, #subBtn {
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

        .filter-input {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 200px; 
        }
        /* Buttons within each gig card */
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

.action-button:hover {
    background-color: #48BEC5; /* Color on hover */
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

    </style>
</head>
<body>
<?php include './navBar.php'; ?>

<h1>GIG Worker Profiles<br>
    <a href="addform.php"><button id="addgig">Add New Gig Work</button><br></a>
    <a href="managegig.php"><button id="managegig">Manage Gig Work</button><br></a>
     <a href="showgigstatus.php"><button id="showgig">Show Gig Work Status</button><br></a>
</h1>
    <div class="container">
    <div class="gig-container">
        <?php
            require_once('./dbconnection.php');

            $db = db_connect();
            $itemsPerPage = 5; // Number of items to display per page

            // Get the current page number, default to 1 if not set
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    
            // Calculate the starting index for fetching rows based on the current page number
            $startIndex = ($page - 1) * $itemsPerPage;
    
            // Fetch gigs from the database with pagination
            $sql = "SELECT * FROM gigworker LIMIT ?, ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param('ii', $startIndex, $itemsPerPage);
            $stmt->execute();
            $result = $stmt->get_result();
    
            // Check if the form is submitted
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $domainFilter = $_POST['domainFilter'];
                $skillsFilter = $_POST['skills'];
                $countryFilter = $_POST['countryFilter'];
                $cityFilter = $_POST['cityFilter'];
                $availabilityFilter = $_POST['availabilityFilter'];                

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
                
                if (!empty($availabilityFilter)) {
                    $filterConditions[] = "availability = ?";
                    $filterValues[] = $availabilityFilter;
                }                

                if(!empty($skillsFilter)){
                    $filterConditions[] = "skills = ?";
                    $filterValues[] = $skillsFilter;
                }

                if (!empty($filterConditions)) {
                    // Construct the SQL query with the filter conditions
                    $sql = "SELECT * FROM gigworker WHERE " . implode(" AND ", $filterConditions) . " ORDER BY id DESC";
               
                    $stmt = $db->prepare($sql);

                    // Bind the filter values to the statement
                    $types = str_repeat('s', count($filterValues)); 
                    $stmt->bind_param($types, ...$filterValues);

                    $stmt->execute();

                    $result = $stmt->get_result();
                } 
                $sortingField = isset($_POST['sortingField']) ? $_POST['sortingField'] : '';
                $sortingOrder = isset($_POST['sortingOrder']) ? $_POST['sortingOrder'] : '';
            
                // Construct the SQL query with the filter conditions and sorting
                $sql = "SELECT * FROM gigworker";
            
                if (!empty($filterConditions)) {
                    $sql .= " WHERE " . implode(" AND ", $filterConditions);
                }
            
                if (!empty($sortingField) && !empty($sortingOrder)) {
                    $sql .= " ORDER BY $sortingField $sortingOrder";
                } else {
                    $sql .= " ORDER BY id DESC";
                }
            
                $stmt = $db->prepare($sql);
            
                // Bind the filter values to the statement
                if (!empty($filterValues)) {
                    $types = str_repeat('s', count($filterValues));
                    $stmt->bind_param($types, ...$filterValues);
                }
            
                $stmt->execute();
            
                $result = $stmt->get_result();
            } else {
                // Fetch all gigs from the database without a filter
                $sql = "SELECT * FROM gigworker ORDER BY id DESC";
                $result = $db->query($sql);
            }
            function generateFeedbackButton($company) {
                return '<button id="review">' . generateFeedbackLink($company) . '</button>';
            }
            
            function generateFeedbackLink($company) {
                return '<a href="./workerreviews.php?userName=' . urlencode($company) . '" target="_blank">Leave Feedback</a>';
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
            <form method="POST" action="">
                <!--<h4>Filter:</h4>-->
                <label for="domainFilter">Domain:</label><br>
                <select id="domainFilter" name="domainFilter" style="width: 207px; height: 22px;" placeholder="Enter a domain">
                <option value=""></option>
                <optgroup label="Transportation and delivery services">
                    <option value="package">Package delivery driver</option>
                    <option value="food">Food delivery driver</option>
                    <option value="grocery">Grocery delivery driver</option>
                    <option value="bicycle">Bicycle courier</option>
                    <option value="ride">Ride-share driver</option>
                </optgroup>
                <optgroup label="Personal services">
                    <option value="dog">Dog walker</option>
                    <option value="Babysitter">Babysitter or nanny</option>
                    <option value="home">Home health aide</option>
                    <option value="tutor">Tutor</option>
                    <option value="massage">Massage therapist</option>
                    <option value="telehealth">Telehealth provider</option>
                </optgroup>
                <optgroup label="On-demand skilled work">
                    <option value="photographer">Photographer</option>
                    <option value="graphic">Graphic designer</option>
                    <option value="content">Content writer or copywriter</option>
                    <option value="web">Web developer</option>
                    <option value="editor">Editor</option>
                    <option value="consultant">Consultant</option>
                    <option value="translator">Translator</option>
                </optgroup>
                <optgroup label="Home services">
                    <option value="handyperson">Handyperson</option>
                    <option value="mover">Mover</option>
                    <option value="house">House sitter</option>
                    <option value="housekeeper">Housekeeper</option>
                    <option value="cook">Cook</option>
                    <option value="lawn">Lawn care technician or landscaper</option>
                </optgroup>
                <optgroup label="Internet-based gigs">
                    <option value="survey">Survey taker</option>
                    <option value="transcriptionist">Transcriptionist</option>
                    <option value="virtual">Virtual assistant</option>
                    <option value="proofreader">Proofreader</option>
                    <option value="customer">Customer service representative</option>
                    <option value="data">Data entry clerk</option>
                </optgroup>
                <optgroup label="Other">
                    <option value="Other">Other</option>
                </optgroup>
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
                <label for="sortingField">Sort by:</label>
           <select id="sortingField" name="sortingField">
    <option value="id">Default</option>
    <option value="domain">Domain</option>
    <option value="country">Country</option>
    <option value="city">City</option>
    <option value="availability">Availability</option>
    <option value="skills">Skill</option>
</select>

<label for="sortingOrder">Sort order:</label>
<select id="sortingOrder" name="sortingOrder">
    <option value="DESC">Descending</option>
    <option value="ASC">Ascending</option>
</select>
<br>
                <button type="submit" id="filter">Filter</button><br>
            </form><br>';

            // Display the gigs
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="gig">';
                    echo '<h3><a href="displayprofile.php?userName=' . $row['userName'].'">'. $row['userName'] . '</a></h3>';
                    echo '<p>Location: ' . $row['city'] . ', ' . $row['country'] . '</p>';
                    echo '<p>Phone: ' . $row['phone'] . '</p>';
                    echo '<p>Email: ' . $row['userEmail'] . '</p>';
                    echo '<p>Domain: ' . $row['domain'] . '</p>';
                    echo '<p>Availability: ' . $row['availability'] . '</p>';
                    echo '<p>Skills: ' . $row['skills'] . '</p>';
                    echo '<br><a href="../../newchat/indexchat.php?userName='.$row['userName'].'" target="_blank"><button id="chat">Chat</button></a> ';
                    echo generateFeedbackButton($row['userName']);
                    echo generateViewReviewsButton($row['userName']);
                    //echo '<button class="delete-button" data-id="' . $row['id'] . '" target="_blank" id="delete">Delete</button>';
                    echo '</div>';
                }
            } else {
                echo "No gigs found.";
            }
            echo '</div>';
            $sql = "SELECT COUNT(*) AS total FROM gigworker";
            $result = $db->query($sql);
            $row = $result->fetch_assoc();
            $totalItems = $row['total'];
    
            // Calculate the total number of pages
            $totalPages = ceil($totalItems / $itemsPerPage);
    
            // Display pagination links
            echo '<div class="pagination">';
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<a href="?page=' . $i . '">' . $i . '</a>';
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
