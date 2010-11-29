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
			$users = file_get_contents(dirname(__FILE__) .Growl::USERS_FILE);
			$usersArr = explode('|',$users);

			foreach($usersArr as $user){
				if(!empty($user)){
					$a = explode(',' ,$user);
					$this->users[] = array( 
									'username'	=> $a[0],
									'host' 		=> $a[1],
									'permission'=> explode(":",$a[2])
									);
				}

			}

			$mess = file_get_contents(dirname(__FILE__) .Growl::MESSAGES_FILE);
			$messArr = explode('|',$mess);
			
			foreach($messArr as $message){
				if(!empty($message)){
					$m = explode(",", $message);
					$this->messages[] = array(
											'id'  => $m[0],
											'subject' =>$m[1],
											'message' =>$m[2]
											);
				}
			}

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
		
		
		public function updateUserDetails($user){
			
			$key = array_search($user['username'], $this->users);
			$this->users[$key] = $user;
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
				$uArr[] = $user['username'].",".$user['host'].",".implode(":", $user['permission']);
			}
			
			$uString = implode("|", $uArr);
			fwrite($userFile, $uString);
		}
		
		public static function updateUserPrefs($userName, $prefs){
			$growl = new Growl('');
			$user = $growl->getUserByName($userName);
			
			$user['permission'] = $prefs;
			$growl->updateUserDetails($user);
			
		}
				
		
		public static function updateIP($userName){
			
			$growl = new Growl('');
			$user = $growl->getUserByName($userName);
			
			if($user){
				//update
				if($user['host'] != $_SERVER['REMOTE_ADDR']){
					$user['host'] = $_SERVER['REMOTE_ADDR'];
					$growl->updateUserDetails($user);
				}
			}else{
				//insert
				$growl->addUser($userName, $_SERVER['REMOTE_ADDR']);
			
			}
			
			//Make sure IP is unique
			$allUsers = $growl->getUsers();
			foreach($allUsers as $u){
				
				if($u['host'] == $_SERVER['REMOTE_ADDR']){
					echo $u['host'];
					$u['host'] = '0.0.0.0';
					$growl->updateUserDetails($user);
				}
			}
		
		}
						
		public function updateUserPermission($username, $permissions){
			$user = $this->getUsersByName($username);
			$user['permission'] = $permissions;
			$this->updateUserDetails($user);
		}
		
		
		
		
 	}
?>