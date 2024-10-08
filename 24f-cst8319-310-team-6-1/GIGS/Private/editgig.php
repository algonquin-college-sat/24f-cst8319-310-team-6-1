<?php
session_start();
require_once('./dbconnection.php');

$db = db_connect();

// Check if the form is submitted
if (isset($_POST['update'])) {
    // Retrieve form data
    $id = $_POST['id'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $domain = $_POST['domain'];
    $company = $_POST['company'];
    $duration = $_POST['duration'];
    $description = $_POST['description'];
    $hourlyPaid = $_POST['hourly'];

    // Update the gig in the database
    $sql = "UPDATE gigs SET country='$country', city='$city', domain='$domain', company='$company', duration='$duration', description='$description', hourly_paid='$hourlyPaid' WHERE id='$id'";
    $result = $db->query($sql);

    if ($result) {
        // Set session variables for notification
        $_SESSION['notification_message'] = "Gig updated successfully!";
        $_SESSION['notification_type'] = "edit_gig";
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '&success=true&notification_type=edit_gig');
        exit();
    } else {
        echo "Error updating the gig: " . $db->error;
    }
} else {
    // Fetch the gig data based on the ID
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM gigs WHERE id='$id'";
        $result = $db->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $country = $row['country'];
            $city = $row['city'];
            $domain = $row['domain'];
            $company = $row['company'];
            $duration = $row['duration'];
            $description = $row['description'];
            $hourlyPaid = $row['hourly_paid'];
        } else {
            echo "Gig not found.";
            exit();
        }
    } else {
        echo "Gig ID not provided.";
        exit();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="icon/Picture4.ico"/>
    <title>Edit Gig</title>
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
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .country-dropdown{
            height: 32px;
        }

        .description{
            height: 100px;
        }
        .notification {
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            display: none;
            z-index: 9999;
        }
    </style>
</head>
<body>
    <?php include 'navBar.php'; ?>
    <?php if (isset($_SESSION['notification_message']) && isset($_SESSION['notification_type'])): ?>
<div id="notification" class="notification">
    <?php echo $_SESSION['notification_message']; ?>
</div>
<script>
    // Display the notification
    document.getElementById("notification").style.display = "block";
    setTimeout(function() {
        document.getElementById("notification").style.display = "none";
    }, 3000);
    <?php unset($_SESSION['notification_message'], $_SESSION['notification_type']); ?>
</script>
<?php
endif; ?>
   
    <div class="container">
        <h1>Edit Gig</h1>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <label for="country">Country:</label>
        <select id="country-dropdown" class="country-dropdown" name="country" required>
            <option value="" disabled>Select a Country...</option>
        </select>
<script>
    // Fetch the country data from the API
    fetch('https://restcountries.com/v3.1/all')
        .then(response => response.json())
        .then(data => {
            const countryDropdownElement = document.getElementById('country-dropdown');
        
            // Sort the country names alphabetically
            const sortedCountries = data
                .map(country => country.name.common)
                .sort();
        
            // Move Canada to the front
            const canadaIndex = sortedCountries.indexOf('Canada');
            if (canadaIndex !== -1) {
                sortedCountries.splice(canadaIndex, 1);
                sortedCountries.unshift('Canada');
            }
        
            sortedCountries.forEach(countryName => {
                const optionElement = document.createElement('option');
                optionElement.textContent = countryName;
                optionElement.value = countryName;
                countryDropdownElement.appendChild(optionElement);
            });
        })
        .catch(error => console.error('Error:', error));
</script>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" value="<?php echo $company; ?>" required>

            <label for="domain">Domain:</label>
<select name="domain" id="domain" class="country-dropdown" required>
    <option value="" disabled>Select a Domain...</option>
    <?php
    // Array of domain options
    $domainOptions = [
        "Transportation and delivery services" => ["package", "food", "grocery", "bicycle", "ride"],
        "Personal services" => ["dog", "Babysitter", "home", "tutor", "massage", "telehealth"],
        "On-demand skilled work" => ["photographer", "graphic", "content", "web", "editor", "consultant", "translator"],
        "Home services" => ["handyperson", "mover", "house", "housekeeper", "cook", "lawn"],
        "Internet-based gigs" => ["survey", "transcriptionist", "virtual", "proofreader", "customer", "data"],
        "Rental services" => ["survey", "transcriptionist", "virtual", "proofreader", "customer", "data"]
    ];

    // Iterate through domain options to populate and pre-select
    foreach ($domainOptions as $groupLabel => $options) {
        echo "<optgroup label='$groupLabel'>";
        foreach ($options as $option) {
            $selected = ($option == $domain) ? 'selected' : ''; // Check if option matches fetched domain
            echo "<option value='$option' $selected>$option</option>";
        }
        echo "</optgroup>";
    }
    ?>
</select>

            
            <label for="company">Company Name:</label>
            <input type="text" id="company" name="company" value="<?php echo $company; ?>" required>

            <label for="duration">Duration:</label>
            <input type="text" id="duration" name="duration" value="<?php echo $duration; ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" class="description" required><?php echo $description; ?></textarea>

            <label for="hourly">Hourly Paid:</label>
            <input type="text" id="hourly" name="hourly" value="<?php echo $hourlyPaid; ?>" required>
            <br>
            <input type="submit" value="Update Gig" name="update" style="font-size: 20px; ">
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
