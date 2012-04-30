<?php 
	if (isset($_GET['selectLinks'])) {
		header("Location: links.php#link".$_GET['selectLinks']);
		exit();
	}

	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduLinks.php");
	
	$categoriesList = getAllLinkCategories();
	
	$linksList = getAllLinks();	
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'External links and resources';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>External links and resources</span></li>';
	
	include("links.html.php");
?>