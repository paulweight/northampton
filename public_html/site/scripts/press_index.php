<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduPressReleases.php");
	include_once("JaduCategories.php");
	include_once("JaduAppliedCategories.php");

	$topPressRelease = getTopPressReleases(true, true);
	if ($topPressRelease === null) {
		$topPressRelease = getLastPressReleases(true, true);
	}
	
	$allPressReleases = getAllPressReleasesByDateLimited(30, true, true);
	$categoryList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Press releases';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Press releases</span></li>';
	
	include("press_index.html.php");
?>