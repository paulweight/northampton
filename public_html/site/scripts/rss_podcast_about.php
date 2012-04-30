<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");

	$MAST_HEADING = 'About podcasts';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildMultimediaPodcastsURL() . '">Podcasts</a></li><li class="bc_end"><span>About podcasts</span></li>';
	
	include("rss_podcast_about.html.php");
?>