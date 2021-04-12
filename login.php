<?php 
define("INC_CHECK", TRUE);
$pageName = "Login";
include_once('includes/header.php');
    

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 60); //300 seconds = 5 minutes. In case if your CURL is slow and is loading too much (Can be IPv6 problem)

error_reporting(E_ALL);

include_once('includes/api.php');

// Start the login process by sending the user to Discord's authorization page
if(get('action') == 'login') {

  $params = array(
    'client_id' => OAUTH2_CLIENT_ID,
    'redirect_uri' => $config['DISCORD_OAUTH_LOGIN_REDIRECT_URL'],
    'response_type' => 'code',
    'scope' => 'identify guilds'
  );

  // Redirect the user to Discord's authorization page
  header('Location: https://discordapp.com/api/oauth2/authorize' . '?' . http_build_query($params));
  die();
}


// When Discord redirects the user back here, there will be a "code" and "state" parameter in the query string
if(get('code')) {

  // Exchange the auth code for a token
  $token = apiRequest($tokenURL, array(
    "grant_type" => "authorization_code",
    'client_id' => OAUTH2_CLIENT_ID,
    'client_secret' => OAUTH2_CLIENT_SECRET,
    'redirect_uri' => $config['DISCORD_OAUTH_LOGIN_REDIRECT_URL'],
    'code' => get('code')
  ));
  $logout_token = $token->access_token;
  $_SESSION['access_token'] = $token->access_token;


  header('Location: panel.php');
}

if(get('action') == 'logout') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $revokeURL);
    curl_setopt ($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, OAUTH2_CLIENT_ID . ":". OAUTH2_CLIENT_SECRET);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, "token=".session('access_token')); 
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $output = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    print_r($output);
    if ($output == "{}")
    {
      session_unset();
      echo "<script>window.location.href='panel.php';</script>";
      exit;
    }
    else{
      die("something went wrong whilst logging you out." . $output);
    }
}
?>