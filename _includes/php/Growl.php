<?php

error_reporting(E_ALL);

include('class.growl.php');

$Growl = new Growl('test');

$Growl->notify(0);

?>