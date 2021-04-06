<?php
    /*
                Panel.php

        Kat's configuration dashboard.
        Uses ares-api-v2
        Needs login.
    */
define("INC_CHECK", TRUE);
    
$pageName = "Dashboard";
include_once('includes/header.php');

Authorize(); // Needs Discord user session.


$actionFailed = False;  // When performing an action, did it fail?
$actionMessage = "";     // Error message for if action failed.
$clearedLevels = FALSE; // Did we clear the levels in the last action?



/// Iterates through all guilds user is in and checks if they are the owner.
/// Returns array(Guild)
function generateEditableGuilds($user)
{
    $ownedGuilds = array();
    $usrGuilds = apiRequest("https://discord.com/api/users/@me/guilds");

    foreach($usrGuilds as $i => $guild)
    {       
        if ($guild->owner)
        {
            array_push($ownedGuilds, $guild);
        }
    }
    return $ownedGuilds;
}


// Guilds that are usable in the dashboard.
$guilds = generateEditableGuilds(getUser());


function getGuildName($id)
{
    global $guilds;
    foreach($guilds as $guild)
    {
        if ($guild->id == $id)
        {
            return $guild->name;
        }
    }
}


/// Sets all Member XP and Levels to 0.
function clearAllMemberLevels()
{
    try
    {
        // Get all member ids
        $memberIds = aresGet("/guilds/" . $_GET['guild'] . "/members");
        
        $data = array(
            "xp" => 0,
            "level" => 0
        );

        foreach ($memberIds as $member) {
            aresPatch("/guilds/" . $_GET['guild'] . "/" . $member, json_encode($data));
        }

        $actionFailed = FALSE;
        $actionMessage = "Transaction completed.";
        $clearedLevels = TRUE;

        // $conn = getSQLConnection();
        // $conn->beginTransaction();
        // $query = 'UPDATE `level_data` SET `xp` = 1, `lvl` = 1 WHERE `guild_id` = "'.$_GET['guild'].'";';
        // $query = $conn->prepare($query);
        // $query->execute();
        // $conn->commit();
    }
    catch(Exception $e)
    {
        $actionFailed = TRUE;
        $actionMessage = $e->getMessage();
    }
}


function updateGuildSettings()
{
     /*
            Update guild settings with new ones from $_POST
            Items that need to update:
                action
                guild
                json
                levelRange
                levelFreeze
                prefixText

                adminRoles
                modRoles
        */

        // Fetch settings and build into JSON object.
        // We send the current settings as to not delete any that are stored and not 
        // updated by the dashboard.
        $json = json_decode($_POST['json']);

        // Update settings with new POST data.
        
        // Level settings
        $json->settings->level->xp_multi = (float) $_POST['levelRange'];
        $json->settings->level->freeze = isset($_POST['levelFreeze']);

        // Prefix
        $json->settings->prefix = $_POST['prefixText'];

        // Admin roles
        /* Server is currently using 32-bit PHP, so we are unable to store these as ints, so we store as strings in the dashboard side.*/
        $json->roles->moderators     =  json_decode('[' . $_POST['modRoles'] . ']'); 
        $json->roles->administrators =  json_decode('[' . $_POST['adminRoles'] . ']');

        try
        {
            $prefixJson = '{"settings": {}}';
            $prefixJson = json_decode($prefixJson);
            $prefixJson->settings = $json;

            echo json_encode($prefixJson);
            //TODO: Something here fucking breaks.
            /*
                Generating the JSON string works.
                somewhere between CURL and the API the body of the request
                gets set to nothing...
                API shows that request.json == None
                Sooo.... I dont know... It must be something with cURL.
                
                Current test URL: http://dev.reign-network.co.uk/panel.php
                API: PATCH http://api.reign-network.co.uk:5000/api/v2/guilds/438542169855361025
                Fuckin' maybe think about recreating this dashboard in fuckin Flask. goddamn i hate PHP
            */
            $o = aresPatch("/guilds/".$_POST['guild'], json_encode($prefixJson));
            
            var_dump($o);

            $actionMessage = "Settings updated.";
            $actionFailed = FALSE;

            $_GET['guild'] = $_POST['guild'];
        }
        catch(\Exception $e)
        {
            print_r($e);
            $actionFailed = TRUE;
            $actionMessage = $e->getMessage();
        }
}

// GET ACTIONS
if (isset($_GET['action']))
{
    if ($_GET['action'] == "clearlevels")
    {
        clearAllMemberLevels();
    }
}
// POST ACTIONS
if (isset($_POST['action']))
{

    if ($_POST['action'] == "update")
    {
        updateGuildSettings();
    }
}
?>

