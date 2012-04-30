<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");
	include_once("multimedia/JaduMultimediaPodcasts.php");

	include("../includes/lib.php");
    
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Podcasts';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Podcasts</span></li>';
	
	include("podcasts_index.html.php");
?>