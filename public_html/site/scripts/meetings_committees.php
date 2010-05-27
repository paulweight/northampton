<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("egov/JaduEGovMeetingMinutes.php");
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");

	$allMeetings = array();

	if (isset($_GET['headerID']) && is_numeric($_GET['headerID'])) {
		$header = getMeetingMinutesHeader($_GET['headerID']);
		if ($header != -1) {
			$allMeetings = getAllMeetingMinutesForHeader ($_GET['headerID'], true);
		}
		else {
			header("Location: ./meetings_index.php");
			exit();
		}
	}
	else {
		header("Location: ./meetings_index.php");
		exit();
	}

	$breadcrumb = 'meetingsCommittees';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $header->title; ?> | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="committee, meetings, minutes, agendas, <?php print $header->title; ?>, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Agendas, Reports and Minutes for <?php print $header->title; ?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Agendas, Reports and Minutes - <?php print $header->title; ?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Agendas, Reports and Minutes for <?php print $header->title; ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
				
<?php
	if ($header != -1) {
?>

	<h2><?php print $header->title;?></h2>
	
<?php
		if (sizeof($allMeetings) > 0) {
?>
		
	<p class="first">Listed in order of date, are the meetings held by the <strong><?php print $header->title;?></strong>.</p>
	
	<div class="cate_info">
		<ul>
<?php
			foreach ($allMeetings as $meeting) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/meetings_info.php?meetingID=<?php print $meeting->id;?>"><?php print $meeting->getMeetingMinutesDateFormatted('l jS F Y'); ?></a></li>
<?php
			}
?>
		</ul>
	</div>				
			
<?php
		}
		else {
?>
	<p class="first">There are no meetings currently listed for the <?php print $header->title;?>. Please come back soon as these pages are updated regularly.</p>
<?php 
		}

	} 
	else {
?>
	<h2>Sorry, this committee is not presently available.</h2>
			
<?php 
	}
?>	

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>