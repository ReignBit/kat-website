<?php 
    define("INC_CHECK", TRUE);
    $pageName = "About";
    include_once('includes/header.php');

    // TODO: Add status api request here.
    $request = NULL;
    if ($request != NULL)
    {
        // Get stats from file 
        // TODO: Expose these through API
        $stats = file_get_contents($_SERVER['DOCUMENT_ROOT']."/kat_command_helps/kat_stats");
        $guildCount = str_getcsv($stats, ",")[0];
        $userCount = str_getcsv($stats, ",")[1];
        $katStatus = json_decode($request,true)['data'][0]['status'];
    }
    else
    {
        // Kat is not online.
        $guildCount = "???";
        $userCount = "???";
        $katStatus = 0;
    }
    
    // DEV KAT INSTANCE STATUSES
    $yumiRequest = file_get_contents("/kat_command_helps/yumi_status");
    $yumiStatus = str_getcsv($yumiRequest, ",")[0];
    if ($yumiStatus == "") {$yumiStatus = "Offline";}
    $yumiguildCount = str_getcsv($yumiRequest, ",")[1];
    if ($yumiguildCount == "") {$yumiguildCount = "???";}
    $yumiuserCount = str_getcsv($yumiRequest, ",")[2];
    if ($yumiuserCount == "") {$yumiuserCount = "???";}

    if ($katStatus){ $katStatus = "Online"; $showAlert = "style='display:none;'";} else { $katStatus = "Offline"; $showAlert = "";}
?>
    <div class="container mr-auto">
        <div class="mt-5">
            <!-- Kat offline banner -->
            <!--
            <div class="alert alert-warning" role="alert" <?php echo $showAlert;?>>
                Kat is currently undergoing maintenance and is offline. Check <code>#announcements</code> in the Kat Support Discord Server for updates.
            </div>
            -->
            <ul class="list-group list-group-dark">
                <li class="list-group-item bg-dark">
                    <h4><i class="fas fa-chart-bar mr-2"></i> Current Statistics</h4>
                </li>
                <li class="list-group-item color-discorddark pb-0">
                    
                    <!-- Kat & Yumi status banner -->
                    <p class="mx-auto px-auto">Kat is <code><?php echo $katStatus; ?></code> and has joined <code><?php echo $guildCount; ?></code> servers, with a total of
                        <code><?php echo $userCount; ?></code> users.<hr>
                    Yumi is <code><?php echo $yumiStatus; ?></code> and has joined <code><?php echo $yumiguildCount; ?></code> servers, with a total of
                    <code><?php echo $yumiuserCount; ?></code> users.</p>
                </li>
            </ul>



        </div>

        <div class="ml">
            <h1 class="display-4 pt-5">
                A Discord bot with personality.
            </h1>
            <p class="lead">Invite Kat to your server and start customizing to your liking with the command
                <code>$config</code>, or use our web-based dashboard instead!</p>
            <a
                href="https://discord.com/api/oauth2/authorize?client_id=379153719180394498&permissions=8&redirect_uri=https%3A%2F%2Fkat.reign-network.co.uk%2Fcommands.php&scope=bot">
                <button type="button-block" class="btn btn-lg color-discordblue mr-auto">
                    Add to Discord
                </button>
            </a>
        </div>

        <div class="mr">
            <h1 class="display-4 pt-5">
                Stay up to date.
            </h1>
            <p class="lead">Interested in the development of Kat, or need to ask a question?<br>
                Join our support server, or visit the Github page and post an issue!</p>
            <div>

                <a href="https://discord.gg/VFBy7faTPP" class="mr-auto">
                    <button type="button-block" class="btn btn-lg color-discordblue mr-auto">
                        Join Kat's Server
                    </button>
                </a>
                <a href="https://github.com/ReignBit/discord-kat/issues/" class="mr-auto">
                    <button type="button-block" class="btn btn-lg color-discordblue">
                        <i class="fab fa-github"></i> Submit an issue
                    </button>
                </a>
            </div>
        </div>
        <div class="mr">
            <h1 class="display-4 pt-5">
                Help squash them bugs.
            </h1>
            <p class="lead">Become a bughunter and invite Yumi!<br>
                Yumi is the testing branch version of Kat. Gain access to features early, and help report bugs before they reach the main branch.</p>
                <small class="text-muted">Yumi's availability varies and is not guaranteed to be online 24/7. If you want a stable experience, invite Kat instead.</small>
            <div style="padding-bottom: 60px;">
                <a href="https://discord.com/api/oauth2/authorize?client_id=582014502405537792&permissions=8&redirect_uri=https%3A%2F%2Fkat.reign-network.co.uk%2Fcommands.php&scope=bot" class="mr-auto">
                    <button type="button-block" class="btn btn-secondary btn-lg mr-auto">
                        <i class="fas fa-bug text-dark"></i> Invite Yumi
                    </button>
                </a>
            </div>
        </div>

        
    <small class="text-muted text-center">Kat, Yumi, and this website are not affiliated with Discord or its partners.</small>
    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>
    
    </body>

</html>