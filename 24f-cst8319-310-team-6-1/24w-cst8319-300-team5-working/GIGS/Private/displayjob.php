<?php
session_start();
require_once('./dbconnection.php');
$db = db_connect();

$companyFilter = isset($_SESSION['filters']['companyFilter']) ? $_SESSION['filters']['companyFilter'] : '';
$domainFilter = isset($_SESSION['filters']['domainFilter']) ? $_SESSION['filters']['domainFilter'] : '';
$countryFilter = isset($_SESSION['filters']['countryFilter']) ? $_SESSION['filters']['countryFilter'] : '';
$cityFilter = isset($_SESSION['filters']['cityFilter']) ? $_SESSION['filters']['cityFilter'] : '';
$sortingField = isset($_SESSION['sorting']['sortingField']) ? $_SESSION['sorting']['sortingField'] : 'id';
$sortingOrder = isset($_SESSION['sorting']['sortingOrder']) ? $_SESSION['sorting']['sortingOrder'] : 'DESC';
$gigsPerPage = 5;

// Get the current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the offset
$offset = ($page - 1) * $gigsPerPage;
// Set default sorting parameters if not already set in session
if (!isset($_SESSION['sorting']['sortingField'])) {
    $_SESSION['sorting']['sortingField'] = 'id';
}

if (!isset($_SESSION['sorting']['sortingOrder'])) {
    $_SESSION['sorting']['sortingOrder'] = 'DESC';
}
if (!isset($_SESSION['filters'])) {
    $_SESSION['filters'] = array(
        'companyFilter' => '',
        'domainFilter' => '',
        'countryFilter' => '',
        'cityFilter' => ''
    );
}

if (!isset($_SESSION['sorting'])) {
    $_SESSION['sorting'] = array(
        'sortingField' => 'id',
        'sortingOrder' => 'DESC'
    );
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter'])) {
    // Retrieve filter values from form submission
    $companyFilter = isset($_POST['companyFilter']) ? $_POST['companyFilter'] : '';
    $domainFilter = isset($_POST['domainFilter']) ? $_POST['domainFilter'] : '';
    $countryFilter = isset($_POST['countryFilter']) ? $_POST['countryFilter'] : '';
    $cityFilter = isset($_POST['cityFilter']) ? $_POST['cityFilter'] : '';
    $sortingField = isset($_POST['sortingField']) ? $_POST['sortingField'] : 'id';
    $sortingOrder = isset($_POST['sortingOrder']) ? $_POST['sortingOrder'] : 'DESC';

    // Update filter parameters in session
    $_SESSION['filters'] = array(
        'companyFilter' => $companyFilter,
        'domainFilter' => $domainFilter,
        'countryFilter' => $countryFilter,
        'cityFilter' => $cityFilter
    );

    $_SESSION['sorting'] = array(
        'sortingField' => $sortingField,
        'sortingOrder' => $sortingOrder
    );

    // Redirect or reload the page
    // Construct the URL with the updated filter parameters
    $queryParameters = http_build_query(array(
        'companyFilter' => $companyFilter,
        'domainFilter' => $domainFilter,
        'countryFilter' => $countryFilter,
        'cityFilter' => $cityFilter,
        'sortingField' => $sortingField,
        'sortingOrder' => $sortingOrder
    ));

    // Redirect or reload the page with the updated filter parameters
    header('Location: ' . $_SERVER['PHP_SELF'] . '?' . $queryParameters);
    exit;
}

// Fetch data from the database based on filters and sorting
$sql = "SELECT * FROM gigs";

// Initialize an array to store the filter conditions
$filterConditions = array();

// Initialize an array to store the values for prepared statement
$filterValues = array();

// Add filter conditions based on the filter parameters
if (!empty($companyFilter)) {
    $filterConditions[] = "company = ?";
    $filterValues[] = $companyFilter;
}
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

// Check if there are any filter conditions
if (!empty($filterConditions)) {
    // Append the WHERE clause with all filter conditions
    $sql .= " WHERE " . implode(" AND ", $filterConditions);
}

// Add sorting
$sql .= " ORDER BY $sortingField $sortingOrder";

// Add pagination
$sql .= " LIMIT $gigsPerPage OFFSET $offset";

// Prepare and execute the SQL statement
$stmt = $db->prepare($sql);
if ($stmt) {
    // Bind the filter values to the statement
    if (!empty($filterValues)) {
        $types = str_repeat('s', count($filterValues));
        $stmt->bind_param($types, ...$filterValues);
    }
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "Error in preparing statement: " . $db->error;
}

