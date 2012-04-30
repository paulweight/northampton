<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduConstants.php");
	include_once("websections/JaduNews.php");
	include_once("websections/JaduPressReleases.php");
	include_once("websections/JaduEvents.php");
	include_once("websections/JaduDownloads.php");
	include_once("websections/JaduDocuments.php");
	include_once("egov/JaduXFormsForm.php");

	include("../includes/lib.php");

	define('MAX_WHATS_NEW', 10);

	$news = getAllNewsByDate(true, true);
	$events = getNumEvents(MAX_WHATS_NEW);
	$downloads = getXMostRecentlyCreatedDownloadFiles(MAX_WHATS_NEW);
	$documents = getXMostRecentlyCreatedDocuments(MAX_WHATS_NEW, true, true);
	$forms = getXMostRecentlyCreatedXFormsForms(MAX_WHATS_NEW);
	$press = getAllPressReleasesByDate(true, true);

	$MAST_HEADING = "What's New";
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>What&#39;s new</span></li>';
	
	include("whats_new_index.html.php");	
?>