<?php

include('./dbconnection.php');
require __DIR__ . '/../../../autoload.php';
use Twilio\Rest\Client;

$db = db_connect();

$result = array();

$message = isset($_POST['message']) ? $_POST['message'] : null;
$from = isset($_POST['from']) ? $_POST['from'] : null;
$toUser = isset($_POST['toUser']) ? $_POST['toUser'] : null;

if (!empty($message) && !empty($from) && !empty($toUser)) {
    // Insert the new message with seen status set to 0 (unseen)
    $sql = "INSERT INTO `newchat` (`message`, `from`, `toUser`, `seen`) VALUES ('$message', '$from', '$toUser', 0)";
    if ($db->query($sql) === TRUE) {
        $result['send_status'] = "Message sent successfully";

        // Check if there are any unseen messages for the recipient
        $checkUnseenQuery = "SELECT COUNT(*) AS unseen_count FROM newchat WHERE toUser = ? AND seen = 0";
        $stmt = $db->prepare($checkUnseenQuery);
        $stmt->bind_param("s", $toUser);
        $stmt->execute();
        $unseenResult = $stmt->get_result();
        $unseenRow = $unseenResult->fetch_assoc();

        if ($unseenRow['unseen_count'] > 0) {
            // Fetch the recipient's email address
            $accountQuery = "SELECT userEmail, userType, employertKey, gigworkertKey FROM account WHERE userName = ?";
            $stmt = $db->prepare($accountQuery);
            $stmt->bind_param("s", $toUser);
            $stmt->execute();
            $accountResult = $stmt->get_result();

            if ($accountResult->num_rows > 0) {
                $accountRow = $accountResult->fetch_assoc();
                $recipientEmail = $accountRow['userEmail'];
                $userType = $accountRow['userType'];
                $recipientPhone = null;

                // Fetch the recipient's phone number based on userType
                if ($userType === 'e') {
                    // If user is an employer, fetch phone from employert table
                    $employerQuery = "SELECT phone FROM employert WHERE id = ?";
                    $stmt = $db->prepare($employerQuery);
                    $stmt->bind_param("i", $accountRow['employertKey']);
                    $stmt->execute();
                    $employerResult = $stmt->get_result();
                    if ($employerResult->num_rows > 0) {
                        $employerRow = $employerResult->fetch_assoc();
                        $recipientPhone = $employerRow['phone'];
                    }
                } elseif ($userType === 'w') {
                    // If user is a gig worker, fetch phone from gigworkert table
                    $workerQuery = "SELECT phone FROM gigworkert WHERE id = ?";
                    $stmt = $db->prepare($workerQuery);
                    $stmt->bind_param("i", $accountRow['gigworkertKey']);
                    $stmt->execute();
                    $workerResult = $stmt->get_result();
                    if ($workerResult->num_rows > 0) {
                        $workerRow = $workerResult->fetch_assoc();
                        $recipientPhone = $workerRow['phone'];
                    }
                }


                // Send the email notification
                $subject = "New Message from $from on FLEXYGIG";
                $messageBody = "You have received a new message from $from. Please log in to your account to view the message.";
                $headers = "From: no-reply@FLEXYGIG.com\r\n";
                $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

                // Send the email
                if (mail($recipientEmail, $subject, $messageBody, $headers)) {
                    $result['email_status'] = "Email Notification sent successfully";
                } else {
                    $result['email_status'] = "Failed to send email notification";
                }

                // Send the SMS notification using Twilio
                if (!empty($recipientPhone)) {
                    $sid = $_ENV["TWILIO_SID"];
                    $token = $_ENV["TWILIO_TOKEN"];
                    $twilio = new Client($sid, $token);

                    $smsBody = "You have a new message from $from on FLEXYGIG. Please log in to view it.";

                    try {
                        $message = $twilio->messages->create(
                            $recipientPhone, // Recipient phone number
                            [
                                "from" => "+14243321496", // Your Twilio number
                                "body" => $smsBody
                            ]
                        );
                        $result['sms_status'] = "SMS Notification sent successfully";
                    } catch (Exception $e) {
                        $result['sms_status'] = "Failed to send SMS: " . $e->getMessage();
                    }
                }
            }
            $stmt->close();
        }
    } else {
        $result['send_status'] = "Error: " . $db->error;
    }
}

// Fetch new messages based on the start ID
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$from2 = isset($_GET['from2']) ? $_GET['from2'] : null;
$toUser2 = isset($_GET['toUser2']) ? $_GET['toUser2'] : null;
$sql = "SELECT * FROM `newchat` WHERE id > $start AND ((`from` = '$from2' AND `toUser` = '$toUser2') OR (`from` = '$toUser2' AND `toUser` = '$from2'))";
$items = $db->query($sql);

while ($row = $items->fetch_assoc()) {
    $result['items'][] = $row;
}

$db->close();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

echo json_encode($result);

?>
