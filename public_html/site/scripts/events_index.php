<?php
	header('Location: /homepage/407/what_s_on_in_northampton');
	exit;

	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduEvents.php");
	include_once("egov/JaduEGovMeetingMinutes.php");
	include_once("JaduAppliedCategories.php");
	
	$today = date('d-m-Y');
	$events = getEventsForDateRange($today, $today);
	$meetingEvents = getMeetingsInDateRangeAsEvents($today, $today);
	$allEvents = array_merge($events, $meetingEvents);
	
	// Filter out the events that are not live
	$liveEvents = array();
	foreach ($allEvents as $event) {
		if ($event->live == 1) {
			$liveEvents[] = $event;
		}
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Events';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Events</span></li>';
	
	include("events_index.html.php");
?>
