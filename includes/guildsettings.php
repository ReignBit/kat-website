<?php
defined('INC_CHECK') || die('Direct access not permitted');

    try
    {
        $data = aresGet("/guilds/".get("guild"));

        if ($data == NULL)
        {
            throw new PDOException("Failed to connect to API");
        }

        $settings = $data->data[0]->settings;
    }
    catch (PDOException $e)
    {
        die('<div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span class="text-danger" aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="alert-heading">Unable to process request.</h4>
                            <hr>
                            <p class="mb-0 text-danger">Something went wrong and we are unable to process your request. Please try again; if the error persists, please try later on.</p>
                        </div>');
    }    
?>

<div class="modal fade" id="clearModal" tabindex="-1" aria-labelledby="clearModelLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-dark">
      <div class="modal-header">
        <h5 class="modal-title" id="clearModelLabel">Reset User Levels</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        This will irreversibly reset all user levels and experience points to 0. <br/>
        Are you sure?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a href=<?php echo '"panel.php?guild='.get('guild').'&action=clearlevels"'?> class="btn btn-danger">Do it!</a>
      </div>
    </div>
  </div>
</div>


<form action="panel.php" method="post">
    <input type="hidden" name="guild" value=<?php echo '"'.get('guild').'"';?>/>
    <input type="hidden" name="action" value="update"/>
    <input type="hidden" name="json" value=<?php echo "'".json_encode($settings)."'";?>>

    <div class="cog-container ml-0">
        <ul class="list-group list-group-dark mr-auto  mt-3" style="border-radius:10%;" href="#fun">
            <a class="no-hover" style="text-decoration:none;" data-toggle="collapse" href="#collaspeSettingData" role="button">
                <li class="list-group-item list-group-item-dark color-discordblue">
                <h3>General Settings</h3>
                </li>
            </a>
            <div class="collapse show" id="collaspeSettingData"> 
                <li class="list-group-item bg-dark">
                    <div class="slider-container form-group">
                        <label for="prefixText" class="list-item">Command Prefix</label>
                        <input type="text" value=<?php echo '"'.$settings->settings->prefix.'"';   ?> class="text form-control" required name="prefixText" id="prefixText">
                    </div>


                    <input type="submit" class="btn btn-success saveButton" value="Save">
                </li>
            </div>
        </ul>
    </div>

    <div class="cog-container ml-0">
        <ul class="list-group list-group-dark mr-auto mt-3" style="border-radius:10%;" href="#fun">
            <a class="no-hover" style="text-decoration:none;" data-toggle="collapse" href="#collaspeLevelData" role="button">
                <li class="list-group-item list-group-item-dark color-discordblue">
                <h3>Level Settings</h3>
                </li>
            </a>
            <div class="collapse show" id="collaspeLevelData"> 
                <li class="list-group-item bg-dark pb-0">
                    <div class="setting-container form-group ml-3 ">

                        <div class="custom-control custom-switch custom-control-inline">
                            <input type="checkbox" class="custom-control-input" <?php if($settings->settings->level->freeze){echo 'checked';}?> name="levelFreeze" id="levelFreezeDisable">
                            <label class="custom-control-label" for="levelFreezeDisable">Freeze Levels</label>
                        </div>
                        <a class="btn btn-danger btn-sm ml-5" data-toggle="modal" data-target="#clearModal"><i class="fas fa-exclamation-triangle"></i> Clear Leaderboards</a><br/>
                        <small class="form-hint text-muted mx-0 px-0 ">Freezing the levels will stop experience from being rewarded, effectively disabling the level system.</small>
                    </div>
                </li>

                <li class="list-group-item bg-dark">
                    <div class="setting-container form-group">
                        <label for="levelRange" class="list-item">XP Multiplier: <code id="levelRangeValue">0</code></label>
                        <input type="range" min="0" max="3" list="tickmarks" value=<?php echo '"'.$settings->settings->level->xp_multi.'"';   ?> class="slider form-control" step="0.1" name="levelRange" id="levelRange">
                        
                    
                        <datalist id=tickmarks>
                            <option>0  </option>
                            <option>0.1</option>
                            <option>0.2</option>
                            <option>0.3</option>
                            <option>0.4</option>
                            <option>0.5</option>
                            <option>0.6</option>
                            <option>0.7</option>
                            <option>0.8</option>
                            <option>0.9</option>
                            <option>1  </option>
                            <option>1.1</option>
                            <option>1.2</option>
                            <option>1.3</option>
                            <option>1.4</option>
                            <option>1.5</option>
                            <option>1.6</option>
                            <option>1.7</option>
                            <option>1.8</option>
                            <option>1.9</option>
                            <option>2  </option>
                            <option>2.1</option>
                            <option>2.2</option>
                            <option>2.3</option>
                            <option>2.4</option>
                            <option>2.5</option>
                            <option>2.6</option>
                            <option>2.7</option>
                            <option>2.8</option>
                            <option>2.9</option>
                            <option>3  </option>

                        </datalist>
                    </div>

                    <input type="submit" class="btn btn-success saveButton" value="Save">
                </li>
            </div>
        </ul>
    </div>

    <div class="cog-container ml-0">
        <ul class="list-group list-group-dark mr-auto  mt-3 mb-5" style="border-radius:10%;" href="#fun">
            <a class="no-hover" style="text-decoration:none;" data-toggle="collapse" href="#collaspeRoleData" role="button">
                <li class="list-group-item list-group-item-dark color-discordblue">
                <h3>Role Permissions</h3>
                </li>
            </a>

            <div class="collapse show" id="collaspeRoleData"> 
                <li class="list-group-item bg-dark">
                    <div class="slider-container form-group">
                        <h4>Moderator Roles</h4>
                        <input type="text" id="modRoles" required class="form-control" name="modRoles" value=<?php echo '"'.implode(",",$settings->roles->moderators).'"';?>>
                        <small class="form-hint text-muted">Role IDs seperated by commas. (Right click a role in discord to get Role ID.) If you don't want any roles then leave blank.</small>
                    </div>
                </li>
                
                <li class="list-group-item bg-dark">
                    <div class="slider-container form-group">
                        <h4>Administrator Roles</h4>
                        <input type="text" required id="adminRoles" class="form-control" name="adminRoles" value=<?php echo '"'.implode(",",$settings->roles->administrators).'"';?>>
                        <small class="form-hint text-muted">Role IDs seperated by commas. (Right click a role in discord to get Role ID.) If you don't want any roles then leave blank.</small>
     
                    </div>
                    <input type="submit" class="btn btn-success saveButton" value="Save">
                </li>

                
            </div>
        </ul>
    </div>
</form>