<?php
/**
 * This class is responsible for the main functionality of the Ghetto Blaster
 *
 */
class GhettoBlaster {

	/*
	 *
	 */
	var $path = "/";
	
	
	/*
	 *
	 */
	var $volume = 0;
	
	/*
	 *
	 */
	var $symlink = "sfx";
	
	
	/**
	 * A list of filenames & directory names to ignore
	 *
	 * @var array $ignorelist The names to skip over
	 */
	var $ignorelist = array (
		'.',
		'..',
		'.svn',
		'CVS',
		'.DS_Store',
		'_htaccess',
		'.htaccess',
		'_htpasswd',
		'.htpasswd',
		'Thumbs.db'
	);
	
	
	/**
	 * A list of file types that are playable
	 *
	 * @var array $playableFiletypes
	 */
	var  $playableFiletypes = array (
		'mp3',
		'mp4',
		'wav',
		'aiff',
		'm4a'
	);
	

	/*
	 *
	 */
	function __construct() {
		$this->getVolume();
	}
	
	
	/*
	 *
	 */
	function setPath($path) {
		$this->path = $path;
		
		// condition : does symlink exist?
		if (!@readlink($this->symlink)) {
			@symlink($path, $this->symlink);
		}
	}
	
	
	/*
	 *
	 */
	function play($play) {
		$play = strip_tags($play);
		$play = str_replace('/sfx', '', $play);
		$cmd = 'afplay ' . $this->path . $play;
		shell_exec($cmd);
		return $cmd;
	}
	
	
	/*
	 *
	 */
	function stop() {
		shell_exec('killall afplay');
		//shell_exec('killall say');
		return "done";
	}
	
	
	/*
	 *
	 */
	function getVolume() {
		$this->volume = shell_exec('osascript -e "output volume of (get volume settings)"');
		return array("volume" => $this->volume);
	}
	

	/*
	 *
	 */
	function mute() {
		shell_exec("osascript -e 'set volume output volume 0'");
		return $this->getVolume();
	}

	
	/*
	 *
	 */
	function volumeUp() {
		shell_exec("osascript -e 'set volume output volume (get (output volume of (get volume settings)) + 5)'");
		return $this->getVolume();
	}
	
	
	
	/*
	 *
	 */
	function volumeDown() {
		shell_exec("osascript -e 'set volume output volume (get (output volume of (get volume settings)) - 5)'");
		return $this->getVolume();
	}
	
	
	
	/*
	 *
	 */
	function say($txt, $voice) {
		$cmd = "say -v ".stripslashes($voice)." '" . $txt . "'";
		shell_exec($cmd);
		return $cmd;
	}
	
	

	/**
	 * Create a list of all files
	 *
	 * @return array $fileList Array of all files, with file/directory details
	 * @access public
	 */
	function createFileList($path="", $dir="") {
		
		if (empty($path)) {
			$path = $this->path;
			$root = true;
		} else {
			$root = false;
		}

		// temporary arrays to hold separate file and directory content
		$filelist = array();
		$directorylist = array();

		// get the ignore list, in local scope (can't use $this-> later on)
		$ignorelist = $this->ignorelist;

		// Open directory and read contents
		if (is_dir($path)) {

			// loop through the contents
            $dirContent = scandir($path);

			foreach($dirContent as $key => $file) {

				// skip over any files in the ignore list, and mac-only files starting with ._
				if (!in_array($file, $ignorelist) && (strpos($file, "._") !== 0)) {

					// condition : if it is a directory, add to dir list array
					if (is_dir($path.$file)) {

						$directorylist[$file] = array(
							"files" => $this->createFileList($path.$file, $file)
						);

                    // file, add to file array
					} else {
						if ($root) {
							$directorylist["root"]["files"][] = array(
								"file" => $file,
								"path" => $path,
								"dir" => $dir
							);
						} else {
							$filelist[] = array(
								"file" => $file,
								"path" => $path,
								"dir" => $dir . "/"
							);
						}
					}
				}
			}
		}

		// merge file and directory lists
		$finalList = array_merge($directorylist, $filelist);
		
		return $finalList;
	}
}