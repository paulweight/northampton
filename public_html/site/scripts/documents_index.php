<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");

	include("../includes/lib.php");
	
	$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
	$allRootCategories = $lgclList->getTopLevelCategories();
	$rootCategories = filterCategoriesInUse($allRootCategories, DOCUMENTS_APPLIED_CATEGORIES_TABLE, true);

	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Online information';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Online information</span></li>';
	
	include("documents_index.html.php");
?>