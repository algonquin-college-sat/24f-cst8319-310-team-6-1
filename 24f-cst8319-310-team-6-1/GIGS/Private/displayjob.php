<?php

#Cannot access the page if you did not login
session_start();
if($_SESSION['username']) {
    $username = $_SESSION['username'];
}
else {
    header ("Location:  ../Public/index.php");
}
?>

<?php

    // Database connection
    require_once('./dbconnection.php');
    $db = db_connect();

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['interest'])) {
        // Retrieve form data
        $country = $_POST['country'];
        $city = $_POST['city'];
        $domain = $_POST['domain'];
        $company = $_POST['company'];
        $duration = $_POST['duration'];
        $description = $_POST['description'];
        $hourlyPaid = $_POST['hourly'];
        
        // Insert into database
        $sql = "INSERT INTO interested_jobs (username, country, city, domain, company, duration, description, hourly_paid)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);

        // Check if the statement preparation was successful
        if (!$stmt) {
            echo "Error preparing the statement: " . $db->error;
            exit();
        }
        
        // Bind parameters
        $stmt->bind_param("ssssssss",  $username, $country, $city, $domain, $company, $duration, $description, $hourlyPaid);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to the same page after inserting data
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error adding the job: " . $stmt->error;
        }

    }
    $sql = "SELECT COUNT(*) AS total FROM gigs ";
if (!empty($filterConditions)) {
    $sql .= "WHERE " . implode(" AND ", $filterConditions) . " ";
}


$sortingField = isset($_POST['sortingField']) ? $_POST['sortingField'] : 'id';
$sortingOrder = isset($_POST['sortingOrder']) ? $_POST['sortingOrder'] : 'DESC';

// Reconstruct SQL query for fetching gig posts with pagination
$sql = "SELECT * FROM gigs ";
if (!empty($filterConditions)) {
    $sql .= "WHERE " . implode(" AND ", $filterConditions) . " ";
}
$sql .= "ORDER BY $sortingField $sortingOrder LIMIT ?, ?"; // Modified SQL query with placeholders for limit


// Prepare the SQL statement with placeholders
$stmt = $db->prepare($sql);

// Bind parameters for limit
$stmt->bind_param("ii", $offset, $posts_per_page);

// Execute the prepared statement
$stmt->execute();

// Get the result set
$result = $stmt->get_result();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removeFilters'])) {
    // Reset filter conditions
    $filterConditions = array();
    $filterValues = array();
    // Redirect to the same page after removing filters
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="icon/Picture4.ico"/>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <title>Display Gigs</title>
        <!-- Include Firebase SDK -->
        <script src="https://www.gstatic.com/firebasejs/9.6.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.0/firebase-messaging.js"></script>
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

        #addgig, #chat, #review, #filter, #interestBtn, #displayBtn {
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
        .action-button {
    border-radius: 5px;
    padding: 3px 12px;
    font-weight: 800;
    font-size: 18px;
    border: none;
    background-color: #084D6A; /* Same color as other buttons */
    color: #97D779; /* Text color */
    cursor: pointer;
    margin-top: 10px;
}

.action-button:hover {
    background-color: #48BEC5; /* Color on hover */
}
    </style>
</head>
<body>
    <?php include './navBar.php'; ?>

    <h1>GIG Work Posts<br></h1>
    <div class="container">
    <?php
            require_once('./dbconnection.php');

            $db = db_connect();

            // Check if the form is submitted
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $companyFilter = $_POST['companyFilter'];
                $domainFilter = $_POST['domainFilter'];
                $countryFilter = $_POST['countryFilter'];
                $cityFilter = $_POST['cityFilter'];      
                $sortingField = isset($_POST['sortingField']) ? $_POST['sortingField'] : 'id';
                $sortingOrder = isset($_POST['sortingOrder']) ? $_POST['sortingOrder'] : 'DESC';
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
                
                if (!empty($companyFilter)) {
                    $filterConditions[] = "company = ?";
                    $filterValues[] = $companyFilter;
                }  
                if (!in_array($sortingField, ['id', 'company', 'country', 'city'])) {
                    $sortingField = 'id'; // Default to id if sorting field is invalid
                }
            
                if (!in_array($sortingOrder, ['ASC', 'DESC'])) {
                    $sortingOrder = 'DESC'; // Default to DESC if sorting order is invalid
                }
                if (!empty($filterConditions)) {
                    // Construct the SQL query with the filter conditions
                    $sql = "SELECT * FROM gigs WHERE " . implode(" AND ", $filterConditions) . " ORDER BY $sortingField $sortingOrder";

                    $stmt = $db->prepare($sql);

                    // Bind the filter values to the statement
                    $types = str_repeat('s', count($filterValues));
                    $stmt->bind_param($types, ...$filterValues);

                    $stmt->execute();

                    $result = $stmt->get_result();
                } else {
                    // If no filters are provided, fetch all gigs from the database
                    $sql = "SELECT * FROM gigs ORDER BY $sortingField $sortingOrder";
                    $result = $db->query($sql);
                }
                
            } else {
                // Fetch all gigs from the database without a filter
                $sql = "SELECT * FROM gigs ORDER BY id DESC";
                $result = $db->query($sql);
            }
            
            function generateFeedbackButton($company, $db) {
                // Prepare SQL statement to check if the company exists in interested_jobs
                $sql = "SELECT COUNT(*) AS count FROM interested_jobs WHERE company = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("s", $company);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
            
                if ($row['count'] > 0) {
                    // If the company exists in interested_jobs, return the button
                    return '<button id="review">' . generateFeedbackLink($company) . '</button>';
                } else {
                    // Otherwise, return an empty string (no button)
                    return '';
                }
            }
            
            function generateFeedbackLink($company) {
                return '<a href="./gigreviews.php?company=' . urlencode($company) . '" target="_blank">Leave Feedback</a>';
            }
            function generateViewReviewsButton($company) {
                return '<form method="GET" action="displayreview.php" style="margin-top: 10px;">' .
                       '<input type="hidden" name="company" value="' . htmlspecialchars($company) . '">' .
                       '<button type="submit" class="action-button">View Reviews</button>' .
                       '</form>';
            }
            
          
            echo '<br><div class="gig-container">';
            echo '<form method="POST" action="">';
            echo '<label for="companyFilter">Company Name:</label>';
            echo '<br>';
            echo '<input type="text"  id="companyFilter" name="companyFilter" placeholder="Search...">';
           echo ' <div id="gigList">';
            // Display the filter form
            echo '
            
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
                <select id="countryFilter" name="countryFilter" style="width: 207px; height: 22px;">
                <option value="">All Countries</option>
                </select>

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
                <input type="text" id="cityFilter" name="cityFilter" class="filter-input" placeholder="Enter a city name">
                <br> 
                <label for="sortingField">Sort by:</label>
