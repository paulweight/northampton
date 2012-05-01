<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
<?php 
		include_once("../includes/stylesheets.php");
		if (isset($_GET['eventID']) && $_GET['eventID'] > 0) { 
			printMetadata(EVENTS_METADATA_TABLE, EVENTS_CATEGORIES_TABLE, $event->id, $event->title, 
				"http://".DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
		}
		include_once("../includes/metadata.php");
?>

	<meta name="Keywords" content="events, whats on, clubs, meetings, leisure, out and about, things to do, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>s events calendar for whats on where and when in the local area" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Whats on Events" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>s events calendar for whats on where and when in the local area" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
	
	<!-- All that days events listed -->
	<h2>
<?php
	if (isset($_REQUEST['location'])) {
		print 'Events at ' . encodeHtml($_REQUEST['location']);
	}
	else if (isset($startTimestamp) && isset($endTimestamp)) {
		if ($startTimestamp == $endTimestamp) {
			print 'Events for ' . formatDateTime(FORMAT_DATE_MEDIUM, $startTimestamp);
		}
		else {
			print 'Events for ' . formatDateTime(FORMAT_DATE_MEDIUM, $startTimestamp) . ' - ' . formatDateTime(FORMAT_DATE_MEDIUM, $endTimestamp);
		}
	}
	else if (!isset($_GET['eventID'])) {
		print 'All Events';
	}
?>
	</h2>
			
<?php
	if (sizeof($events) < 1) {
?>
	<h2>There are no events for this period</h2>
<?php
	}
	else if (!isset($_GET['eventID'])) {
?>
	<p>Showing <?php print (int) $offset + 1; ?> to <?php print (int) $offset + $numEvents; ?> of <?php print (int) $totalEvents; ?> events.</p>	
<?php
	}
	
	foreach ($events as $event) {
?>
	<h3><?php print encodeHtml($event->title); ?></h3>
	<ul class="list icons events">
		<li class="long">Date: <?php print $event->getDateString(); ?></li>
<?php
		$intervalString = $event->getIntervalString();
		if (!empty($intervalString)) {
?>
		<li class="long"><?php print $intervalString; ?></li>
<?php
		}
?>
		<li class="long">Location: <?php print encodeHtml($event->location); ?></li>
<?php
		$timeString = $event->getTimeString();
		if (!empty($timeString)) {
?>
		<li class="long">Time: <?php print encodeHtml($timeString); ?></li>
<?php
		}
?>
		<li class="long">Cost: <?php print encodeHtml($event->cost); ?></li>
	</ul>
	<p><strong><?php print encodeHtml($event->summary); ?></strong></p>
	
<?php
		if (!empty($event->description)) {
?>
	<div class="byEditor article">
<?php 
			if ($event->imageURL != '') {
				if (mb_strlen(getImageProperty($event->imageURL, 'longdesc')) > 0) {
?>
				<div class="figcaption">
					<img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($event->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($event->imageURL, 'altText')); ?> " />
					<p><?php print encodeHtml(getImageProperty($event->imageURL, 'longdesc')); ?></p>
				</div>
<?php
				}
				else {
?>
				<img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($event->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($event->imageURL, 'altText')); ?> "" />
<?php 
				}
			}
?>
		<?php print processEditorContent($event->description); ?>
	</div>
<?php
		}
?>

<?php
	}

	// Display pagination if required
	if ($offset > 0) {
?>
		<p><a href="<?php print getSiteRootURL() . encodeHtml(modifyRequestParameters('offset=' . ((int) $offset - $numToDisplay) . (isset($_REQUEST['location']) ? '&location=' . encodeHTML($_REQUEST['location']) : ''))); ?>">&laquo; Previous Page</a></p>
<?php
	}
	if (($offset + $numEvents) < $totalEvents) {
?>
		<p><a href="<?php print getSiteRootURL() . encodeHtml(modifyRequestParameters('offset=' . ((int) $offset + $numEvents) . (isset($_REQUEST['location']) ? '&location=' . encodeHTML($_REQUEST['location']) : ''))); ?>">&raquo; Next Page</a></p>
<?php
	} 
?>

	<p><a class="rss" href="<?php print getSiteRootURL() . buildRSSURL('events');?>">Events RSS feed</a></p>
	
	<?php include('../includes/event_selection.php'); ?>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