<div class="container-fluid pt-5">
    <div class="container">
        <?php
            if (isset($_POST['action']))
            {
                if ($_POST['action'] == "update")
                {
                    if (!$actionFailed)
                    {
                        echo '
                        <div class="alert alert-success" role="alert">
                            Successfully updated settings for <span class="alert-link">'.getGuildName($_POST['guild']).'</span>.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                        </div>';
                    }
                    else
                    {
                        echo '
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span class="text-danger" aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="alert-heading">Failed to update settings for '.getGuildName($_POST['guild']).'.</h4>
                            <hr>
                            <p class="mb-0">Something went wrong and we were unable to save your changes. Please try again, or if the error persists try later on.</p>
                        </div>';
                    } 
                }
                
            }

            if (isset($_POST['action']) && $_POST['action'] == "clearlevels" && $clearedLevels)
            {
                echo '
                <div class="alert alert-success" role="alert">
                        Successfully reset user levels for <span class="alert-link">'.getGuildName($_POST['guild']).'</span>.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span class="text-success" aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            }
        ?>
        
        <div class="dropdown pb-5">
            <a class="btn btn-secondary dropdown-toggle btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php
                    if (get('guild'))
                    {
                        echo getGuildName(get('guild'));
                    }
                    else
                    {
                        echo "Select a server.";
                    }
                ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <?php
                
                foreach($guilds as $guild)
                {
                    echo '<a class="dropdown-item" href="panel.php?guild='.$guild->id.'">'.getGuildName($guild->id).'</a>';
                }
            ?>
            </div>
            
            <a href=<?php if(get('guild')) {echo '"panel.php?guild='. get('guild').'"';} else {echo "panel.php";} ?> class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></a>

            <a
                href="https://discord.com/api/oauth2/authorize?client_id=379153719180394498&permissions=8&redirect_uri=https%3A%2F%2Fkat.reign-network.co.uk%2Fcommands.php&scope=bot">
                <button type="button-block" class="btn btn-sm color-discordblue mr-auto float-right">
                    Add to Discord
                </button>
            </a>

            <small class="text-muted">Dashboard only available for Kat.</small>

        </div>
        
        <?php 
            if (get('guild'))
            {
                $selected_guild_id = get('guild');
                
                include_once('includes/guildsettings.php');

            }
        ?>
    </div>
</div>

<script>
    var slider = document.getElementById("levelRange");
    var output = document.getElementById("levelRangeValue");
    output.innerHTML = slider.value + "x"; // Display the default slider value

    // Update the current slider value (each time you drag the slider handle)
    slider.oninput = function() {
    output.innerHTML = this.value  + "x";
        if (this.value == 0)
        {
            output.innerHTML = "No experience points will be rewarded";
        }

    }

    var prefix = document.getElementById("prefixText");
    var adminRoles = document.getElementById("adminRoles");
    var modRoles = document.getElementById("modRoles");

    var saveButtons = document.getElementsByClassName("saveButton");
    
    var isModValidated = false;
    var isAdminValidated = false;
    var isPrefixValidated = false;

    function checkValidation(mod, admin, prefixBool) {
        console.log("admin Validated: " + mod);
        console.log("mod Validated: " + admin);
        console.log("prefixbool Validated: " + prefixBool);

        if (mod) {modRoles.classList.remove("is-invalid"); modRoles.classList.add("is-valid");} else {modRoles.classList.add("is-invalid"); modRoles.classList.remove("is-valid");}
        if (admin) {adminRoles.classList.remove("is-invalid"); adminRoles.classList.add("is-valid");} else {adminRoles.classList.add("is-invalid"); adminRoles.classList.remove("is-valid");}
        if (prefixBool) {prefix.classList.remove("is-invalid"); prefix.classList.add("is-valid");} else { prefix.classList.add("is-invalid"); prefix.classList.remove("is-valid");}
        
        if (mod && admin && prefixBool)
        {
            for (i = 0; i < saveButtons.length; i++) {
                console.log("removing disabled attribute for: " + document.getElementsByClassName("saveButton")[i])
                document.getElementsByClassName("saveButton")[i].removeAttribute("disabled");
            }
        }
        else
        {
            for (i = 0; i < saveButtons.length; i++) {
                if (!document.getElementsByClassName("saveButton")[i].getAttribute("disabled"))
                {
                    console.log("setting disabled attribute for: " + document.getElementsByClassName("saveButton")[i])
                    document.getElementsByClassName("saveButton")[i].setAttribute("disabled","");
                }
            }
        }
    }


    function validatePrefix() {
        if (prefix.value == "" || prefix.value == "/" || prefix.value == "\\" || prefix.value == "`")
        {
            isPrefixValidated = false;
        }
        else
        {
            isPrefixValidated = true;
        }
        checkValidation(isModValidated, isAdminValidated, isPrefixValidated);
    }

    function validateAdmin()
    {
        if (!/^((?:[0-9]{18},?)*[0-9]{18})$/.test(adminRoles.value) && adminRoles.value.length >= 0)
        {
            isAdminValidated = false;
        }
        else 
        {
            isAdminValidated = true;
        }
        checkValidation(isModValidated, isAdminValidated, isPrefixValidated);
    }

    function validateMod()
    {
        if (!/^((?:[0-9]{18},?)*[0-9]{18})$/.test(modRoles.value) && modRoles.value.length >= 0)
        {
            isModValidated = false;
        }
        else 
        {
            isModValidated = true;
        }
        checkValidation(isModValidated, isAdminValidated, isPrefixValidated);
    }

    document.addEventListener("DOMContentLoaded", function(event) { 
        validatePrefix();
        validateMod();
        validateAdmin();

        checkValidation(isModValidated, isAdminValidated, isPrefixValidated);
    });
    
    
    prefix.onfocusout = function() {
        validatePrefix();
    }

    adminRoles.onfocusout = function() {
        validateAdmin();
    }
    
    modRoles.onfocusout = function() {
        validateMod();
    }
</script>
</body>

</html>