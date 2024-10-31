<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../Public/index.php");
    exit();
} else {
    $username = $_SESSION["username"];
}
// Connect to the database
require_once ('./dbconnection.php');
$db = db_connect();
// Query the database to fetch the interested jobs
$sql = "SELECT * FROM interested_jobs where username = '$username'";
$result = $db->query($sql);
// Process filter criteria
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $countryFilter = isset($_POST['countryFilter']) ? $_POST['countryFilter'] : '';
    $cityFilter = isset($_POST['cityFilter']) ? $_POST['cityFilter'] : '';
    $domainFilter = isset($_POST['domainFilter']) ? $_POST['domainFilter'] : '';
    $hourlyPaidFilter = isset($_POST['hourlyPaidFilter']) ? $_POST['hourlyPaidFilter'] : '';
    // Check if any filter inputs are provided
    if (!empty($countryFilter) || !empty($cityFilter) || !empty($domainFilter) || !empty($hourlyPaidFilter)) {
        $filterConditions = array();
        $filterValues = array();
        if (!empty($countryFilter)) {
            $filterConditions[] = "country = ?";
            $filterValues[] = $countryFilter;
        }
        if (!empty($cityFilter)) {
            $filterConditions[] = "city = ?";
            $filterValues[] = $cityFilter;
        }
        if (!empty($domainFilter)) {
            $filterConditions[] = "domain = ?";
            $filterValues[] = $domainFilter;
        }
        if (!empty($hourlyPaidFilter)) {
            $filterConditions[] = "hourly_paid = ?";
            $filterValues[] = $hourlyPaidFilter;
        }
        if (!empty($filterConditions)) {
            // Add additional conditions to the SQL query
            $sql.= " WHERE " . implode(" AND ", $filterConditions);
            // Prepare and execute the filtered query
            $stmt = $db->prepare($sql);
            $types = str_repeat('s', count($filterValues));
            $stmt->bind_param($types, ...$filterValues);
            $stmt->execute();
            $result = $stmt->get_result();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Interested Jobs</title>
    <style>
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
        .filter-form {
            margin-bottom: 20px;
        }
        .filter-label {
            font-weight: bold;
        }
        .filter-input {
            width: 100%;
            height: 30px;
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .filter-select {
            width: 100%;
            height: 30px;
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #fff;
        }
        #filter-button {
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: bold;
            font-size: 16px;
            border: none;
            background-color: #48BEC5;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #filter-button:hover {
            background-color: #084D6A;
        }
    </style>
</head>
<body>
<?php include './navBar.php'; ?>

<h1>GIG Interested Jobs</h1>
<div class="container">
            
<!-- Filter form -->
<form method="POST" action="" class="filter-form">
    <div class="form-group">
        <label for="domainFilter" class="filter-label">Domain:</label><br>
        <select id="domainFilter" name="domainFilter" class="filter-select" required>
            <optgroup label="Select a Domain...">
                <option value=""></option>
            </optgroup>
            <optgroup label="Transportation and delivery services">
                <option value="package">Package delivery driver</option>
                <option value="food">Food delivery driver</option>
                <option value="grocery">Grocery delivery driver</option>
                <option value="bicycle">Bicycle courier</option>
                <option value="ride">Ride-share driver</option>
            </optgroup>
            <optgroup label="Personal services">
                <option value="dog">Dog walker</option>
                <option value="babysitter">Babysitter or nanny</option>
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
            <optgroup label="Rental services">
                <option value="survey">Survey taker</option>
                <option value="transcriptionist">Transcriptionist</option>
                <option value="virtual">Virtual assistant</option>
                <option value="proofreader">Proofreader</option>
                <option value="customer">Customer service representative</option>
                <option value="data">Data entry clerk</option>
            </optgroup>
        </select>
    </div>
    <div class="form-group">
        <label for="countryFilter" class="filter-label">Country:</label><br>
        <select id="countryFilter" name="countryFilter" class="filter-select"></select>
    </div>
    <div class="form-group">
        <label for="cityFilter" class="filter-label">City:</label><br>
        <input type="text" id="cityFilter" name="cityFilter" class="filter-input" placeholder="Enter a city name">
    </div>
    <div class="form-group">
        <label for="hourlyPaidFilter" class="filter-label">Hourly Paid:</label><br>
        <input type="text" id="hourlyPaidFilter" name="hourlyPaidFilter" class="filter-input" placeholder="Enter hourly paid">
    </div>
    <button type="submit" id="filter-button" class="filter-button">Filter</button>
</form>


<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div>';
        echo '<h2>' . $row['company'] . '</h2>';
        echo '<p>Location: ' . $row['city'] . ', ' . $row['country'] . '</p>';
        echo '<p>Domain: ' . $row['domain'] . '</p>';
        echo '<p>Duration: ' . $row['duration'] . '</p>';
        echo '<p>Description: ' . $row['description'] . '</p>';
        echo '<p>Hourly Paid: $' . $row['hourly_paid'] . '</p>';
        // Add a "Message" button to communicate with the employer
        echo '<br><a href="../../newchat/indexchat.php?userName='.$row['company'].'" target="_blank"><button id="chat">Message</button></a> ';
        echo '</div>';
    }
} else {
    echo "No interested jobs found.";
}
?>
</div>
<?php include './footer.php'; ?>
<script>
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
</script>
</body>
</html>
            
