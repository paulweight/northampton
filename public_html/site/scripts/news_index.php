<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduNews.php");
	include_once("JaduCategories.php");
	include_once("JaduAppliedCategories.php");
	include_once("websections/JaduFeedManager.php");

	$topNews = getTopNews(true, true);
	if ($topNews === null) {
		$topNews = getLastNews(true, true);
	}
	
	$allNews = getAllNewsByDateLimited(30, true, true);
	$categoryList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Latest news';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Latest news</span></li>';
	
	include("news_index.html.php");
?>