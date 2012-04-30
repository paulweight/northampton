<?php
    include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("websections/JaduDownloads.php");
	include_once("egov/JaduCL.php");

	include("../includes/lib.php");

	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
		$allDownloads = getAllDownloadsWithCategory($_GET['categoryID'], array(
		    'approved' => true, 
		    'live' => true,
		    'visible' => true
		));
		
		//	Category Links
		$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		$allCategories = $lgclList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, DOWNLOADS_APPLIED_CATEGORIES_TABLE, true);
		$splitArray = splitArray($categories);
		
		//	Category Links
		$currentCategory = $lgclList->getCategory($_GET['categoryID']);
		$dirTree = $lgclList->getFullPath($_GET['categoryID']);
	}
	else {
		$dirTree = array();
	}
	
	// Breadcrumb, H1 and Title
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildDownloadsURL() .'">Document downloads</a></li>';
	$levelNo = 1;
	$count = 0;
	foreach ($dirTree as $parent) {
		if ($count < sizeof($dirTree) - 1) {
			$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildDownloadsURL($parent->id) .'" >'. encodeHtml($parent->name) .'</a></li>';
		}
		else {
			$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($parent->name) .'</span></li>';
			$MAST_HEADING = 'Document Downloads - '.$parent->name;
		}
		$count++;
		$levelNo++;
	}

	include("downloads.html.php");
?>