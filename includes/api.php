<?php
defined('INC_CHECK') || die('Direct access not permitted');
include_once("config.php");

$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/oauth2/token';
$revokeURL = "https://discord.com/api/oauth2/token/revoke";
$apiURLBase = 'https://discord.com/api/users/@me';

function aresGet($endpoint)
{
    global $config;

    $ch = curl_init($config['API_ROOT'] . $endpoint);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
    // Only accept JSON
    $headers[] = 'Accept: application/json';
    $headers[] = 'Authorization: ' . $config['API_AUTH_METHOD'] . $config['API_AUTH_CREDS'];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = json_decode(curl_exec($ch));
    curl_close($ch);
    return $result;
}

function aresPost($endpoint, $data)
{
    global $config;
    $ch = curl_init($config['API_ROOT'] . $endpoint);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    // Build POST fields
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

    // Only accept JSON
    $headers[] = 'Accept: application/json';
    $headers[] = 'Authorization: ' . $config['API_AUTH_METHOD'] . $config['API_AUTH_CREDS'];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = json_decode(curl_exec($ch));
    curl_close($ch);
    return $result;
}

function aresPatch($endpoint, $data)
{
    global $config;
    $ch = curl_init($config['API_ROOT'] . $endpoint);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Accept: application/json';
    $headers[] = 'Authorization: ' . $config['API_AUTH_METHOD'] . $config['API_AUTH_CREDS'];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = json_decode(curl_exec($ch));
    curl_close($ch);
    return $result;
}

function aresDelete($endpoint)
{
    $ch = curl_init($config['API_ROOT'] . $endpoint);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

    // Only accept JSON
    $headers[] = 'Accept: application/json';
    $headers[] = 'Authorization: ' . $config['API_AUTH_METHOD'] . $config['API_AUTH_CREDS'];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = json_decode(curl_exec($ch));
    curl_close($ch);
    return $result;
}

// Api Request to Discord
function apiRequest($url, $post=FALSE, $headers=array()) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $response = curl_exec($ch);


    if($post)
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

    $headers[] = 'Accept: application/json';

    if(session('access_token'))
        $headers[] = 'Authorization: Bearer ' . session('access_token');

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    $json = json_decode($response);
    if (get('message', "") == "401: Unauthorized")
    {
        session_unset();
        header("Location: index.php");
    }

    return json_decode($response);
}

function get($key, $default=NULL) {
    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

function session($key, $default=NULL) {
    return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}


?>
