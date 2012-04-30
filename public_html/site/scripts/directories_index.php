<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("egov/JaduCL.php");
	include_once("directoryBuilder/JaduDirectories.php");
	include_once("../includes/lib.php");

	$dirTree = array();

	if (isset($_GET['categoryID'])) {

		$allDirectories = getAllDirectories($adminID = -1, $live = 1, $_GET['categoryID']);

		$bespokeCategoryList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		$allCategories = $bespokeCategoryList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, DIRECTORY_APPLIED_CATEGORIES_TABLE, true);

		$currentCategory = $bespokeCategoryList->getCategory($_GET['categoryID']);
		$dirTree = $bespokeCategoryList->getFullPath($_GET['categoryID']);
		$leftCategoryID = $_GET['categoryID'];
	}
	else {
	    $bespokeCategoryList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
    	$allRootCategories = $bespokeCategoryList->getTopLevelCategories();
    	$categories = filterCategoriesInUse($allRootCategories, DIRECTORY_APPLIED_CATEGORIES_TABLE, true);
	}


	// Breadcrumb, H1 and Title
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li>';
	if(count($dirTree) > 0) {
		$MAST_BREADCRUMB .= '<li><a href="' . buildDirectoriesURL() . '">Online directories</a></li>';
	}
	else {
		$MAST_BREADCRUMB .= '<li><span>Online directories</span></li>';
	}
	$MAST_HEADING = 'Online directories';
	$levelNo = 1;
	$count = 0;
	foreach ($dirTree as $parent) {
		if ($count < sizeof($dirTree) - 1) {
			$MAST_BREADCRUMB .= '<li><a href="' . buildDirectoriesURL($parent->id) . '" >'. encodeHtml($parent->name) .'</a></li>';
		}
		else {
			$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($parent->name) .'</span></li>';
			$MAST_HEADING = $parent->name .' Directories';
		}
		$count++;
		$levelNo++;
	}
	
	include("directories_index.html.php");
?>