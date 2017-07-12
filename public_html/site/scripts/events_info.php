<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduEvents.php");
	include_once('JaduImages.php');
	include_once("egov/JaduEGovMeetingMinutes.php");
	include_once("JaduAppliedCategories.php");
	include_once('library/JaduNetworkFunctions.php');
	
	// Re-direct a period submit by post to a readable get
	if (isset($_POST['period'])) {
		header('Location: http://'. DOMAIN . buildEventsURL(-1,$_POST['period']));
		exit;
	}

	if (isset($_GET['eventID'])) {
		// Default to approved/live
		$approvedOnly = true;
		$liveOnly = true;

		// Check whether an administrator is previewing the page
		$allowPreview = (isset($isPreviewLink) && $isPreviewLink && isset($allowPreview) && $allowPreview);
		if($allowPreview) {
			$approvedOnly = $liveOnly = false;
		}
		
		// Get an individual event
		$event = getEvent($_GET['eventID'], $approvedOnly, $liveOnly);
		// Set categoryID in GET so that related items are shown
		$_GET['categoryID'] = getFirstCategoryIDForItemOfType(EVENTS_CATEGORIES_TABLE, $event->id, BESPOKE_CATEGORY_LIST_NAME);
		$allEvents = array($event);
	}
	else if (isset($_REQUEST['location'])) {
		// Get all events for a location
		$allEvents = getEventsForLocation($_REQUEST['location']);
		$location = $_REQUEST['location'];
	}
	else if (isset($_GET['startDate']) && $_GET['startDate'] != -1 && isset($_GET['endDate']) && $_GET['endDate'] != -1) {
		$startDate = $_GET['startDate'];
		$endDate = $_GET['endDate'];
		
		$startTimestamp = 0;
		$startParts = split('[/.-]', $startDate);
		if (count($startParts) == 3) {
			$startTimestamp = mktime(0, 0, 0, $startParts[1], $startParts[0], $startParts[2]);
		}
		
		$endTimestamp = 0;
		$endParts = split('[/.-]', $endDate);
		if (count($endParts) == 3) {
			$endTimestamp = mktime(0, 0, 0, $endParts[1], $endParts[0], $endParts[2]);
		}
	}
	else {
		$period = isset($_GET['period']) ? $_GET['period'] : null;
		switch ($period) {
			default:
			case 'thisWeek':
				// Monday to Sunday
				$startTimestamp = mktime(0, 0, 0, date('m'), date('d')-(date('N')-1), date('Y'));
				$startDate = date('d-m-Y', $startTimestamp);
				$endTimestamp = mktime(0, 0, 0, date('m', $startTimestamp), date('d', $startTimestamp)+6, date('Y', $startTimestamp));
				$endDate = date('d-m-Y', $endTimestamp);
				break;
				
			case 'nextWeek':
				$startTimestamp = mktime(0, 0, 0, date('m'), date('d')+(7-date('N')), date('Y'));
				$startDate = date('d-m-Y', $startTimestamp);
				$endTimestamp = mktime(0, 0, 0, date('m', $startTimestamp), date('d', $startTimestamp)+6, date('Y', $startTimestamp));
				$endDate = date('d-m-Y', $endTimestamp);
				break;
				
			case 'thisMonth':
				$startTimestamp = mktime(0, 0, 0, date('m'), 1, date('y'));
				$startDate = date('d-m-Y', $startTimestamp);
				$endTimestamp = mktime(0, 0, 0, date('m'), date('t'), date('y'));
				$endDate = date('d-m-Y', $endTimestamp);
				break;
				
			case 'nextMonth':
				$startTimestamp = mktime(0, 0, 0, date('m')+1, 1, date('y'));
				$startDate = date('d-m-Y', $startTimestamp);
				$endTimestamp = mktime(0, 0, 0, date('m', $startTimestamp), date('t', $startTimestamp), date('y', $startTimestamp));
				$endDate = date('t-m-Y', $endTimestamp);
				break;
			
			case 'full':
				// Get all events ordered by the startDate
				$allEvents = getAllFutureEvents('startDate', true, true);
				break;
		}
	}
	
	// Get all events within a given date range
	if (!isset($allEvents)) {
		$events = getEventsForDateRange($startDate, $endDate);
		$meetingEvents = getMeetingsInDateRangeAsEvents($startDate, $endDate);
		$allEvents = array_merge($events, $meetingEvents);
	}
	
	// Only show the live events
	$events = array();
	$liveEvents = array();
	foreach ($allEvents as $event) {
		if (!$liveOnly || $event->live == 1) {
			$liveEvents[] = $event;
		}
	}

	// The number of events that have been requested
	$totalEvents = sizeof($liveEvents);

	// The number of events to display on each page
	$numToDisplay = 5;
	
	$offset = 0;
	if (isset($_GET['offset'])) {
		$offset = $_GET['offset'];
	}
	$events = array_slice($liveEvents, $offset, $numToDisplay);
	$numEvents = sizeof($events);
	
	// Breadcrumb, H1 and Title
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildEventsURL() .'">Events</a></li>';
	if (isset($location)) {
		$MAST_BREADCRUMB .= '<li><span>Events at' . encodeHtml($location) .'</span></li>';
		$MAST_HEADING = 'Events at '. $location;
	}
	else if (isset($period) && $period == 'full') {
		$MAST_BREADCRUMB .= '<li><span>All Events</span></li>';
		$MAST_HEADING = 'All Events';
	}
	else if (isset($_GET['eventID'])) {
		$MAST_BREADCRUMB .= '<li><span>' . encodeHtml($event->title) . '</span></li>';
		$MAST_HEADING = $event->title;
	}
	else if ($startTimestamp == $endTimestamp) {
		$MAST_BREADCRUMB .= '<li><span>' . formatDateTime(FORMAT_DATE_MEDIUM, $startTimestamp) . '</span></li>';
		$MAST_HEADING = 'Events on '. formatDateTime(FORMAT_DATE_MEDIUM, $startTimestamp);
	}
	else {
		$MAST_BREADCRUMB .= '<li><span>Events</span></li>';
		$MAST_HEADING = 'Events';
	}
	
	include("events_info.html.php");
?>