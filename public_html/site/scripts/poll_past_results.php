<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("websections/JaduPolls.php");

	if (isset($_REQUEST['pollID']) && is_numeric($_REQUEST['pollID'])) {
		$currentPoll = getPoll($_REQUEST['pollID']);
		if (isset($_REQUEST['answer']) and $_SESSION["voted$currentPoll->id"] == null) {
			$currentPoll->addAnswer($_REQUEST['answer']);
			$_SESSION["voted$currentPoll->id"] = 1;
		}
		$pagePollTitle = 'Poll results';
		$pagePollURL = '<li><a href="' . getSiteRootURL() . buildPastPollResultsURL() . '">Poll results</a></li>';
	}
	else {
		$currentPoll = getCurrentPoll();
		$pagePollTitle = 'Past polls';
		$pagePollURL = '';
	}
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = $pagePollTitle;
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li>'. $pagePollURL .'<li><span>' .$pagePollTitle .'</span></li>';

	include("poll_past_results.html.php");
?>