<?php
	// import ghetto blaster files
	require_once("../_includes/php/Login.php");
	require_once("../_includes/php/PageBuilder.php");
	require_once("../_includes/php/Stats.php");
	
	// get ghetto blaster configuration file
	$conf = parse_ini_file("../config.ini.php", true);

	// condition : actions logged?
	if (!$conf['options']['logActions']) {
		echo "Actions not logged";
		die;
	}

	// start stats
	$statModel = new Stats;
	$statTypes = $statModel->getStatTypes();
	
	// condition : is a stat type sent through?
	if (isset($_GET['stattype'])) {
		$statType = $_GET['stattype'];
		$stats = $statModel->getStats($statType);
	} else {
		$statType = $statModel->getDefaultStatType();
		$stats = $statModel->getStats($statType);
	}

	// start the page builder
	$page = new PageBuilder();
	
	// get ghetto blaster configuration file
	$conf = parse_ini_file("../config.ini.php", true);

	session_start();

	// build page
	echo $page->buildStatsPage($statTypes, $statType, $stats);