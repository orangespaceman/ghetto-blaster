<?php 
/**
 * page builder class.  
 * build page header and footer
 *
*/
class PageBuilder {
	
	/**
	 * The constructor.
	 */
	function __construct() {
	}

	
	/**
	 * Build the page
	 */
	function buildPage($files, $volume) {

		$return = '<!DOCTYPE html>
<html>
	<head>
		<title>Ghetto Blaster</title>	
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="./_includes/css/site/screen.css" />
		
		<link rel="shortcut icon" href="./_includes/icons/favicon.ico" type="image/x-icon" />
		<link rel="icon" href="./_includes/icons/favicon.ico" type="image/x-icon" />
		';
		
		// iphone?
		$iPhone = preg_match("/iP(hone|od)/i", $_SERVER['HTTP_USER_AGENT']);
		$iP = preg_match("/iP(hone|od|ad)/i", $_SERVER['HTTP_USER_AGENT']);
		if ($iPhone == true){
		  		$return .= '
		<link rel="stylesheet" type="text/css" href="./_includes/css/site/iphone.css" media="screen" />		
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
				';
			}
		
		$return .= '		
		<script src="http://www.google.com/jsapi"></script>
		<script>
			google.load("jquery", "1.4.2");
		</script>

		<script src="./_includes/js/site/ghettoBlaster.js"></script>
		<script src="./_includes/js/site/init.js"></script>
	</head>
	<body>
		<div id="wrapper">
			<header>
				<h1>Ghetto Blaster</h1>
				<div id="say">
					<form method="post" action="">
						<fieldset>
							<legend>Say</legend>
							<div class="input-container clearfix">
								<input type="text" class="text" id="say-text" name="say-text" />
								<select name="voice" id="voice">
									<optgroup label="Female">
										<option>Agnes</option>
										<option>Kathy</option>
										<option>Princess</option>
										<option>Vicki</option>
										<option>Victoria</option>
									</optgroup>                       
									<optgroup label="Male">
										<option selected="selected">Bruce</option>
										<option>Fred</option>
										<option>Junior</option>
										<option>Ralph</option>
									</optgroup>
									<optgroup label="Novelty">
										<option>Albert</option>
										<option value=\'"Bad News"\'>Bad News</option>
										<option>Bahh</option>
										<option>Bells</option>
										<option>Boing</option>
										<option>Bubbles</option>
										<option>Cellos</option>
										<option>Deranged</option>
										<option value=\'"Good News"\'>Good News</option>
										<option>Hysterical</option>
										<option value=\'"Pipe Organ"\'>Pipe Organ</option>
										<option>Trinoids</option>
										<option>Whisper</option>
										<option>Zarvox</option>						
									</optgroup>
								</select>
								<input type="submit" class="button" value="Say" />
							</div>
						</fieldset>
					</form>
				</div>
				<div id="volume" class="clearfix">
					<ul class="horiznavlist clearfix">
						<li class="first">Volume: <a id="volume-down" href="#">Down</a>
						<li id="volume-level">'.$volume.'</li>
						<li><a id="mute" href="#">Mute</a></li>
						<li class="last"><a id="volume-up" href="#">Up</a>
					</ul>
				</div>
			</header>
			
			
			<div id="content">
		';
				
				
		$return .= $this->buildBlaster($files);
		
				
		$return .= '		
			</div>
		</div>
</body>
</html> 
		';

		return $return;
	}
	
	
	/*
	 * 
	 */
	function buildBlaster($files) {
		
		$keys = array();
		
		$count = count($files);
		
		$return = "";
		
		foreach ($files as $key => $file) {
			$keys[] = urlencode($key);
			$return .= '
				<div id="folder-'.urlencode($key).'" class="folder-container" style="z-index:'.$count--.'">
					<div class="folder clearfix">
						<h2>'.$key.'</h2>
						<ul>
				';

					foreach ($file['files'] as $fileKey => $fileDetails) {
						$return .= '
							<li class="file"><a href="/'.$fileDetails['dir'].$fileDetails['file'].'">'.$fileDetails['file'].'</a></li>
						';
					}

				$return .= '
						</ul>
					</div>
				</div>
			';
		}
		
		$return .= '
			<div id="keys">
				<h2>Quicklinks</h2>
				<ul>
		';
		
		foreach($keys as $key) {
			$return .= '
					<li><a href="#folder-'.$key.'">'.$key.'</a></li>
			';
		}
					
		$return .= '
				</ul>
			</div>
		';
		
		return $return;
	}
	
	
	
	/**
	 * Build the login page
	 */
	function buildLoginPage() {

		$user = (isset($_POST['u'])) ? $_POST['u'] : "";

		$return = '<!DOCTYPE html>
<html>
	<head>
		<title>Ghetto Blaster - Login</title>	
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="./_includes/css/site/screen.css" />

		<link rel="shortcut icon" href="./_includes/icons/favicon.ico" type="image/x-icon" />
		<link rel="icon" href="./_includes/icons/favicon.ico" type="image/x-icon" />
		';

		// iphone?
		$iPhone = preg_match("/iP(hone|od)/i", $_SERVER['HTTP_USER_AGENT']);
		$iP = preg_match("/iP(hone|od|ad)/i", $_SERVER['HTTP_USER_AGENT']);
		if ($iPhone == true){
		  		$return .= '
		<link rel="stylesheet" type="text/css" href="./_includes/css/site/iphone.css" media="screen" />		
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
				';
			}

		$return .= '		
		<script src="http://www.google.com/jsapi"></script>
		<script>
			google.load("jquery", "1.4.2");
		</script>

	</head>
	<body>
		<div id="wrapper">
			<header>
				<h1>Ghetto Blaster</h1>
			</header>

			<div id="content">
				<div id="login">
					<div id="login-inner">

						<h2>With great power comes great responsibility&hellip;</h2>

						<form method="post" action="">
							<fieldset>
								<legend>Login</legend>
								<div class="input-container clearfix">
									<label for="u">Username</label>
									<input type="text" class="text" id="u" name="u" value="'.$user.'" />
								</div>
								<div class="input-container clearfix">
									<label for="p">Password</label>
									<input type="password" class="text" id="p" name="p" />
									<input class="button" type="submit" name="login" id="button" value="Log in" />
								</div>
							</fieldset>
						</form>
					';

		if (isset($_POST) && count($_POST) > 0) {
			$return .= '
						<p class="error">There was an error with your log-in details, please try again!</p>
			';
		}
			
		$return .= '
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
		';
	
		return $return;
	
	}
	
}