<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page or display an error message
    header("Location: ../Public/login.php");
    exit();
}

// Handle form submission for verifying the code
if (isset($_POST['verify'])) {
    // Retrieve the verification code submitted by the user
    $enteredCode = $_POST['verification_code'];

    // Retrieve the stored verification code and user email from the database
    require_once(__DIR__ . '/../Private/dbconnection.php');
    $username = $_SESSION['username'];
    $db = db_connect();

    // Retrieve userEmail based on userName
    $userEmailSql = "SELECT userEmail FROM account WHERE userName = ?";
    $userEmailStmt = $db->prepare($userEmailSql);
    $userEmailStmt->bind_param("s", $username);
    $userEmailStmt->execute();
    $userEmailResult = $userEmailStmt->get_result();

    if ($userEmailResult->num_rows > 0) {
        $userEmailRow = $userEmailResult->fetch_assoc();
        $userEmail = $userEmailRow['userEmail'];

        // Retrieve the stored verification code from the user_verification table
        $verificationSql = "SELECT verificationCode FROM user_verification WHERE userName = ?";
        $verificationStmt = $db->prepare($verificationSql);
        $verificationStmt->bind_param("s", $username);
        $verificationStmt->execute();
        $verificationResult = $verificationStmt->get_result();

        if ($verificationResult->num_rows > 0) {
            $verificationRow = $verificationResult->fetch_assoc();
            $storedCode = $verificationRow['verificationCode'];

            // Check if the entered code matches the stored code
            if ($enteredCode == $storedCode) {
                // Update the user's verification status to 'yes' in the database
                $updateSql = "UPDATE user_verification SET verified = 'yes' WHERE userName = ?";
                $updateStmt = $db->prepare($updateSql);
                $updateStmt->bind_param("s", $username);
                $updateStmt->execute();

                // Verification successful message
                echo '<script>alert("Verification successful!");</script>';
                header("Location: ../Public/firstpage.php");
                exit();
            } else {
                // Incorrect verification code message
                echo '<script>alert("Incorrect verification code. Please try again.");</script>';
                header("Location: ../Public/firstpage.php");
                // Debugging: Output the entered code and stored code for inspection
                
            }
        } else {
            // No verification code found (should not happen if user received SMS)
            echo '<script>alert("No verification code found. Please try again later.");</script>';
            header("Location: ../Public/firstpage.php");
        }
    } else {
        // User not found based on username
        echo '<script>alert("User not found. Please try again later.");</script>';
        header("Location: ../Public/firstpage.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            color: #084d6a; /* DARK BLUE */
            margin-top: 50px;
        }

        form {
            width: 80%;
            margin: 0 auto;
            text-align: center;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        p {
            color: #084d6a; /* DARK BLUE */
            font-size: 18px;
        }

        input[type="text"] {
            width: 300px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            margin-bottom: 20px;
        }

        button[type="submit"] {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            background-color: #084d6a; /* DARK BLUE */
            color: #97d779; /* GREEN */
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #48bec5; /* LIGHT BLUE */
        }
    </style>
</head>
<body>
    <h2>Verify Your Account</h2>
    <form action="verification.php" method="post">
        <p>Enter the verification code received via SMS:</p>
        <input type="text" name="verification_code" required>
        <button type="submit" name="verify">Verify Account</button>
    </form>
</body>
</html>