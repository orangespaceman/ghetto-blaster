<?php
	session_start();

	if (isset($_POST) && count($_POST) > 0) {

		// get site configuration
		$conf = parse_ini_file("../../config.ini.php", true);
		
		
		// import required files
		require_once("./GhettoBlaster.php");
		require_once("./class.growl.php");
		require_once("./Log.php");

		// start the ghetto blaster
		$ghettoBlaster = new GhettoBlaster;
		$ghettoBlaster->setPath($conf['paths']['sfx']);
		
		// start growl
		$growl =  new Growl('test', $_SESSION['u']);
	

		// start the log (if required)
		$log = new Log($conf['options']['logActions']);

		// check what to do
		$method = $_POST['method'];
		unset($_POST['method']);
		switch ($method) {

			
			case "play":
				
				// condition : if login is required but not present, don't play a sound
				if ($conf['options']['doLogin'] && !isset($_SESSION['u'])) {
					exit;
				}

				$result = $ghettoBlaster->play($_POST['file']);
				$log->logMessage("played the file '" . $_POST['file'] . "'");
				echo json_encode($result);
			break;
			
			
			case "stop":
				$result = $ghettoBlaster->stop();
				$log->logMessage("hit stop");
				echo json_encode($result);
			break;
			
			
			case "getVolume":
				$result = $ghettoBlaster->getVolume();
				echo json_encode($result);
			break;
			
			
			case "volumeUp":
				$result = $ghettoBlaster->volumeUp();
				echo json_encode($result);
			break;
			
			
			case "volumeDown":
				$result = $ghettoBlaster->volumeDown();
				echo json_encode($result);
			break;
			
			
			case "mute":
				$result = $ghettoBlaster->mute();
				$log->logMessage("hit mute");
				echo json_encode($result);
			break;
			

			case "say":
				$result = $ghettoBlaster->say($_POST['say'], $_POST['voice']);
				$log->logMessage("made the server say '" . $_POST['say'] . "'");
				echo json_encode($result);
			break;
			
			case "notify":
				$result = $growl->notify(0);
				echo json_encode($result);
			break;
		}
	}
	
