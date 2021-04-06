<?php 
ob_start(); // Stop content being sent until php finished. (Allows for Header redirects later down the code.)
ini_set('display_errors', 'On'); // Disable errors being shown on the webpage. 
defined('INC_CHECK') || die('Direct access not permitted'); // Disallow direct access to file.
session_start();    // Start PHP session.

include_once('includes/api.php');   // API functions and stuff.
include_once('includes/common.php') // Common functions and things used across most pages.
?>

<!doctype html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="https://cdn.discordapp.com/app-icons/379153719180394498/596f1b2abbc450c9bfc7f3e51728928c.png"/>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <script src="https://kit.fontawesome.com/66f1f5f520.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>

    <title>Kat DEV | <?php echo $pageName;?></title>

    
</head>

<body class="color-discorddark" style="width:100%">
    <!-- Header bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a href="index.php">	
			<img class="navbar-brand" style="border-radius:50%;width:60px;"
				src="https://cdn.discordapp.com/app-icons/379153719180394498/596f1b2abbc450c9bfc7f3e51728928c.jpg">
		</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link <?php if($pageName == "About") {echo "active";} ?>" href="/index.php">About<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        href="https://discord.com/api/oauth2/authorize?client_id=379153719180394498&permissions=8&redirect_uri=https%3A%2F%2Fkat.reign-network.co.uk%2Fcommands.php&scope=bot">
                        Invite</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if($pageName == "Commands") {echo "active";} ?>" href="/commands.php">Commands</a>
				</li>
				<li class="nav-item float-right">
                    <a class="nav-link" href="https://github.com/ReignBit/discord-kat">Github</a>
                </li>
            </ul>
            <ul class="navbar-nav mr">
                <!-- Login / User name Section -->
                <li class="nav-item">
					<!-- Logged in -->
                    <?php 
					if (!isset($_SESSION['access_token']))
					{
						echo '<a class="nav-link" href="#login" data-toggle="modal" data-target="#login">Login</a>';
					}
					else
					{
						$user = getUser();
						echo '
						<a class="nav-link active" href="panel.php">'.$user->username.'#'.$user->discriminator.'</a>';
					}
					?>
				</li>
                    <!-- Logged out -->
					<?php
					if (isset($_SESSION['access_token']))
					{
						echo '<li class="nav-item"> <a class="nav-link" href="login.php?action=logout">Logout</a></li>';
					}
					?>
            </ul>

        </div>
    </nav>

    <!-- Modal -->
    <div class="modal fade rounded-1" id="login" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog bg-dark rounded-1">
            <div class="modal-content bg-dark rounded-1">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body bg-dark">
                    <p>In order to use the web-based interface, please login with discord.</p>
					<a href="login.php?action=login">
						<button type="button" class="btn color-discordblue text-white text-center">
							<i class="fab fa-discord"></i> Login with Discord
						</button>
					</a>
                </div>
            </div>
        </div>
    </div>

    <!-- API Warning Banner-->
    <div class="alert alert-warning" role="alert">
        > We are in the process of migrating to our new v2 api.
        Due to this, the configuration dashboard and statistics are currently unavailable.
        Kat is still able to be configured using the <code>$config</code> command in your Discord Server!
    </div>

    