<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("egov/JaduEGovMeetingMinutes.php");
	include_once("egov/JaduCL.php");
	include_once("../includes/lib.php");
	
	$activeHeaders = getAllMeetingMinutesHeaders(false);
	
	$archivedHeaders = getAllMeetingMinutesHeaders(true);
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Agendas, Reports and Minutes';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildMeetingsURL(). '" >Agendas, reports and minutes</a></li><li><span>Archive</span></li>';
	
	include("meetings_archive.html.php");
?>