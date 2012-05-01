<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="committee, meetings, minutes, agendas, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s directory of Agendas, Reports and Minutes" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Agendas, Reports and Minutes" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s directory of Agendas, Reports and Minutes" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php 
	if (count($mostRecent) > 0) {
?>      
	<h2>Most Recent Meetings</h2>
	<ul class="list icons meetings">
<?php 
		foreach($mostRecent as $index => $item) {
			$header = getMeetingMinutesHeader($item->headerID);
?>
		<li><a href="<?php print getSiteRootURL() . buildMeetingsURL( -1, 'meeting', $item->id); ?>"><?php print encodeHtml($header->title); ?></a> - <?php print $item->getMeetingMinutesDateFormatted(FORMAT_DATE_FULL); ?></li>
<?php
		}
?>
	</ul>
<?php
	}
?>


<?php
	if (count($allHeaders) > 0) {
?>	
	<h3>Archive</h3>
	<p>To view a full archive of meetings, select a committee heading.</p>
<?php
	if(sizeof($allHeaders) > 0) {
		print '<ul class="list icons meetings">';
		foreach ($allHeaders as $l) {
?>	
			<li><a href="<?php print getSiteRootURL() . buildMeetingsURL( -1, 'committee', $l->id); ?>"><?php print encodeHtml($l->title); ?></a></li>
<?php
		}
		print '</ul>';
	}

	}
	else {
?>
	<p>There are currently no active committee headings.</p>
<?php
	}
?>
<div class="clear"></div>
	
	<p><a href="<?php print getSiteRootURL() . buildMeetingsArchiveURL(); ?>">View all committees headings</a></p>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>