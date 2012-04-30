<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("egov/JaduCL.php");
	include_once("websections/JaduEvents.php");

	include_once("../includes/lib.php"); 

	$dirTree = array();

	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {

		$allEvents = getAllEventsWithCategory($_GET['categoryID'], true);
		
		//	Category Links
		$bespokeCategoryList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		$allCategories = $bespokeCategoryList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, EVENTS_APPLIED_CATEGORIES_TABLE, true);

		//	Category Links
		$currentCategory = $bespokeCategoryList->getCategory($_GET['categoryID']);
		$dirTree = $bespokeCategoryList->getFullPath($_GET['categoryID']);
		$leftCategoryID = $_GET['categoryID'];
	}
	else {
		header("Location: " . buildEventsURL());
		exit;
	}
	
	// Breadcrumb, H1 and Title
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildEventsURL() .'">Events</a></li>';
	$levelNo = 1;
	$count = 0;
	foreach ($dirTree as $parent) {
		if ($count < sizeof($dirTree) - 1) {
			$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildEventsURL($parent->id) .'" >'. encodeHtml($parent->name) .'</a></li>';
		}
		else {
			$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($parent->name) .'</span></li>';
			$MAST_HEADING = 'Events - '.$parent->name;
		}
		$count++;
		$levelNo++;
	}
	
	include("events.html.php");
?>