echo '<!DOCTYPE html>
<html>

<head>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="icon" href="icon/Picture4.ico" />
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <title>Display Gigs</title>
    <style>
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

        #addgig,
        #chat,
        #review,
        #filter {
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

        /* Autocomplete styles */
        #companyContainer {
            position: absolute;
            background-color: #f1f1f1;
            max-height: 150px;
            overflow-y: auto;
            border: 1px solid #ccc;
            display: none;
        }

        .company {
            padding: 5px;
            cursor: pointer;
        }

        .company:hover {
            background-color: #ddd;
        }
    </style>

    <script>
        function autocompleteCompany() {
            var input = document.getElementById("companyFilter");
            var filter = input.value.toUpperCase();
            var container = document.getElementById("companyContainer");
            var companies = document.getElementsByClassName("company");

            container.style.display = "block";

            for (var i = 0; i < companies.length; i++) {
                var company = companies[i].getElementsByTagName("span")[0];
                if (company.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    companies[i].style.display = "";
                } else {
                    companies[i].style.display = "none";
                }
            }
        }

        function selectCompany(selectedCompany) {
            document.getElementById("companyFilter").value = selectedCompany;
            document.getElementById("companyContainer").style.display = "none";
        }
    </script>
</head>

<body>';
include './navBar.php';

echo '<h1>GIG Work Posts<br></h1>
    <div class="container">';

echo '<div id="companyContainer" style="display: none;">';
$sqlCompanies = "SELECT DISTINCT company FROM gigs";
$resultCompanies = $db->query($sqlCompanies);

if ($resultCompanies->num_rows > 0) {
    while ($rowCompany = $resultCompanies->fetch_assoc()) {
        echo '<div class="company" onclick="selectCompany(\'' . $rowCompany['company'] . '\')"><span>' . $rowCompany['company'] . '</span></div>';
    }
}

echo '</div>';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyFilter = $_POST['companyFilter'];
    $domainFilter = $_POST['domainFilter'];
    $countryFilter = $_POST['countryFilter'];
    $cityFilter = $_POST['cityFilter'];

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

    if (!empty($filterConditions)) {
        // Construct the SQL query with the filter conditions
        $sql = "SELECT * FROM gigs WHERE " . implode(" AND ", $filterConditions);
        
        // Optionally, you can add ORDER BY and LIMIT clauses here
        $sql .= " ORDER BY id DESC LIMIT $gigsPerPage OFFSET $offset";

        $stmt = $db->prepare($sql);

        // Bind the filter values to the statement
        $types = str_repeat('s', count($filterValues));
        $stmt->bind_param($types, ...$filterValues);

        $stmt->execute();

        $result = $stmt->get_result();
    } else {
        // If no filters are provided, fetch all gigs from the database
        $sql = "SELECT * FROM gigs ORDER BY id DESC LIMIT $gigsPerPage OFFSET $offset";
        $result = $db->query($sql);
    }

    // Store filter and sorting parameters in the session
    $_SESSION['filters'] = array(
        'companyFilter' => $companyFilter,
        'domainFilter' => $domainFilter,
        'countryFilter' => $countryFilter,
        'cityFilter' => $cityFilter
    );

    $sortingField = isset($_POST['sortingField']) ? $_POST['sortingField'] : '';
    $sortingOrder = isset($_POST['sortingOrder']) ? $_POST['sortingOrder'] : '';

    // Store sorting parameters in the session
    $_SESSION['sorting'] = array(
        'sortingField' => $sortingField,
        'sortingOrder' => $sortingOrder
    );

    // Construct the SQL query with the filter conditions and sorting
    $sql = "SELECT * FROM gigs";

    if (!empty($filterConditions)) {
        $sql .= " WHERE " . implode(" AND ", $filterConditions);
    }

    if (!empty($sortingField) && !empty($sortingOrder)) {
        $sql .= " ORDER BY $sortingField $sortingOrder";
    } else {
        $sql .= " ORDER BY id DESC";
    }

    $sql .= " LIMIT $gigsPerPage OFFSET $offset";
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
    $sql = "SELECT * FROM gigs ORDER BY id DESC LIMIT $gigsPerPage OFFSET $offset";
    $sqlCount = "SELECT COUNT(*) as total FROM gigs";
    $result = $db->query($sql);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    // Reset all filter and sorting parameters to their default values
    $_SESSION['filters'] = array(
        'companyFilter' => '',
        'domainFilter' => '',
        'countryFilter' => '',
        'cityFilter' => ''
    );

    $_SESSION['sorting'] = array(
        'sortingField' => 'id',
        'sortingOrder' => 'DESC'
    );

    // Redirect to the current page to clear the filters
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
echo '<br><div class="gig-container">';

