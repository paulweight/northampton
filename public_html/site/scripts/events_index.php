<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduEvents.php");

	$days = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");

	// work out the start and end date of the current week
	$dayOffset = array_search(date("D"),$days);
	$thisWeekStart = date("d-m-Y", mktime(0, 0, 0, date("m"), date("d")-$dayOffset, date("Y")));
	list ($day, $month, $year) = split ('[/.-]', $thisWeekStart);
	$thisWeekEnd = date("d-m-Y", mktime(0, 0, 0, $month, $day+7, $year));

	// work out the start and end date of the next week
	list ($day, $month, $year) = split ('[/.-]', $thisWeekEnd);
	$nextWeekStart = date("d-m-Y", mktime(0, 0, 0, $month, $day+1, $year));
	list ($day, $month, $year) = split ('[/.-]', $nextWeekStart);
	$nextWeekEnd = date("d-m-Y", mktime(0, 0, 0, $month, $day+7, $year));

	// work out the start and end date of this month
	$thisMonthStart = date("d-m-Y", mktime(0, 0, 0, date("m"), 1, date("Y")));
	$thisMonthEnd = date("d-m-Y", mktime(0, 0, 0, date("m")+1, 1-1, date("Y")));

	// work out the start and end date of next month
	list ($day, $month, $year) = split ('[/.-]', $thisMonthEnd);
	if ($month > 12) {
		$year = $year + 1;
	}
	$nextMonthStart = date("d-m-Y", mktime(0, 0, 0, $month+1, 1, $year));
	list ($day, $month, $year) = split ('[/.-]', $nextMonthStart);
	$nextMonthEnd = date("d-m-Y", mktime(0, 0, 0, $month+1, 1-1, $year));

	$breadcrumb = 'eventsIndex';
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
    
		<!-- Display the 'Pick of the week' -->
<?php
	$pick = getPickOfWeek();
	if ($pick->id != null) {
		$pick->cost = htmlentities(strip_tags(utf8_decode($pick->cost)), ENT_QUOTES, 'UTF-8');
		$pick->location = htmlentities(strip_tags(utf8_decode($pick->location)), ENT_QUOTES, 'UTF-8');
?>
    
		<h2><?php print htmlentities($pick->title, ENT_QUOTES, 'UTF-8');?></h2>
<?php		
			if ($pick->startDate == $pick->endDate) {
				$date = date("jS M y", $pick->startDate);
				print "<p class=\"first\"><strong>$date</strong></p>\n";
			}
			else {
				$start = date("jS M y", $pick->startDate);
				$finish = date("jS M y", $pick->endDate);
				print "<p class=\"first\"><strong>$start - $finish</strong></p>\n";
			}
?>

<?php
   		if (!empty($pick->summary)) {
?>
			<p class="first"><?php print htmlentities($pick->summary, ENT_QUOTES, 'UTF-8');?></p>
			<p><strong>Time: <?php print $pick->startTime;?> - <?php print $pick->endTime;?></strong></p>
			<p><strong>Location: <?php print $pick->location;?></strong></p>
<?php
			if(trim($pick->cost) != '') {
?>
			<p><strong>Cost: <?php print $pick->cost;?></strong></p>

<?php
			}
   		}

		if (!empty($pick->description)) {
	   		print '<div class="byEditor">'.$pick->description.'</div>';
		}
?>
 		<br />
<?php
	}
	else {
?>
		<h2>There are currently no events to display</h2>
<?php
	}
?>

		<!-- all that days events listed -->
<?php
	$today = date("d/m/Y");

	// get all of the events for today
	$todaysEvents = getEventsForDate($today);

	// filter out the events that are not live
	$todaysLiveEvents = array();
	foreach ($todaysEvents as $event) {
		if ($event->live == 1) {
			 $todaysLiveEvents[] = $event;
		}
	}

	// if there are no events for today then say so
	if (sizeof($todaysLiveEvents) == 0){
		print "<p><strong>There are no events scheduled for today, " . date("jS F y"). "</strong></p>";
	}

	// otherwise display them
	else {
		print "<h3 class=\"decorative\">Happening Today, " . date("jS M y") . "</h3>";
	}

	foreach ($todaysLiveEvents as $event) {
		$event->title = htmlentities(strip_tags(utf8_decode($event->title)));
		$event->location = htmlentities(strip_tags(utf8_decode($event->location)));
		$event->summary = htmlentities(strip_tags(utf8_decode($event->summary)));
		print "<div><h4>$event->title</h4>\n";
		print "<p><strong>Location: $event->location</strong></p>\n";
		print "<p><strong>Time: $event->startTime</strong>\n";
		if (!empty($event->endTime)){
			 print " - <strong>$event->endTime</strong>";
		}
		print "</p>\n";
		$event->cost = htmlentities(strip_tags(utf8_decode($event->cost)), ENT_QUOTES, 'UTF-8');
		if(trim($event->cost) != '') {
			print "<p><strong>Cost: $event->cost</strong></p>";
		}
		print "<p class=\"first\">$event->summary</p>";
		print "<div class=\"byEditor\">$event->description</div>";
		print "</div>\n";
	}
?>
		<!-- END events list -->
		<br class="clear" />
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
			<br class="clear" />

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->


<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>