<?php
    class Growl
    {
 		
		const MESSAGES_FILE		= '/../txt/messages_growl.txt';
		const USERS_FILE		= '/../txt/users_growl.txt';
		
		private $notifications;
		private $app_name;
		private $password;
		private $users;
		private $messages;
		private $currentUser;
		
        public function __construct($password = '', $currentUser = '')
        {
            $this->notifications 	= array();
			$this->password 	 	= $password;
			$this->currentUser		= $currentUser;
			$this->loadConfig();
			
        }
		
		public function notify($type){
			
			$hosts = array();
			
			foreach($this->users as $user){
				//Does the user subscribe to this type of notification.
				if($user['permission'][$type] == 1){
					$this->addNotification($user['host'], $this->messages[$type]['subject'], str_replace('{sender}', ucfirst($this->currentUser), $this->messages[$type]['message']) );
					$hosts[] = $user['host'];
				}
			}
			
			
			$this->sendNotifications();
			$this->log($this->messages[$type]['subject'].": ".$this->messages[$type]['message'], count($hosts));
			
			return 1;
		}
		

		private function addNotification($host, $subject, $message){
			$this->notifications[] = escapeshellarg($host)." ".escapeshellarg($subject)." ".escapeshellarg($message)." ".escapeshellarg($this->password)."";
		}
		
		public function sendNotifications(){
			
			if(!empty($this->notifications)){
				$output = '';
			
				foreach($this->notifications as $n){
					echo $n;
					$output .= shell_exec('sh growlNotify.sh '.$n);
				}
				return true;
			}else{
				return false;
			}
			

		}
		
		private function loadConfig(){
			$users = file(dirname(__FILE__) .Growl::USERS_FILE);

			foreach($users as $user){
				if(!empty($user)){
					$a = explode(',' ,$user);
					
					$permissions = explode(":",$a[2]);
					foreach($permissions as $key => $perm){
						$permissions[$key] = intval($perm);
					}
					$this->users[] = array( 
									'username'	=> $a[0],
									'host' 		=> $a[1],
									'permission'=> $permissions
									);
					
					
				}

			}

			$mess = file(dirname(__FILE__) .Growl::MESSAGES_FILE);

			foreach($mess as $message){
				if(!empty($message)){
					$m = explode(",", $message);
					$this->messages[] = array(
											'id'  => intval($m[0]),
											'subject' =>$m[1],
											'message' =>$m[2]
											);
				}
			}
			
			$this->verifyPermissions();
		}
		
		private function verifyPermissions(){
			foreach($this->users as $key => $user){
				if(count($user['permission']) < count($this->messages)){
					
					foreach($this->messages as $pkey => $message){
							if(!isset($user['permission'][$pkey])){
								$user['permission'][$pkey] = 0;
							}
					}
				}
				
				if(count($user['permission']) > count($this->messages)){
					foreach($user['permission'] as $mkey => $per){
						if(!isset($this->messages[$mkey])){
							unset($user['permission'][$mkey]);
						}
					}
				}
				
				$this->users[$key] = $user;
			}
			
			$this->saveUserDetails();
			
		}
		
		public function clearNotification(){
			unset($this->notifications);
		}
		
		public function getMessages(){
			return $this->messages;
		}
		
		public function getUsers(){
			return $this->users;
		}
		
		public function getUserByName($name){
			
			foreach($this->users as $user){
				if($user['username'] == $name){
					$return = $user;
				}
			}
			if(isset($return)){
				return $return;
			}else{
				return false;
			}
		}
		
		private function log($notification, $hosts){
			$logFile = dirname(__FILE__) . "/../txt/log_growl.txt";
			
			$user = (isset($_SESSION['u'])) ? $_SESSION['u'] : "anon";
			$log = fopen($logFile, 'a') or exit("Can't open $logFile!");
			fwrite($log, $user." sent notification-> ".$notification." (to ".$hosts." hosts) \n");
		}
		
		
		public function updateUserDetails($u){
		
			$updateKey;
			
			foreach($this->users as $key => $user){
				
				if($u['username'] == $user['username']){
					$updateKey = $key;
				}
			}
		//	echo $updateKey;
			$this->users[$updateKey] = $u;
			$this->saveUserDetails();
		}
		
		private function addUser($username, $host){

			foreach($this->messages as $m){
				$pm[] = 0;
			}
			
			$this->users[] = array('username'=>$username, 'host'=>$host, 'permission'=>$pm);
			$this->saveUserDetails();
		}
		
		private function saveUserDetails(){
			
			$userFile = fopen( dirname(__FILE__).Growl::USERS_FILE, 'w+')  or exit("Can't open ".$dirname(__FILE__).Growl::USERS_FILE."!");
			
		
			foreach($this->users as $user){
				
				foreach($user['permission'] as $key => $permission){
					$user['permission'][$key] = rtrim($permission);
				}	
				$uArr[] = rtrim($user['username'].",".$user['host'].",".implode(":", $user['permission']));
			}

			$uString = implode("\n", $uArr);
			fwrite($userFile, $uString);
		}
		
		public function updateUserPrefs($userName, $prefs){
			
			$user = $this->getUserByName($userName);
			
			$permissions = $user['permission'];
			$newPermission =  Array();
				
		//	var_dump($prefs);
			foreach($permissions as $k => $v) {
				$i = 0;
				
			
				foreach($prefs as $pref){
				
					if($k == $pref){
						$i = 1;
						
					}
				
				}

				$newPermission[$k] = $i;
			}
			//var_dump($newPermission);

			$user['permission'] = $newPermission;
			
			$this->updateUserDetails($user);
			
			return 1;
			
		}
				
		
		public static function updateIP($userName){
			
			$growl = new Growl('');
			$user = $growl->getUserByName($userName);
			
			//Check IP is ipv4
			if(preg_match('/\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b/', $_SERVER['REMOTE_ADDR'])){
				$ip = $_SERVER['REMOTE_ADDR'];
			}else{
				$ip = gethostbyname(gethostbyaddr($_SERVER['REMOTE_ADDR']));
			}
			
			
			if($user){
				//update
				if($user['host'] != $ip){
					$user['host'] = $ip;
					$growl->updateUserDetails($user);
				}
			}else{
				//insert
				$growl->addUser($userName, $ip);
			
			}
			
			//Make sure IP is unique
			//$allUsers = $growl->getUsers();
			//foreach($allUsers as $u){
				
			//	if($u['host'] == $_SERVER['REMOTE_ADDR']){
					//echo $u['host'];
		//			$u['host'] = '0.0.0.0';
		//			$growl->updateUserDetails($user);
		//		}
		//	}
		
		}
						
		public function updateUserPermission($username, $permissions){
			$user = $this->getUsersByName($username);
			$user['permission'] = $permissions;
			$this->updateUserDetails($user);
		}
		
		
		
		
 	}
?>