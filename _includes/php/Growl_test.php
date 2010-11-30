<?php

error_reporting(E_ALL);

include('Growl.php');

$Growl = new Growl('rumfest');

//$Growl->notify(0);
$checked = array();
$result = $Growl->updateUserPrefs('matt', $checked );

?>