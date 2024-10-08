<!-- editcalendarhtml.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Add Availability</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include './navBar.php'; ?>
    <div class="container">
        <h1>Add Availability</h1>
        <form action="save_availability.php" method="post">
            <label for="day">Day of the Week:</label>
            <select name="day" id="day">
                <option value="Sunday">Sunday</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
            </select><br><br>
            <label for="from">Available From:</label>
            <input type="time" name="from" id="from" value="07:00:00" >
            <label for="to">Available To:</label>
            <input type="time" name="to" id="to" value="07:00:00"><br><br>
            <input type="submit" value="Add Availability">
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
