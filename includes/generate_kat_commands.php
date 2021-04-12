<?php
defined('INC_CHECK') || die('Direct access not permitted');
$files = scandir('kat_command_helps/');

foreach($files as $file) {
    if ($file == "." or $file == "..") {

    }
    else 
    {

    $stream = file_get_contents("kat_command_helps/" . $file);
    $lines = explode("\n", $stream);
    if (count($lines) > 0) {
    $upcase = ucfirst($file);
    echo "<div class='category'>
		<h2>$upcase</h2><hr>
		<table class='commands'>
			<tr>
				<th>Command</th>
				<th>Usage</th>
			</tr>";
    foreach($lines as $line) {
        if ($line == "" or $line == "\n") {

        }else {
        list($command, $usage) = explode("||", $line);
        echo "<tr>
				<td>$command</td>
				<td>$usage</td>
            </tr>";
        }
    }

    echo "</table>
	</div>";
        }
    }
}


?>