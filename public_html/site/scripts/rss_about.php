<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	$MAST_HEADING = 'RSS Feed';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildNewsURL() .'">News</a></li><li class="bc_end"><span>RSS news feed</span></li>';
	
	include("rss_about.html.php");
?>