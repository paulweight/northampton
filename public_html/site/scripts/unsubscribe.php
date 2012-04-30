<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Unsubscribe';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Unsubscribe</span></li>';
	
	include("unsubscribe.html.php");
?>