echo '
<form id="filterForm" method="POST" action="">
        <label for="companyFilter">Company Name:</label>
        <input type="text" id="companyFilter" name="companyFilter" class="filter-input" 
               placeholder="Enter a Company Name" oninput="autocompleteCompany()">
        <br>
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
        <form id="resetForm" method="POST" action="">
    <button type="submit" id="reset">Reset Filters</button>
</form>
    </form><br>';
    

echo '</div>';

// Display the gigs
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<li class="gig">';
        echo '<h3>' . $row['company'] . '</h3>';
        echo '<p>Location: ' . $row['city'] . ', ' . $row['country'] . '</p>';
        echo '<p>Domain: ' . $row['domain'] . '</p>';
        echo '<p>Duration: ' . $row['duration'] . '</p>';
        echo '<p>Description: ' . $row['description'] . '</p>';
        echo '<p>Hourly Paid: $' . $row['hourly_paid'] . '</p>';
        echo '<a href="./gigreviews.php?company=' . urlencode($row['company']) . '" target="_blank"><button id="review">Leave Feedback</button></a> ';
        echo '</li>';
    }
} else {
    echo "No gigs added yet.";
}
$sqlCount = "SELECT COUNT(*) as total FROM gigs";
$resultCount = $db->query($sqlCount);
$totalRows = $resultCount->fetch_assoc()['total'];

// Calculate total pages
$totalPages = ceil($totalRows / $gigsPerPage);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$companyFilter = isset($_GET['companyFilter']) ? urldecode($_GET['companyFilter']) : '';
$domainFilter = isset($_GET['domainFilter']) ? urldecode($_GET['domainFilter']) : '';
$countryFilter = isset($_GET['countryFilter']) ? urldecode($_GET['countryFilter']) : '';
$cityFilter = isset($_GET['cityFilter']) ? urldecode($_GET['cityFilter']) : '';
$sortingField = isset($_GET['sortingField']) ? urldecode($_GET['sortingField']) : '';
$sortingOrder = isset($_GET['sortingOrder']) ? urldecode($_GET['sortingOrder']) : 'DESC';

        // Display pagination links
        $totalPages = ceil($totalRows / $gigsPerPage);

        // Display pagination links
 
$baseURL = $_SERVER["PHP_SELF"];
// Display pagination links
echo '<div class="pagination">';
if ($totalPages > 1) {
    // Generate pagination links
    for ($i = 1; $i <= $totalPages; $i++) {
        // Construct the query string with page number and filter parameters
        $queryParams = array(
            'page' => $i,
            'companyFilter' => $companyFilter,
            'domainFilter' => $domainFilter,
            'countryFilter' => $countryFilter,
            'cityFilter' => $cityFilter,
            'sortingField' => $sortingField,
            'sortingOrder' => $sortingOrder
        );

        // Remove any empty parameters from the query
        $queryParams = array_filter($queryParams);
        $pageQueryString = http_build_query($queryParams);

        // Get the base URL without any query string
        $baseURL = strtok($_SERVER["REQUEST_URI"], '?');

        // Create the pagination link
        echo '<a href="' . $baseURL . '?' . $pageQueryString . '">' . $i . '</a> ';
    }
}
echo '</div>';
?>

<script>
function submitForm(pageNumber) {
    // Get the current filter and sorting parameters from the form
    var companyFilter = document.getElementById('companyFilter').value;
    var domainFilter = document.getElementById('domainFilter').value;
    var countryFilter = document.getElementById('countryFilter').value;
    var cityFilter = document.getElementById('cityFilter').value;
    var sortingField = document.getElementById('sortingField').value;
    var sortingOrder = document.getElementById('sortingOrder').value;

    // Construct the URL with the existing filter parameters and the new page number
    var url = window.location.pathname + '?page=' + pageNumber +
              '&companyFilter=' + encodeURIComponent(companyFilter) +
              '&domainFilter=' + encodeURIComponent(domainFilter) +
              '&countryFilter=' + encodeURIComponent(countryFilter) +
              '&cityFilter=' + encodeURIComponent(cityFilter) +
              '&sortingField=' + encodeURIComponent(sortingField) +
              '&sortingOrder=' + encodeURIComponent(sortingOrder);

    // Set the form action to the constructed URL
    document.getElementById('filterForm').action = url;

    // Submit the form
    document.getElementById('filterForm').submit();
}
</script>
    </div>



    <?php include './footer.php'; ?>

</body>

</html>