<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
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

	<!-- Display the 'Pick of the week' -->
<?php
	$pick = getPickOfWeek();
	if ($pick != null) {
?>
	<h2><?php print encodeHtml($pick->title); ?></h2>
	<ul class="list icons events">
		<li class="long"><strong>Date:</strong> <?php print $pick->getDateString(); ?></li>
<?php
		$intervalString = $pick->getIntervalString();
		if (!empty($intervalString)) {
?>
		<li class="long"><strong><?php print $intervalString; ?></strong></li>
<?php
		}
?>
		<li class="long"><strong>Location:</strong> <?php print encodeHtml($pick->location); ?></li>
<?php
		$timeString = $pick->getTimeString();
		if (!empty($timeString)) {
?>
		<li class="long"><strong>Time:</strong> <?php print encodeHtml($timeString); ?></li>
<?php
		}
?>
		<li class="long"><strong>Cost:</strong> <?php print encodeHtml($pick->cost); ?></li>
	</ul>
	<p><strong><?php print encodeHtml($pick->summary); ?></strong></p>
	

<?php
		if (!empty($pick->description)) {
?>
	<div class="byEditor article">
		<?php print processEditorContent($pick->description); ?>
	</div>
<?php
		}
?>

<?php
	}
	else {
?>
	<h2>There are currently no events</h2>
<?php
	}
?>

	<!-- All events for today -->
<?php
	if (count($liveEvents) == 0) {
		// If there are no events for today then say so
?>
	<p>There are no events for today, <?php print formatDateTime(FORMAT_DATE_LONG); ?></li>
<?php
	}
	else {
		// Otherwise display them
?>
	<h2>Happening today, <?php print formatDateTime(FORMAT_DATE_LONG); ?></h2>
<?php
		foreach ($liveEvents as $event) {
?><h3><?php print encodeHtml($event->title); ?></h3>
	<ul class="list icons events">
		
		<li class="long"><strong>Date:</strong> <?php print $event->getDateString(); ?></li>
<?php
			$intervalString = $event->getIntervalString();
			if (!empty($intervalString)) {
?>
		<li class="long"><strong><?php print $intervalString; ?></strong></li>
<?php
			}
?>
		<li class="long"><strong>Location:</strong> <?php print encodeHtml($event->location); ?></li>
<?php
			$timeString = $event->getTimeString();
			if (!empty($timeString)) {
?>
		<li class="long"><strong>Time:</strong> <?php print encodeHtml($timeString); ?></li>
<?php
			}
?>
		<li class="long"><strong>Cost:</strong> <?php print encodeHtml($event->cost); ?></li>
	</ul>
		<p><strong><?php print encodeHtml($event->summary); ?></strong></p>
	
<?php
			if (!empty($event->description)) {
?>
	<div class="byEditor article">
		<?php print processEditorContent($event->description); ?>
	</div>
<?php
			}
		}
	}
?>

	<p><a class="rss" href="<?php print getSiteRootURL() . buildRSSURL('events');?>">Events RSS feed</a></p>
	
	

	<?php include('../includes/event_selection.php'); ?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>