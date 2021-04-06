<?php
    define("INC_CHECK", TRUE);
    $pageName = "Commands";
    include_once('includes/header.php');
?>
  <div class="container mr-auto">

    <h1 class="text-center pt-5">Commands</h1>
    <p></p> 
    
    <?php
      $prefix = "$";
      if (isset($_GET['prefix']))
      {
        $prefix = $_GET['prefix'];
      }
    ?>

    <div>
    
    <?php

      error_reporting(E_ALL);
ini_set('display_errors', 1);
      $helpFile = file_get_contents($_SERVER['DOCUMENT_ROOT']."/kat_command_helps/help_info.json");
      $helpData = json_decode($helpFile);
      
      

      foreach($helpData as $module => $moduleInfo)
      {
        echo '
          <div class="cog-container ml-0">
            <ul class="list-group list-group-dark mr-auto mt-3" style="border-radius:10%;" href="#fun">
              <a class="no-hover" style="text-decoration:none;" data-toggle="collapse" href="#collaspe'. $module .'" role="button">
                <li class="list-group-item list-group-item-dark color-discordblue">
                  <span class="h3 mr-4 px-0 align-middle">'. $module . '</span>';
                  foreach($moduleInfo->badges as $badge) {echo "<span class='badge badge-dark ml-1 align-middle'>".$badge .'</span>';} 
                echo'
                </li>
              </a>
              <div class="collapse" id="collaspe'. $module .'">
                <li class="list-group-item bg-dark">';

        foreach($moduleInfo->commands as $key => $value)
        {
          // Replace < and > into HTML escaped codes to avoid fucking up formatting.
          $htmlSafeCommand = preg_replace(array("/</","/>/"), array("&lt;","&gt;"), $key);
          $htmlSafeDesc = preg_replace(array("/</","/>/"), array("&lt;","&gt;"), $value);
          echo '
                  <h4><code>'.$prefix.$htmlSafeCommand.'</code></h4>
                  <p>'.$htmlSafeDesc.'</p>     
          ';
        }
        
        echo '</li></div></ul></div>';

        // if ($file != "." and $file != ".." and $file != "kat_stats")
        // {
        // echo '
        // 
        //   ';

        //   while(!feof($helpFile))
        //   {
        //     $cmdData = fgets($helpFile);
        //     $cmd = str_getcsv($cmdData, ",")[0];
        //     $desc = str_getcsv($cmdData, ",")[1];
        //     echo '<li class="list-group-item bg-dark">
        //       <h4><code>'.$prefix.$cmd.'</code></h4>
        //       <p>'.$desc.'</p>
        //     </li>';
        //   }

        //   fclose($helpFile);
        //   echo '</div></ul></div>';
        //}
        
      }
    ?>
    </div>
  </div>
</body>

</html>