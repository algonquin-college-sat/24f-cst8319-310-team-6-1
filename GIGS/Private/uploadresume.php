<?php
session_start();
require_once('./dbconnection.php');
$db = db_connect();
$username = $_SESSION['username'];
echo $username;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload2"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check file size
    if ($_FILES["fileToUpload2"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats (for resumes)
    if ($fileType != "pdf" && $fileType != "doc" && $fileType != "docx") {
        echo "Sorry, only PDF, DOC, and DOCX files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload2"]["tmp_name"], $target_file)) {
            $document = htmlspecialchars(basename($_FILES["fileToUpload2"]["name"]));
            $sql = "UPDATE account SET document='$document' WHERE userName='$username'";
            $result = $db->query($sql);
            echo '<script> window.location.replace("editprofile.php")</script>';
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload2"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

