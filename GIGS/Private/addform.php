<?php
   session_start();
   require_once('./dbconnection.php');

   $db = db_connect();


   // Check if the form is submitted
   if (isset($_POST['insert'])) {
    // Retrieve form data
    $country = $_POST['country'];
    $city = $_POST['city'];
    $domain = $_POST['domain'];
    $company = $_POST['company'];
    $duration = $_POST['duration'];
    $description = $_POST['description'];
    $hourlyPaid = $_POST['hourly'];

    // Insert the gig into the database
    $sql = "INSERT INTO gigs (country, city, domain, company, duration, description, hourly_paid)
            VALUES ('$country', '$city', '$domain', '$company', '$duration', '$description', '$hourlyPaid')";

    $result = $db->query($sql);

if ($result) {
        // Set session variables for notification
        $_SESSION['notification_message'] = "New gig added successfully!";
        $_SESSION['notification_type'] = "new_gig";
        //header('Location: ' . $_SERVER['HTTP_REFERER'] . '?success=true&notification_type=new_gig');
        header('Location: ' . "../Private/displayjob.php");
        exit();
    } else {
        echo "Error adding the gig: " . $db->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <link rel="icon" href="icon/Picture4.ico"/>
    <title>Add Gig Form</title>
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
        <h1>Add New Gig</h1>
        <form action="" method="POST">
            <label for="country">Country:</label>
            <select id="country-dropdown" class="country-dropdown" name="country"></select>
  
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
            </script><br>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>

            <label for="domain">Domain:</label>
            <select name="domain" id="domain" class="country-dropdown">
                <optgroup label="Select a Domain...">
                <option value="none"></option>
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
                <optgroup label="Rental services">
                    <option value="survey">Survey taker</option>
                    <option value="transcriptionist">Transcriptionist</option>
                    <option value="virtual">Virtual assistant</option>
                    <option value="proofreader">Proofreader</option>
                    <option value="customer">Customer service representative</option>
                    <option value="data">Data entry clerk</option>
                </optgroup>
            </select required><br>
            

            <label for="company">Company Name:</label>
            <input type="text" id="company" name="company" required>

            <label for="duration">Duration:</label>
            <input type="text" id="duration" name="duration" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" class="description" required></textarea>

            <label for="hourly">Hourly Paid:</label>
            <input type="text" id="hourly" name="hourly" required>
            <br>
            <input type="submit" value="Add Gig" name="insert" style="font-size: 20px; ">
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
