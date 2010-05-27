<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	
	include_once("egov/JaduEGovMeetingMinutes.php");
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");
	include_once("JaduMetadata.php");
	include_once("egov/JaduEGovMeetingMinutesTypes.php");
	include_once("egov/JaduEGovMeetingAttachments.php");
	
	$displayAnchor = false;
	$meeting = -1;

	if (isset($_GET['meetingID']) && is_numeric($_GET['meetingID'])) {
		$meeting = getMeetingMinutes($_GET['meetingID'], true);
	}
	else {
		header("Location: meetings_index.php");
		exit;
	}
	
	// if an attachment has been requested then get it
	if (isset($_GET['attachmentID']) && is_numeric($_GET['attachmentID'])) {
		$attachmentDetails = getMeetingAttachment($_GET['attachmentID']);
		
		if ($meeting == -1) {
			$meeting = getMeetingMinutes($attachmentDetails->meetingID, true);
		}
	}
		
	if ($meeting != -1) {

		$header = getMeetingMinutesHeader($meeting->headerID);

		// get the attachments for the meeting
		$attachmentList = getMeetingAttachments($meeting->id);
		
		$typedAttachments = array();
		foreach ($attachmentList as $att) {
			$typedAttachments[$att->typeID][] = $att;
		}
		
		$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		
		//	in case don't follow hierarchy but find document from search etc.
		if (!isset($_GET['categoryID'])) {
			$categoryID = getFirstCategoryIDForItemOfType (MEETING_MINUTES_CATEGORIES_TABLE, $meeting->id, BESPOKE_CATEGORY_LIST_NAME);
		}
		
		//	Category Links
		$currentCategory = $lgclList->getCategory($_GET['categoryID']);
		$dirTree = $lgclList->getFullPath($_GET['categoryID']);
	}
	else {
		header("Location: meetings_index.php");
		exit;
	}

	$breadcrumb = 'meetingsInfo';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $header->title; ?> | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="committee, meetings, minutes, agendas, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s directory of Agendas, Reports and Minutes" />

	<?php printMetadata(MEETING_MINUTES_METADATA_TABLE, MEETING_MINUTES_CATEGORIES_TABLE, $meeting->id, $header->title, "http://".$DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
		
<?php
	if ($meeting == -1) {
?>
	<h2>Sorry, this meeting is not available</h2>
<?php
	}
	else {
?>
	<h2><?php print $header->title; ?></h2>
	<p class="first"><?php print $meeting->getMeetingMinutesDateFormatted('l jS F Y'); ?></p>

<?php
	if (sizeof($attachmentList) < 1) {
?>
		<h2>There are currently no attachments for this meeting</h2>
<?php 
	}
	else {
		foreach ($typedAttachments as $index => $typeArray) {
			$attachmentType = getMeetingMinutesType($index);
?>
		<ul class="list">
			
<?php
			foreach ($typeArray as $download) {
?>
				<li><a href="<?php if ($download->contentType == "file") print "http://$DOMAIN/downloads/$download->content"; else print "http://$DOMAIN/site/scripts/meetings_info.php?meetingID=".$_GET['meetingID']."&amp;attachmentID=$download->id" . "#meetingContent"; ?>"><?php print $download->title; ?></a> <?php if ($download->contentType == "file" && $download->size > 0) print " - <span class=\"note\">Download size ".$download->getHumanReadableSize()."</span>";?></li>
<?php
			}
?>			
		</ul>
<?php
		}
?>
				
				<!-- Meeting Attachment -->
<?php
			if (isset($_GET['attachmentID']) && $attachmentDetails != -1) {
				if ($attachmentDetails->contentType == "manual") {
					$t = getMeetingMinutesType($attachmentDetails->typeID);
					$typeName = $t->type;
					$displayAnchor = true;
?>
	
	<h3 id="meetingContent"><?php print $typeName; ?>: <?php print $attachmentDetails->title; ?></h3>
	<div class="byEditor"><?php print $attachmentDetails->content; ?></div>
<?php
				}
			}
		}
?>
		<!-- End Meeting Attachment -->

<?php
	}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>