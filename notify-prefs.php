<?php
session_start();
?>
<html>
<head>
	<title>Ghetto Blaster - Notify Preferences</title>	
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="./_includes/css/site/screen.css" />
	
	<link rel="shortcut icon" href="./_includes/icons/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="./_includes/icons/favicon.ico" type="image/x-icon" />	
	<script src="http://www.google.com/jsapi"></script>
	<script>
		google.load("jquery", "1.4.2");
	</script>

	<script src="http://mediaplayer.yahoo.com/js"></script>

	<script src="./_includes/js/site/ghettoBlaster.js"></script>
	<script src="./_includes/js/site/init.js"></script>
</head>
<?php
	
	require_once("./_includes/php/Growl.php");
	$growl = new Growl('');
	$messages = $growl->getMessages();
	$user = $growl->getUserByName($_SESSION['u']);
	//var_dump($user['permission']);
	//var_dump($user);
?>
<body id='ghetto-blaster' class='notify-prefs'>
<h1>Modify your Notication preferences</h1>
<div class="pref-text">
<p><strong>Hello <?php echo $user['username'];?> your IP is logged as:</strong> <?php echo $user['host'];?></p>
<p>Tick which notifications you would like to receive</p>
</div>
<div class='message'>
	Your preferences have been updated
</div>
<form action='' method='post' id='prefs-form' class='clear'>
		<?php
		
		foreach ($messages as $message){
			if($user['permission'][$message['id']]){
				echo '<div class="input-row"><input type="checkbox" id="'.$message['subject'].'" value="'.$message['id'].'" checked="checked" name="permission"/><label for="'.$message['subject'].'">'.$message['subject'].'</label></div>'."\n";
			}else{
				echo '<div class="input-row"><input type="checkbox" value="'.$message['id'].'" name="permission"/><label for="'.$message['subject'].'">'.$message['subject'].'</label></div>'."\n";
			}
			
		}
		echo '<input type="hidden" name="username" value="'.$user['username'].'" />';
		
		?>
		<div class='input-row'>
			<input type="submit" class="button" value="Update Preferences" />
		</div>
</form>
<div class="pref-text pref-border" >
<h2>How to set up Growl</h2>
<ul>
	<li>Under system preferences set Growl to 'Listen for incoming notification' and 'Allow remote application registration'.</li>
	<li>Also provide the server password as 'rumfest'</li>
	
</ul>
<img src='/_includes/img/site/growl-settings.png'>
</div>
</body>
</html>