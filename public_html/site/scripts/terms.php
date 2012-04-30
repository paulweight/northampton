<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");  
	include_once("websections/JaduTerms.php");
	
	$terms = new Terms();
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Terms and disclaimer';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Terms and disclaimer</span></li>';
	
	include("terms.html.php");
?>