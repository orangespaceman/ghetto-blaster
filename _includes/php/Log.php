<?php
	
/**
 * This class is responsible for logging
 *
 */
class Log {

	/*
	 *
	 */
	var $log = null;
	
	
	/*
	 *
	 */
	var $file = "../txt/log.txt";
	
	
	/*
	 *
	 */
	var $doLog = true;
	
	
	/*
	 *
	 */
	function __construct($doLog) {
		$this->doLog = $doLog;
	}
	
	
	/*
	 *
	 */
	function logMessage($message) {
		
		// condition : log messages?
		if ($this->doLog) {
		
			// if file pointer doesn't exist, then open log file
			if (!$this->log) $this->logOpen();
		
			// define current time
			$time = date('Y-m-d H:i:s');
		
			$ip = $_SERVER['REMOTE_ADDR'];
			
			// condition : is a user set?
			$user = (isset($_SESSION['u'])) ? $_SESSION['u'] : "anon";
		
			// write current time, script name and message to the log file
			fwrite($this->log, $user . " (" . $ip . ") " . $message . " - " . $time . "\n");
		}
	}
	
	
	// open log file
	private function logOpen(){
		
		// define log file path and name
		$file = $this->file;
		
		// open log file for writing only; place the file pointer at the end of the file
		// if the file does not exist, attempt to create it
		$this->log = fopen($file, 'a') or exit("Can't open $file!");
	}
}