<?php
	require_once('utilities/JaduStatus.php');
	require_once('rupa/JaduRupaSearch.php');
	
	$collections = getRupaAdvancedSiteSearchCollections();
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Advanced search';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Advanced search</span></li>';
	
	include("google_advanced.html.php");
?>