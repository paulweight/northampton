<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("egov/JaduEGovMeetingMinutes.php");
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");

	$allMeetings = array();

	if (isset($_GET['headerID']) && is_numeric($_GET['headerID'])) {
		$header = getMeetingMinutesHeader($_GET['headerID']);
		if ($header !== -1) {
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

	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Agendas, Reports and Minutes';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a  href="' . getSiteRootURL() . buildMeetingsURL() .'" >Agendas, Reports and Minutes</a></li><li><span>'. encodeHtml($header->title) .'</span></li>';
	
	include("meetings_committees.html.php");
?>