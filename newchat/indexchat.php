<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Neither user is logged in
    // Redirect to the login page
    header("Location: ../GIGS/Public/index.php");
    exit; // Stop execution after redirect
}

// User is logged in
$name = $_SESSION['username'];
$toUser = "Gig Worker"; // Default value
if (isset($_GET['userName'])) {
    // Set the value of $toUser if provided in the URL
    $toUser = $_GET['userName'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="icon/Picture4.ico" />
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300&family=Courgette&display=swap" rel="stylesheet">

    <title>FLEXYGIG Chat</title>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            font-family: 'Quicksand', sans-serif;
            font-size: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-image: url('picture/transparency_logo.jpg');
            background-size: cover;
            background-position: center;
        }

        #profile {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            /* Add margin to separate from the chat box */
        }

        #profile img {
            width: 100px;
            /* Adjust size as needed */
            height: 100px;
            /* Adjust size as needed */
            border-radius: 50%;
            /* Make it round */
            border: 2px solid #fff;
            /* Add border */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            /* Add shadow */
        }

        #chat-box {
            width: 600px;
            /* Adjust width as needed */
            height: 400px;
            /* Specify fixed height */
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white background */
            border-radius: 50px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            /* Enable vertical scrolling */
        }

        #messages {
            margin-bottom: 20px;
        }

        form {
            display: flex;
            margin-top: 10px;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            /* Glowing shadow effect */
        }

        input {
            flex: 1;
            font-size: 1.2rem;
            padding: 10px;
            margin-right: 10px;
            border: 2px solid #97D779;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0, 0, 255, 0.3);
            /* Glowing shadow effect */
        }

        #send {
            padding: 10px 20px;
            background-color: #97D779;
            color: #084D6A;
            font-weight: 800;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 0 20px rgba(0, 0, 255, 0.3);
            /* Glowing shadow effect */
        }

        .msg {
            background-color: #084D6A;
            color: #F0F1B7;
            padding: 10px;
            border-radius: 25px;
            margin-bottom: 10px;
            display: block;
        }

        .msg p {
            margin: 0;
            font-weight: bold;
            color: #fff;
        }

        .msg span {
            font-size: 0.8rem;
            color: #97D779;
            margin-left: 10px;
        }

        .selected {
            background-color: #48BEC5;
            /* Change background color for selected messages */
        }

        #deleteBtn {
            padding: 10px 20px;
            margin-top: 10px;
            background-color: #ff6b6b;
            /* Change to desired color */
            color: #fff;
            font-weight: 800;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 0 20px rgba(255, 107, 107, 0.3);
            /* Adjust color and shadow as needed */
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <div id="profile">
        <!-- Placeholder for profile photo -->
        <img src="picture/profil.jpg" alt="Profile Photo">
    </div>
    <div id="chat-box">
        <div id="messages"></div>
    </div>
    <form id="messageForm">
        <input type="text" id="message" autocomplete="off" autofocus placeholder="Type your message...">
        <button type="submit" id="send">Send</button>
    </form>
    <div id="delete">
        <button type="button" id="deleteBtn" class="hidden">Delete</button>
    </div>

    <script>
        $(document).ready(function() {
            var from = "<?php echo $name; ?>";
            var toUser = "<?php echo $toUser; ?>";
            var start = 0;
            var url = 'http://localhost/24wcst8319projectFinal/newchat/TESTE/newchat/newchat.php';

            loadMessages();

            $('#messageForm').submit(function(e) {
                e.preventDefault();
                var message = $('#message').val();
                if (message.trim() !== "") { // Check that the message is not empty
                sendMessage(message);
            }
            });

            function sendMessage(message) {
                $('#message').val('');
                $.post(url, {
                    message: message,
                    from: from,
                    toUser: toUser
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Message send failed: ", textStatus, errorThrown);
                });
            }

            function loadMessages() {
                $.get(url + '?start=' + start + '&from2=' + from + '&toUser2=' + toUser, function(result) {
                    if (result.items) {
                        result.items.forEach(function(item) {
                            start = item.id;
                            $('#messages').append(renderMessage(item));
                        });
                        $('#messages').animate({
                            scrollTop: $('#messages')[0].scrollHeight
                        });
                    }
                    // Mark messages as seen
                    //$.post('update_seen_status.php', { from: toUser, toUser: from });
                    // Only call update_seen_status.php when there are new messages
                    $.post('update_seen_status.php', { from: toUser, toUser: from }, function(response) {
                        console.log("Seen status update response:", response); // For debugging
                    }, 'json');
                    setTimeout(loadMessages, 2000); // Adjust the delay as needed (e.g., 2000ms or 2 seconds)
                });
            }

            function renderMessage(item) {
                const timestamp = new Date();
                const formattedTimestamp = timestamp.toLocaleTimeString().toLowerCase();
                return '<div class="msg" data-id="' + item.id + '"><p>' + item.from + '</p>' + item.message + '<span>' + formattedTimestamp + '</span></div>';
            }
        });
    </script>
</body>

</html>
