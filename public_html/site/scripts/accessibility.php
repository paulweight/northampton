<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");
	include_once("websections/JaduAbout.php");
	
	$about = new About();
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Accessibility statement';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Accessibility statement</span></li>';
	
	include("accessibility.html.php");
?>