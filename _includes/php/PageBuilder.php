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
		
		// mobile?
		$iPhone = preg_match("/iP(hone|od)/i", $_SERVER['HTTP_USER_AGENT']);
		$iP = preg_match("/iP(hone|od|ad)/i", $_SERVER['HTTP_USER_AGENT']);
		$android = preg_match("/Android/i", $_SERVER['HTTP_USER_AGENT']);
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

		<script src="http://mediaplayer.yahoo.com/js"></script>

		<script src="./_includes/js/site/ghettoBlaster.js"></script>
		<script src="./_includes/js/site/init.js"></script>
	</head>
	<body id="ghetto-blaster">
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
				<form id="play-method" method="post" action="">
					<fieldset>
						<legend>Play method</legend>
						<div class="input-container clearfix">
							<label for="play-broadcast">Broadcast <input type="radio" class="radio" name="play" id="play-broadcast" value="broadcast" checked="checked" /></label>
							<label for="play-preview">Preview <input type="radio" class="radio" name="play" id="play-preview" value="preview" /></label>
						</div>
					</fieldset>
				</form>
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
							<li class="file"><a href="/sfx/'.$fileDetails['dir'].$fileDetails['file'].'">'.$fileDetails['file'].'</a></li>
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
		<link rel="stylesheet" href="./_includes/css/site/iphone.css" media="screen" />		
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
				';
			}

		$return .= '		
		<script src="http://www.google.com/jsapi"></script>
		<script>
			google.load("jquery", "1.4.2");
		</script>

	</head>
	<body id="log-in">
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
	
	
	
	
		/**
		 * Build the page
		 */
		function buildStatsPage($statTypes = array(), $statType = null, $stats = null) {

			$return = '<!DOCTYPE html>
	<html>
		<head>
			<title>Ghetto Blaster - Stats</title>	
			<meta charset="UTF-8" />
			<link rel="stylesheet" href="../_includes/css/site/screen.css" />

			<link rel="shortcut icon" href="../_includes/icons/favicon.ico" type="image/x-icon" />
			<link rel="icon" href="../_includes/icons/favicon.ico" type="image/x-icon" />
			';

			// mobile?
			$iPhone = preg_match("/iP(hone|od)/i", $_SERVER['HTTP_USER_AGENT']);
			$iP = preg_match("/iP(hone|od|ad)/i", $_SERVER['HTTP_USER_AGENT']);
			$android = preg_match("/Android/i", $_SERVER['HTTP_USER_AGENT']);
			if ($iPhone == true){
			  		$return .= '
			<link rel="stylesheet" href="../_includes/css/site/iphone.css" media="screen" />		
			<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
					';
				}

			$return .= '		
			<script src="http://www.google.com/jsapi"></script>
			<script>
				google.load("jquery", "1.4.2");
			</script>
			
			<script src="../_includes/js/lib/swfobject/swfobject.js"></script>
			<script src="../_includes/js/site/ghettoBlaster.js"></script>
			<script src="../_includes/js/site/init.js"></script>
		</head>
		<body id="stats">
			<div id="wrapper">
				<header>
					<h1>Ghetto Blaster</h1>
					<p id="back"><a href="../">Back</a></p>
				</header>


				<div id="content">
					<ul id="stat-types" class="clearfix">
						<li>Stats:</li>
					';
					
				foreach ($statTypes as $name => $method) {
					$selected = ($statType == $name) ? ' class="selected"' : '';
					
					$return .= '
						<li'.$selected.'><a href="./'.$name.'">'.ucfirst(str_replace('-', ' ', $name)).'</a></li>
					';
				}
					
					
				$return .= '
					</ul>
					
					<div id="stat-content">
						<div id="graph"></div>
						
						<script> 
				';
							// var flashvars = {
							// 	  data:"%7B%22title%22%3A%22%5Cu00bfCua%5Cu0301ntos+viven+en+la+misma+casa%3F%22%2C%22id%22%3A%22282%22%2C%22type%22%3A%22bar%22%2C%22orient%22%3A%22h%22%2C%22pMode%22%3Atrue%2C%22feliCat%22%3A%22family-and-friends%22%2C%22cats%22%3A%5B%226+o+ma%5Cu0301s%22%2C%225+personas%22%2C%224+personas%22%2C%223+personas%22%2C%222+personas%22%2C%22Vive+solo%22%5D%2C%22cats2%22%3A%5Bnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%5D%2C%22chartData%22%3A%5B%7B%22name%22%3A%22Muy+Felices%22%2C%22values%22%3A%5B2.8%2C6.7%2C27.4%2C29.4%2C28.5%2C5.1%5D%7D%2C%7B%22name%22%3A%22Poco+Felices%22%2C%22values%22%3A%5B1.7%2C14%2C23.3%2C19.8%2C26.7%2C14.5%5D%7D%5D%2C%22dSep%22%3A%22%2C%22%2C%22outOf%22%3Anull%7D"
							// };
				$return .= '
				
							var flashvars = { data: "'.$stats.'" };

							var params = { 
							  bgcolor: "#ffffff"
							};
							swfobject.embedSWF("../_includes/swf/graphinator.swf", "graph", "410", "700", "9.0.115.0", null, flashvars, params);
						</script>
					</div>
					<pre>
					';
					//$return .= str_replace(",", ",\r",urldecode($stats));
					$return .= '
					</pre>
				</div>
			</div>
	</body>
	</html> 
			';

			return $return;
		}
	
}