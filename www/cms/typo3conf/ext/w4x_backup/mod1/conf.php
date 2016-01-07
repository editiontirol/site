<?php

	// DO NOT REMOVE OR CHANGE THESE 3 LINES:
define('TYPO3_MOD_PATH', '../typo3conf/ext/w4x_backup/mod1/');
$BACK_PATH='../../../../typo3/';
define("TYPO3TEMP_PATH", "../../../../");
$MCONF["name"]="tools_txw4xbackupM1";

	
$MCONF["access"]="user,group";
$MLANG["default"]["tabs_images"]["tab"] = "moduleicon.gif";
$MCONF["script"]="index.php";


	// Default (english) labels:
$MLANG["default"]["tabs"]["tab"] = "Full Backup";	
$MLANG["default"]["labels"]["tabdescr"] = "Backups database, fileadmin, uploads and conf-files.";	
$MLANG["default"]["labels"]["tablabel"] = "Instant backup of the whole Typo3 installation";	

?>