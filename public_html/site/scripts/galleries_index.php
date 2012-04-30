<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");
	include_once("multimedia/JaduMultimediaGalleries.php");

	include("../includes/lib.php");
    
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Galleries';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Galleries</span></li>';
	
	include("galleries_index.html.php");
?>