<select id="sortingField" name="sortingField">
    <option value="id">Default</option>
    <option value="company">Company Name</option>
    <option value="country">Country</option>
    <option value="city">City</option>
</select>

<label for="sortingOrder">Sort order:</label>
<select id="sortingOrder" name="sortingOrder">
    <option value="DESC">Descending</option>
    <option value="ASC">Ascending</option>
</select>
<br>                      
                <button type="submit" id="filter">Filter</button><br>

            </form><br>
            <form method="POST" action="">
    <button type="submit" id="removeFiltersBtn" name="removeFilters">Remove Filters</button>
</form>';
            
      

            // Display the gigs
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<li class="gig">';
                    echo '<h3><a href="displayprofile.php?userName=' . $row['company'].'">'. $row['company'] . '</a></h3>';
                    echo '<p>Location: ' . $row['city'] . ', ' . $row['country'] . '</p>';
                    echo '<p>Domain: ' . $row['domain'] . '</p>';
                    echo '<p>Duration: ' . $row['duration'] . '</p>';
                    echo '<p>Description: ' . $row['description'] . '</p>';
                    echo '<p>Hourly Paid: $' . $row['hourly_paid'] . '</p>';
                    echo generateFeedbackButton($row['company'], $db);
                    echo generateViewReviewsButton($row['company']);
                    // Display the form with hidden input fields
                    echo '<form class="interestForm" method="post" action="">';
                    echo '<input type="hidden" name="username" value="' . $_SESSION['username'] . '">';
                    echo '<input type="hidden" name="country" value="' . $row['country'] . '">';
                    echo '<input type="hidden" name="city" value="' . $row['city'] . '">';
                    echo '<input type="hidden" name="domain" value="' . $row['domain'] . '">';
                    echo '<input type="hidden" name="company" value="' . $row['company'] . '">';
                    echo '<input type="hidden" name="duration" value="' . $row['duration'] . '">';
                    echo '<input type="hidden" name="description" value="' . $row['description'] . '">';
                    echo '<input type="hidden" name="hourly" value="' . $row['hourly_paid'] . '">';
                    echo '<button type="submit" id="interestBtn" name="interest">I\'m Interested</button>';
                    echo '</form>';
                    echo '</li>';
                }
            } else {
                echo "No gigs added yet.";
            }
            echo ' <div id="gigList">';
            echo '</div>';
            
        ?>
        
           <form action="displayInterestedJob.php" method="GET">
        <button type="submit" id="displayBtn" name:"display">Display Interested Jobs</button>
    </form>
    </div>
  
    <?php include './footer.php'; ?>
    <script>
        // Function to perform live search
        function liveSearch() {
            var input, filter, gigs, gig, i, txtValue;
            input = document.getElementById('companyFilter');
            filter = input.value.toUpperCase();
            gigs = document.getElementById('gigList');
            gig = gigs.getElementsByClassName('gig');

            // Loop through all gigs, and hide those that do not match the search query
            for (i = 0; i < gig.length; i++) {
                txtValue = gig[i].textContent || gig[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    gig[i].style.display = '';
                } else {
                    gig[i].style.display = 'none';
                }
            }
        }

        // Add event listener to the live search input field
        document.getElementById('companyFilter').addEventListener('input', liveSearch);
    </script>
</body>
</html>
