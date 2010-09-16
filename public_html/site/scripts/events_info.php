<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduEvents.php");
	include_once("egov/JaduEGovMeetingMinutes.php");
	include_once("egov/JaduCL.php");
	include_once("JaduAppliedCategories.php");

	// use this to store any get info that might need to be passed
	// on if a 'previous' or 'next' link is used
	$getInfo = "";

	// flag to say whether we need to get events for a date range
	// if not we may need to get all events or events for a location
	$getEventsForDateRange = true;
	
	$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);

	// if dates have been supplied then get the events for those dates
	if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
		$startDate = $_GET['startDate'];
		$endDate = $_GET['endDate'];
		$getInfo = "startDate=" . $startDate . "&amp;endDate=" . $endDate;
	}
	// if a specific period has been requested then display the events
	elseif(isset($_GET['period'])) {
		$period = $_GET['period'];
		$getInfo = "period=$period";
		
		if ($period == "thisWeek") {
			$days = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
			$dayOffset = array_search(date("D"),$days);
			$startDate = date("d-m-Y", mktime(0, 0, 0, date("m"), date("d")-$dayOffset, date("Y")));
			list ($day, $month, $year) = split ('[/.-]', $startDate);
			$endDate = date("d-m-Y", mktime(0, 0, 0, $month, $day+7, $year));
		}
		if ($period == "nextWeek") {
			$days = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
			$dayOffset = array_search(date("D"),$days);
			$startDate = date("d-m-Y", mktime(0, 0, 0, date("m"), date("d")-$dayOffset, date("Y")));
			list ($day, $month, $year) = split ('[/.-]', $startDate);
			$endDate = date("d-m-Y", mktime(0, 0, 0, $month, $day+7, $year));

			list ($day, $month, $year) = split ('[/.-]', $endDate);
			$startDate = date("d-m-Y", mktime(0, 0, 0, $month, $day+1, $year));
			list ($day, $month, $year) = split ('[/.-]', $startDate);
			$endDate = date("d-m-Y", mktime(0, 0, 0, $month, $day+7, $year));
		}
		if ($period == "thisMonth") {
			$startDate = date("d-m-Y", mktime(0, 0, 0, date("m"), 1, date("Y")));
			$endDate = date("d-m-Y", mktime(0, 0, 0, date("m")+1, 1-1, date("Y")));
		}
		if ($period == "nextMonth") {
			$thisMonthEnd = date("d-m-Y", mktime(0, 0, 0, date("m")+1, 1-1, date("Y")));
			list ($day, $month, $year) = split ('[/.-]', $thisMonthEnd);
			if ($month > 12) {
				$year = $year + 1;
			}
			$startDate = date("d-m-Y", mktime(0, 0, 0, $month+1, 1, $year));
			list ($day, $month, $year) = split ('[/.-]', $startDate);
			$endDate = date("d-m-Y", mktime(0, 0, 0, $month+1, 1-1, $year));
		}
		if ($period == "full") {
			$allEvents = getAllEvents('startDate');
			$getEventsForDateRange = false;
		}
	}
	// if a location has been provided then get the events for that location
	elseif (isset($_REQUEST['location'])) {
		$getInfo = "location=" . urlencode($_REQUEST['location']);
		$allEvents = getEventsForLocation($_REQUEST['location']);
		$location = htmlentities($_REQUEST['location'], ENT_QUOTES, 'UTF-8');
		$getEventsForDateRange = false;
	}
	else {
		$days = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
		$dayOffset = array_search(date("D"),$days);
		$startDate = date("d-m-Y", mktime(0, 0, 0, date("m"), date("d")-$dayOffset, date("Y")));
		list ($day, $month, $year) = split ('[/.-]', $startDate);
		$endDate = date("d-m-Y", mktime(0, 0, 0, $month, $day+7, $year));
	}

	// get the events between the start and end dates
	if ($getEventsForDateRange) {
		$allEvents = getEventsForDateRange($startDate, $endDate);
		$meetings = getMeetingsInDateRangeAsEvents($startDate, $endDate);
		if (!empty($meetings)) {
			for ($i = 0; $i < sizeof($meetings); ++$i) {
				$temp_date = split('[/.-]', $meetings[$i]->startDate);
				$meetings[$i]->startDate = mktime(0,0,0,$temp_date[1], $temp_date[2], $temp_date[0]);
				$temp_date = split('[/.-]', $meetings[$i]->endDate);
				$meetings[$i]->endDate = mktime(0,0,0,$temp_date[1], $temp_date[2], $temp_date[0]);
				$temp_date = split('[/.-]', $meetings[$i]->nextDate);
				$meetings[$i]->nextDate = mktime(0,0,0,$temp_date[1], $temp_date[2], $temp_date[0]);
			}
		}

		$allEvents = array_merge($allEvents, $meetings);
	}

	if (isset($_GET['eventID']) && is_numeric($_GET['eventID'])) {
		$event = getEvent($_GET['eventID']);
		$allEvents = array();
		$allEvents[] = $event;
	}

	$events = array();
	$liveEvents = array();
	foreach ($allEvents as $event) {
		if ($event->live == 1) {
			$liveEvents[] = $event;
		}
	}
	
	if (sizeof($allEvents) > 0) {
		$categoryID = getFirstCategoryIDForItemOfType (EVENTS_CATEGORIES_TABLE, $allEvents[0]->id, BESPOKE_CATEGORY_LIST_NAME);	
		$currentCategory = $lgclList->getCategory($categoryID);
		$dirTree = $lgclList->getFullPath($categoryID);
	}

	// the number of events that have been requested
	$totalEvents = sizeof($liveEvents);

	// the number of events to display on each page
	$numToDisplay = 6;

	$eventOffset = 0;
	if (isset($_GET['offset'])) {
		$eventOffset = $_GET['offset'];
	}
	$originalOffset = $eventOffset + 1;
	$limit = $eventOffset + $numToDisplay;

	while (($eventOffset < $limit) && ($eventOffset < $totalEvents)) {
		$events[] = $liveEvents[$eventOffset];
		$eventOffset++;
	}
	
	$breadcrumb = 'eventsInfo';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Events | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="events, whats on, clubs, meetings, leisure, out and about, things to do, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s events calendar for whats on where and when in the local area" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Whats on Events" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s events calendar for whats on where and when in the local area" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
		<!-- all that days events listed -->
		<h2>
