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
//		'top-tracks-by-user' => 'TopTracksByUser',
//		'top-users-by-track' => 'TopUsersByTrack',
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
	protected $graphTypes = array(
		"bar",
		"sbar",
		"line"
	);
	
	/*
	 *
	 */
	protected $graphOrientations = array(
		"v",
		"h",
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
	public function getDefaultStatType() {
		return key($this->statTypes);
	}
	
	
	/*
	 * 
	 */
	protected function buildFlashVars($stats, $cats, $cats2, $title) {
		
		$chartData = array();
		
		
		// construct data
		foreach($stats as $key => $stat){
			$data = array(
				'name' => $key,
				'values' => array(),
			);
			//foreach($stats as $skey => $svalue){
				$data['values'][] = $stat;
			//}
			$chartData[] = $data;
		}
		
		
		// select random styles
		$scheme = array_rand($this->colourSchemes);
		$graphType = array_rand($this->graphTypes);
		$orientation = array_rand($this->graphOrientations);
				
		// construct metadata
		$data = new stdClass();
		$data->title = $title;
		$data->id = '1';
		$data->type = $this->graphTypes[$graphType];
		$data->orient = $this->graphOrientations[$orientation];
		$data->pMode = false;
		$data->feliCat = $this->colourSchemes[$scheme];
		$data->cats = $cats;
		$data->cats2 = $cats2;
		$data->chartData = $chartData;
		$data->dSep = '.';
		$data->outOf = null;
		
		return urlencode(json_encode($data));
	}
	
	
	
	
	
	
	
	/*
	 * STATS METHODS
	 */
	
	
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
		$categories = array('Top Tracks');
		$categories2 = array(null);


		return $this->buildFlashVars($tracks, $categories, $categories2, 'Top Tracks');
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
		$categories = array('Top Users');
		$categories2 = array(null);


		return $this->buildFlashVars($users, $categories, $categories2, 'Top Users');
	}

	
	
	
	/*
	 * 
	 */
	protected function getTopUsersByTrack() {
		$tracks = array();
		$users = array();
		
		// loop through all played tracks
		foreach($this->log as $lineNum => $line) {
			
			// if the line refers to playing a track
			if (strpos($line, "played the file '") !== false) {
			
				// track is after `played the file '` until closing `'`
				$start = strpos($line, "played the file '")+17;
				$end = strpos($line, "' - ") - $start;
				$track = substr($line, $start, $end);
				
			
				// condition : if track has already been added to the array, add count
				if (!array_key_exists($track, $tracks)) {
					$tracks[$track] = array();
				}
				if (!array_key_exists($user, $tracks[$track])) {
					if (count($tracks[$track]) < 2)
					$tracks[$track][$user] = 0;
				}
				if (isset($tracks[$track][$user]))
				$tracks[$track][$user]++;
			}
		}
		
		arsort($tracks);
		$tracks = array_slice($tracks, 0, 10);
		$categories = array('Top Tracks');
		$categories2 = array(null);

		return $this->buildFlashVars($tracks, $categories, $categories2, 'Top Tracks By User');
	}
}