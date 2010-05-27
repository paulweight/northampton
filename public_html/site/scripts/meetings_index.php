<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("egov/JaduEGovMeetingMinutes.php");
	include_once("egov/JaduCL.php");
	include_once("../includes/lib.php");

	$breadcrumb = 'meetingsIndex';

	$mostRecent = getLastXMeetingMinutes(10, true);

	$allHeaders = getAllMeetingMinutesHeaders();
	$splitArray = splitArray($allHeaders);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Agendas, reports and minutes | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="committee, meetings, minutes, agendas, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s directory of Agendas, Reports and Minutes" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Agendas, Reports and Minutes" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s directory of Agendas, Reports and Minutes" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
		
	<p class="first">To view a full archive of meetings, select a committee heading.</p>


<?php 
	if (sizeof($mostRecent) > 0) {
?>      
		<h2>Most Recent Meetings</h2>
		<div class="cate_info">
			<ol class="list">
	<?php 
			foreach($mostRecent as $index => $item) {
				$header = getMeetingMinutesHeader($item->headerID);
	?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/meetings_info.php?meetingID=<?php print $item->id;?>"><?php print $header->title;?></a> <span class="note">- <?php print $item->getMeetingMinutesDateFormatted('l jS F Y'); ?></span></li>
	<?php
			}
	?>
			</ol>
		</div>
<?php
	}
?>


	  <div class="cate_info">
<?php
	if(sizeof($splitArray['left']) > 0) {
		print '<ul class="info_left list">';
		foreach ($splitArray['left'] as $l) {
?>	
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/meetings_committees.php?headerID=<?php print $l->id;?>"><?php print $l->title; ?></a></li>
<?php
		}
		print '</ul>';
	}
	if (sizeof($splitArray['right']) > 0) {
		print '<ul class="info_right list">';
		foreach ($splitArray['right'] as $r) {
?>	
			 <li><a href="http://<?php print $DOMAIN; ?>/site/scripts/meetings_committees.php?headerID=<?php print $r->id;?>"><?php print $r->title; ?></a></li>
<?php
		}
		print '</ul>';
	}
?>
	  </div>


			
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>