<?php
	if (isset($location)) {
		print "Events at " . $location;
	}
	elseif(isset($_GET['period']) || $_POST['period'] == "full") {
		print "All Events";
	 }
	 elseif($startDate == $endDate) {
		  list ($day, $month, $year) = split ('[/.-]', $startDate);
		  $date = date("jS M y", mktime(0, 0, 0, $month, $day, $year));
		  print "Events for $date";
	 }
	 else {
		list ($day, $month, $year) = split ('[/.-]', $startDate);
		$sdate = date("jS M", mktime(0, 0, 0, $month, $day, $year));
		list ($day, $month, $year) = split ('[/.-]', $endDate);
		$edate = date("jS M y", mktime(0, 0, 0, $month, $day, $year));
		print "Events for $sdate - $edate";
	}
?>
		</h2>
            
<?php
	if (sizeof($events) < 1) {
		print "<h2>There are no events for this period</h2>";
	}
	else {
?>
		<p class="first">Showing <strong><?php print $originalOffset;?></strong> to <strong><?php print $eventOffset;?></strong> of <strong><?php print $totalEvents;?></strong> events</p>
		
<?php 
	}

 	// display the events
	// if we're displaying a full list then display the interval and start and end dates
	foreach ($events as $event){
		$event->title = htmlentities(strip_tags(utf8_decode($event->title)), ENT_QUOTES, 'UTF-8');
		print "<div><h2 class=\"decorative\"><span>$event->title</span></h2>\n";
		if (($_POST['period'] == 'full') || ($_GET['period'] == 'full') || ($_POST['location'])) {
			if ($event->startDate == $event->endDate) {
				if (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/", $event->startDate)) {
					list ($year, $month, $day) = split ('[/.-]', $event->startDate);
					$date = date("jS M y", mktime(0, 0, 0, $month, $day, $year));
					print "<p class=\"first\"><strong>Date: $date</strong></p>\n";
				}
				else {
					$date = date("jS M y", $event->startDate);
					print "<p class=\"first\"><strong>Date: $date</strong></p>\n";
				}
			}
			else {
				if (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/", $event->startDate) && preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/", $event->endDate)) {	
					list ($year, $month, $day) = split ('[/.-]', $event->startDate);
					$start = date("jS M y", mktime(0, 0, 0, $month, $day, $year));
					list ($year, $month, $day) = split ('[/.-]', $event->endDate);
					$finish = date("jS M y", mktime(0, 0, 0, $month, $day, $year));
					print "<p class=\"first\"><strong>Date: $start - $finish</strong></p>\n";
				}
				else {
					$start = date("jS M y", $event->startDate);
					$finish = date("jS M y", $event->endDate);
					print "<p class=\"first\"><strong>Date: $start - $finish</strong></p>\n";
				}
			}
			if ($event->interval == "day" && $event->startDate != $event->endDate)
				 print "<p><strong>Every day</strong></p>\n";
			if ($event->interval == "weekly")
				 print "<p><strong>Every week</strong></p>\n";
			if ($event->interval == "fortnight")
				 print "<p><strong>Every fortnight</strong></p>\n";
			if ($event->interval == "monthByDay")
				 print "<p><strong>On this day every month</strong></p>\n";
			if ($event->interval == "monthByDate")
				 print "<p><strong>On this date every month</strong></p>\n";
		}
  		else {
			
			if (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/", $event->nextDate)) {
				list ($year, $month, $day) = split ('[/.-]', $event->nextDate);
				$date = date("jS M y", mktime(0, 0, 0, $month, $day, $year));
			}
			else {
				$date = date("jS M y", $event->nextDate);
			}
			
			if ($event->startDate == $event->endDate)
				print "<p><strong>Date: $date</strong></p>\n";
			else {
				if (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/", $event->startDate) && preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/", $event->endDate)) {
					list ($year, $month, $day) = split ('[/.-]', $event->startDate);
					$start = date("jS M y", mktime(0, 0, 0, $month, $day, $year));
					list ($year, $month, $day) = split ('[/.-]', $event->endDate);
					$finish = date("jS M y", mktime(0, 0, 0, $month, $day, $year));
					print "<p><strong>Date: $start - $finish</strong></p>\n";
				}
				else {
					$start = date("jS M y", $event->startDate);
					$finish = date("jS M y", $event->endDate);
					print "<p class=\"first\"><strong>Date: $start - $finish</strong></p>\n";
				}
			}
			if ($event->interval == "day" && $event->startDate != $event->endDate)
				 print "<p><strong>Every day</strong></p>\n";
			if ($event->interval == "weekly")
				 print "<p><strong>Every week</strong></p>\n";
			if ($event->interval == "fortnight")
				 print "<p><strong>Every fortnight</strong></p>\n";
			if ($event->interval == "monthByDay")
				 print "<p><strong>On this day every month</strong></p>\n";
			if ($event->interval == "monthByDate")
				 print "<p><strong>On this date every month</strong></p>\n";
		}
						
		$event->location = htmlentities(strip_tags(utf8_decode($event->location)), ENT_QUOTES, 'UTF-8');
		print "<p><strong>Location: $event->location</strong></p><p>\n";
	
		$timeString = "";
		if (!empty($event->startTime) && $event->startTime != "00:00") {
			$timeString .= "$event->startTime";
		}
		if (!empty($event->endTime) && $event->endTime != "00:00") {
			if ($timeString != "") {
				$timeString .= " - ";
			}
			$timeString .= "$event->endTime";
		}
		if ($timeString != "") {
			print "<p><strong>Time: $timeString</strong></p>\n";
		}
		if (!empty($event->cost)) {
			$event->cost = htmlentities(strip_tags(utf8_decode($event->cost)), ENT_QUOTES, 'UTF-8');
			print "<p><strong>Cost: $event->cost</strong></p>";
		}	
		if(is_numeric($event->id)) {
			if (!empty($event->summary)) {
				$event->summary = htmlentities(strip_tags(utf8_decode($event->summary)), ENT_QUOTES, 'UTF-8');
				print "<p class=\"first\">$event->summary</p>\n";
			}	
		}
		else {
			$id = str_replace('m', '', $event->id);
			print "<p class=\"first\">More information on <a href=\"http://".$DOMAIN."/site/scripts/meetings_info.php?meetingID=".$id."\">".$event->title."</a></p>\n";
		}
		if (!empty($event->description)) {
			print "<div class=\"byEditor\">$event->description</div>\n";
		}

		print "</div>";
	}

	// if the original offset > 0 then there must 
	// be some previous events to display
	 if ($originalOffset > 1) {
?>
		<p>
			<a href="http://<?php print $DOMAIN; ?>/site/scripts/events_info.php?<?php print $getInfo;?>&amp;offset=<?php print $originalOffset-$numToDisplay-1; ?>">
				&laquo; Previous Page
			</a>
		</p>
<?php
	 }
				 // if there's more to be displayed show a next button
	if ($eventOffset < $totalEvents){
?>
		<p>
			<a href="http://<?php print $DOMAIN; ?>/site/scripts/events_info.php?<?php print $getInfo;?>&amp;offset=<?php print $eventOffset; ?>">
				&raquo; Next Page
			</a>
		</p>
<?php
	 } 
