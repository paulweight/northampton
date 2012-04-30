<?php
	include_once('utilities/JaduStatus.php');
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
		
		if ($meeting === -1) {
			$meeting = getMeetingMinutes($attachmentDetails->meetingID, true);
		}
	}
		
	if ($meeting !== -1) {
		$header = getMeetingMinutesHeader($meeting->headerID);
		
		// Get the attachments for the meeting
		$attachmentList = getMeetingAttachments($meeting->id);
		
		$typedAttachments = array();
		foreach ($attachmentList as $att) {
			$typedAttachments[$att->typeID][] = $att;
		}
		
		$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		
		if (!isset($_GET['categoryID'])) {
			// In case don't follow hierarchy but find document from search etc.
			$categoryID = getFirstCategoryIDForItemOfType (MEETING_MINUTES_CATEGORIES_TABLE, $meeting->id, BESPOKE_CATEGORY_LIST_NAME);
		}
		else {
			// Get categories
			$currentCategory = $lgclList->getCategory($_GET['categoryID']);
			$dirTree = $lgclList->getFullPath($_GET['categoryID']);
		}
	}
	else {
		header("Location: meetings_index.php");
		exit;
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Agendas, Reports and Minutes';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildMeetingsURL() .'" >Agendas, Reports and Minutes</a></li><li><a href="' . getSiteRootURL() . buildMeetingsURL(-1, 'committee', $header->id) .'" >'. $header->title .'</a></li><li><span>'. $meeting->getMeetingMinutesDateFormatted(FORMAT_DATE_FULL) .'</span></li>';
	
	include("meetings_info.html.php");
?>