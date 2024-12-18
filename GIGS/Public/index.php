<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style_index.css" />
    <link rel="stylesheet" href="../css/style_firstPage.css" />
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <link rel="icon" href="icon/Picture4.ico" />
    <script src="../js/registration.js" defer></script>
    <title>Welcome to GIGS!</title>

</head>

<body>
    <?php include '../Private/navBar.php'; ?>

    <div class="main_login">
        <div class="left_login">
            <div class="typewriter">
                <!--Added the Logo to the index page
-->
                <h1><img class="logo" src="../icon/FLEXYGIG_for light background.png"></h1>
            </div>
            <h1> Connecting Gig workers to employers</h1>
            <h2>A platform that connects gig workers with <br>employers with temporary job opportunities.</h2>
        </div>

        <form action="../Private/login_verify.php" id="login" method="POST">


            <div class="right_login">
                <div class="card_login">
                    <h1>GIGS</h1>
                    <div class="textfield">
                        <label for="username">Username</label>
                        <input type="text" name="username" placeholder="Username">
                    </div>
                    <div class="textfield">
                        <label for="password">Password</label>
                        <input type="password" name="password" placeholder="Password">
                    </div>

                    <button class="button_login" type="submit">Sign In</button>

                    <a href="#" onclick="openLinkedInPopup(); return false;" style="display: inline-block; background-color: #0077B5; color: white; padding: 10px 24px; border-radius: 4px; font-size: 16px; text-align: center; cursor: pointer; text-decoration: none; font-family: 'Quicksand', sans-serif; box-shadow: 0 2px 4px 0 rgba(0,0,0,.25); margin:30px">
                        <img src="http://localhost/24wcst8319projectFinal/GIGS/icon/linkedinLogo.jpg" alt="LinkedIn Logo" style="vertical-align: middle; margin-right: 5px; width: 20px; height: 20px; filter: none;">Sign in with LinkedIn
                    </a>


                    <div id="signInDiv"></div>
                    <div class="text_create">
                        Don't have an account?
                    </div>

                    <div class="text_create">
                        <a href="reg_account.php"> Create New Account</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        window.onload = function () {
            google.accounts.id.initialize({
                client_id: '1086471529531-mbq3tfvj2a48kfejm12pu9e3gk0m6pvv.apps.googleusercontent.com', // Replace with your actual client ID
                callback: handleCredentialResponse
            });
            google.accounts.id.renderButton(
                document.getElementById('signInDiv'), // The div where Google Sign-In button will be rendered
                { theme: "outline", size: "large" }  // Button customization
            );
        };

        function handleCredentialResponse(response) {
            var data = parseJwt(response.credential);
            var email = data.email;
            var name = data.name;
            // Redirect to reg_account.php with email and company_name (use username or name based on the ID token content)
            window.location.href = `../Private/login_verify.php?email=${encodeURIComponent(email)}&name=${encodeURIComponent(name)}`;
        }

        // Helper function to parse JWT ID tokens
        function parseJwt(token) {
            var base64Url = token.split('.')[1];
            var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
            var jsonPayload = decodeURIComponent(atob(base64).split('').map(function (c) {
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join(''));

            return JSON.parse(jsonPayload);
        }

        function openLinkedInPopup() {
            const popup = window.location.href = 'linkedin_auth.php';

            
            // Listen for a message from linkedin_auth.php to handle successful login
            window.addEventListener('message', function(event) {
                if (event.origin !== window.location.origin) return; // Security check
                
                // On successful login, event.data should contain the email or any other necessary info
                if (event.data === 'linkedin-success') {
                    window.location.href = 'reg_account.php';
                }
            });
        }
    </script>
</body>

</html>