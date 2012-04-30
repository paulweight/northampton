<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");  

	include_once("JaduSearch.php");
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Advanced search';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li class="bc_end"><span>Advanced search</span></li>';
	
	include("search_index.html.php");
?>