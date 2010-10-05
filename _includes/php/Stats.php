<?php
/**
 * This class is responsible for generating stats
 *
 */

require_once('Log.php');


class Stats {
	
	/*
	 *
	 */
	protected $log = null;
	
	/*
	 *
	 */
	protected $statTypes = array(
		'top-tracks' => 'TopTracks',
		'top-users' => 'TopUsers',
//		'top-tracks-by-user' => 'TopTracks',
//		'top-users-by-track' => 'TopTracks',
//		'plays-by-month' =>'TopTracks',
//		'top-tracks-by-month' => 'TopTracks',
//		'top-users-by-month' => 'TopTracks'
	);
	
	/*
	 *
	 */
	protected $colourSchemes = array(
		"family-and-friends",
		"health",
		"age",
		"food",
		"leisure",
		"love-and-sex",
		"money",
		"work"
	);
	
	/*
	 *
	 */
	public function __construct() {
		$log = new Log;
		$this->log = $log->getLog();
	}
	
	
	/*
	 *
	 */
	public function getStatTypes() {
		return $this->statTypes;
	}	
	
	
	/*
	 *
	 */
	public function getStats($statType) {
		
		// condition : if the requested stat type exists, run it
		if (isset($this->statTypes[$statType])) {
			$method = "get".$this->statTypes[$statType];
			return $this->$method();

		// requested method doesn't exist
		} else {
			return false;
		}
	}
	
	
	/*
	 *
	 */
	public function getDefaultStats() {
		return $this->getStats(key($this->statTypes));
	}
	
	
	
	/*
	 * 
	 */
	protected function getTopTracks() {
		$tracks = array();
		
		// loop through all played tracks
		foreach($this->log as $lineNum => $line) {
			
			// if the line refers to playing a track
			if (strpos($line, "played the file '") !== false) {
			
				// track is after `played the file '` until closing `'`
				$start = strpos($line, "played the file '")+17;
				$end = strpos($line, "' - ") - $start;
				$track = substr($line, $start, $end);
			
				// condition : if track has already been added to the array, add count
				if (array_key_exists($track, $tracks)) {
					$tracks[$track]++;

				// new track, add to array
				} else {
					$tracks[$track] = 1;
				}
			}
		}
		
		arsort($tracks);
		$tracks = array_slice($tracks, 0, 10);


		return $this->buildFlashVars($tracks, 'Top Tracks', 'bar', 'h');
	}
	
	
	
	/*
	 * 
	 */
	protected function getTopUsers() {
		$users = array();
		
		// loop through all played tracks
		foreach($this->log as $lineNum => $line) {

			// get username - it's the start of the line until the first space
			$user = substr($line, 0, strpos($line, ' '));
			
			// condition : if user has already been added to the array, add count
			if (array_key_exists($user, $users)) {
				$users[$user]++;

			// new user, add to array
			} else {
				$users[$user] = 1;
			}
		}
		
		arsort($users);
		$users = array_slice($users, 0, 10);
		
		
		return $this->buildFlashVars($users, 'Top Users', 'bar', 'h');
	}
	
	
	
	
	
	
	
	
	/*
	 * 
	 */
	protected function buildFlashVars($stats, $title, $graphType, $orientation) {
		
		$chartData = array();
		
		
		// construct data
		foreach($stats as $key => $stat){
			$data = array(
				'name' => $key,
				'values' => array(),
			);
		}
		foreach($stats as $skey => $svalue){
			$data['values'][] = $svalue;
		}
		$chartData[] = $data;
		
		
		// construct titles
		$cats = array();
		$cats2 = array();
		foreach($stats as $key => $stat){
			$cats[] = $key;
			$cats2[] = null;
		}
		
		
		// select random category (for styling)
		$scheme = array_rand($this->colourSchemes);
		
		
		// construct metadata
		$data = new stdClass();
		$data->title = $title;
		$data->id = '1';
		$data->type = $graphType;
		$data->orient = $orientation;
		$data->pMode = false;
		$data->feliCat = $this->colourSchemes[$scheme];
		$data->cats = $cats;
		$data->cats2 = $cats2;
		$data->chartData = $chartData;
		$data->dSep = '.';
		$data->outOf = null;
		
		return urlencode(json_encode($data));
	}
}