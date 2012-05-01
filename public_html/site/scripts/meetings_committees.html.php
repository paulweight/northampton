<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($header->title); ?> - <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="committee, meetings, minutes, agendas, <?php print encodeHtml($header->title); ?>, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Agendas, Reports and Minutes for <?php print encodeHtml($header->title); ?>" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Agendas, Reports and Minutes - <?php print encodeHtml($header->title); ?>" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Agendas, Reports and Minutes for <?php print encodeHtml($header->title); ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
				
<?php
	if ($header !== -1) {
?>

	<h2><?php print encodeHtml($header->title); ?></h2>
	
<?php
		if (sizeof($allMeetings) > 0) {
?>
		
	<p>Listed in order of date below are the meetings held by the <?php print encodeHtml($header->title); ?>.</p>
	<ul class="list icons meetings">
<?php
			foreach ($allMeetings as $meeting) {
?>
		<li><a href="<?php print getSiteRootURL() . buildMeetingsURL(-1, 'meeting', $meeting->id); ?>"><?php print $meeting->getMeetingMinutesDateFormatted(FORMAT_DATE_FULL); ?></a></li>
<?php
			}
?>
	</ul>
			
<?php
		}
		else {
?>
	<p>There are no meetings currently listed for the <?php print encodeHtml($header->title); ?>. Please come back soon as these pages are updated regularly.</p>
<?php 
		}

	} 
	else {
?>
	<h2>Sorry, this committee is not presently available</h2>
			
<?php 
	}
?>	
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>