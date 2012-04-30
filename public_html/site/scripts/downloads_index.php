<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");
	include_once("websections/JaduDownloads.php");

	include("../includes/lib.php");

	$topDownloads = getTopXDownloadFiles(10); // no need for live as has no flag
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Document downloads';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Document downloads</span></li>';
	
	include("downloads_index.html.php");
?>