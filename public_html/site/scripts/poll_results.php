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
	}
	else {
		$currentPoll = getCurrentPoll();
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Poll results';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Poll results</span></li>';
	
	include("poll_results.html.php");
?>