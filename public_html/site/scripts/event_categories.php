<?php
	header('Location: /homepage/407/what_s_on_in_northampton');
        exit;

	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");

	include("../includes/lib.php");
	
	$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
	$allRootCategories = $lgclList->getTopLevelCategories();
	$rootCategories = filterCategoriesInUse($allRootCategories, EVENTS_APPLIED_CATEGORIES_TABLE, true);
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Events';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Events</span></li>';
	
	include("event_categories.html.php");
?>
