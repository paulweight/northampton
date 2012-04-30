<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("websections/JaduFeedManager.php");
	include_once("websections/JaduRSS.php");
	include_once("websections/JaduAtom.php");

	$criteria = array(
		'orderBy' => 'position'
	);
	$allFeeds = getAllExternalFeeds($criteria); 
	$viewFeed = false;
	if (isset($_GET['view']) && $_GET['view'] == 'feed' && isset($_GET['feedID'])) {
	    $feed = getExternalFeed($_GET['feedID']);
	    if ($feed !== null) {
	        $parsedFeed = parseExternalFeed($feed->url);
    		$viewFeed = true;
	    }
	    else {
	    	include_once(HOME . '/404.php');
		exit;	
	    }
	}

	$title = 'External Feeds - ' . METADATA_GENERIC_NAME;
	$dateFormat = FORMAT_DATE_SHORT . ' ' . FORMAT_TIME_SHORT;
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'External RSS feeds';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li>';
	if ($viewFeed) {
		$MAST_BREADCRUMB .=  '<li><a href="' . getSiteRootURL(). buildFeedsURL().'">External RSS feeds</a></li><li><span>'. encodeHtml($feed->name) .'</span></li>';
	}
	else {
		$MAST_BREADCRUMB .= '<li><span>External RSS feeds</span></li>';
	}
	
	include("view_feeds.html.php");
?>