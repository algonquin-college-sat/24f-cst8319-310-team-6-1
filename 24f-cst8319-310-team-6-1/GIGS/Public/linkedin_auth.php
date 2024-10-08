<?php
session_start();
require_once 'connect-db.php'; // Adjust the path as necessary

// Configuration - Replace these values with your own
$clientId = '77rvgqtlpnpjvz'; // Your LinkedIn App Client ID
$clientSecret = 'LKZY674IH7m4t10p'; // Your LinkedIn Client Secret
$redirectUri = 'http://localhost/24w-cst8319-300-team5/GIGS/Public/linkedin_auth.php'; // Your Redirect URI

// LinkedIn OpenID Connect endpoints
$authorizationEndpoint = 'https://www.linkedin.com/oauth/v2/authorization';
$tokenEndpoint = 'https://www.linkedin.com/oauth/v2/accessToken';
$userinfoEndpoint = 'https://api.linkedin.com/v2/userinfo';

$db = new DB(); // Instantiate your database class
if (empty($_GET['code']) && empty($_GET['error']) && !isset($_GET['direct'])) {
    echo <<<HTML
<script>
var state = Math.random().toString(36).substring(7); // Generate a simple state value
sessionStorage.setItem("inState", state); // Store state in session storage for verification later

var url = "{$authorizationEndpoint}?response_type=code&client_id={$clientId}&redirect_uri=" + encodeURIComponent("{$redirectUri}") + "&scope=" + encodeURIComponent("openid profile email") + "&state=" + state;
window.open(url, "LinkedIn Login", "width=800, height=600, left=200, top=100");
// window.location.href = "index.php"; // Redirect back to the main page or a specified location after opening LinkedIn login
</script>
HTML;
    exit;
}
// Requesting authorization code with necessary scopes
if (empty($_GET['code']) && empty($_GET['error'])) {
    $state = bin2hex(random_bytes(16)); // Generate a unique state value for CSRF protection
    $_SESSION['linkedin_oauth_state'] = $state; // Store the state value in session to verify later

    $params = http_build_query([
        'response_type' => 'code',
        'client_id' => $clientId,
        'redirect_uri' => $redirectUri,
        'scope' => 'openid profile email', // Requesting necessary scopes
        'state' => $state,

    ]);

    $authUrl = "$authorizationEndpoint?$params";
    header("Location: $authUrl");
    exit;
}

// Handle callback from LinkedIn
if (isset($_GET['code'])) {
    // Exchange authorization code for access token using cURL
    $code = $_GET['code'];
    $params = http_build_query([
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $redirectUri,
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
    ]);

    $ch = curl_init($tokenEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    $response = curl_exec($ch);
    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch) || $httpStatus != 200) {
        // Handle error; could log or echo the error message
        $error_msg = curl_error($ch);
        die("Error exchanging code for token: HTTP Status $httpStatus | Curl Error: $error_msg");
    }
    curl_close($ch);

    $tokenData = json_decode($response, true);
    if (!isset($tokenData['access_token'])) {
        die('Access token not found in the response.');
    }

    $accessToken = $tokenData['access_token'];

    // Fetch user information using access token
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "Authorization: Bearer $accessToken\r\n",
        ],
    ]);

    $userInfoResponse = file_get_contents($userinfoEndpoint, false, $context);
    if (!$userInfoResponse) {
        die('Error fetching user information.');
    }

    $userInfo = json_decode($userInfoResponse, true);

    // Store email address in session
    $_SESSION['linkedin_email'] = $userInfo['email'];

    // Redirect to reg_employer.php with email address as query parameter
    header("Location: reg_account.php?email=" . urlencode($userInfo['email']));
    exit;

} elseif (isset($_GET['error'])) {
    die('LinkedIn OAuth Error: ' . htmlspecialchars($_GET['error_description']));
}
?>