?>
		<!-- END events list -->
			<form action="http://<?php print $DOMAIN; ?>/site/scripts/events_info.php" method="get" class="basic_form newsSelect">
			<p>
				<label for="SeeWhatsOn">See what's on</label>
            	<select name="period" class="select" id="SeeWhatsOn" >
					<optgroup label="What's On When">
					<option value="thisWeek">This Week</option>
					<option value="nextWeek">Next Week</option>
					<option value="thisMonth">This Month</option>
					<option value="nextMonth">Next Month</option>
					<option value="full">Full List</option>
				</optgroup>
				</select>
				<input type="submit" class="button" value="Go" />
			</p>
			</form>

			<form action="http://<?php print $DOMAIN; ?>/site/scripts/events_info.php" method="post" class="basic_form newsSelect">
				<p>
				<label for="Placestogo">Places to go</label>
					<select name="location" class="select" id="Placestogo">
						 <optgroup label="What's On Where">
	<?php
			$locations = getLocations();
			foreach($locations as $location) {
				if (!empty($location)) {
					$location = htmlentities($location, ENT_QUOTES, 'UTF-8');
					$shortlocation = substr(htmlentities($location, ENT_QUOTES, 'UTF-8'), 0, 50);
	?>
					<option value="<?php print $location; ?>"><?php print $shortlocation; ?></option>
	<?php
				}
			}
	?>
					</optgroup>
					</select>
					<input type="submit" class="button" value="Go" />
				</p>
			</form>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>