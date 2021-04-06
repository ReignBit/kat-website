<?php

/// Ensures we have a Discord session.
/// Redirects to index.php otherwise.
function Authorize()
{
    if (!isset($_SESSION['access_token']))
    {
        header("Location: index.php");
    }
}

/// Fetches user data from Discord if we have an access token.
function getUser()
{
    if (isset($_SESSION['access_token']))
    {
        return apiRequest("https://discord.com/api/users/@me");
    }
}
?>