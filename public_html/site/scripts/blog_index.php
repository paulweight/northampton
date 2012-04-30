<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("egov/JaduCL.php");
	include_once("websections/JaduBlogs.php");
	include_once("../includes/lib.php");

	$dirTree = array();

	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {

		$allBlogs = getAllBlogsInCategory ($adminID = -1, $_GET['categoryID'], $onlyLive = true, $categoryType = BESPOKE_CATEGORY_LIST_NAME);
		$splitBlogs = splitArray($allBlogs);

		$bespokeCategoryList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		$allCategories = $bespokeCategoryList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, BLOG_APPLIED_CATEGORIES_TABLE, true);

		$currentCategory = $bespokeCategoryList->getCategory($_GET['categoryID']);
		$dirTree = $bespokeCategoryList->getFullPath($_GET['categoryID']);
		$leftCategoryID = $_GET['categoryID'];
		
		if (sizeof($allBlogs) == 0 && sizeof($categories) == 0) {
			header('HTTP/1.0 404 Not Found');
		}
	}
	else {
	    $bespokeCategoryList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
    	$allRootCategories = $bespokeCategoryList->getTopLevelCategories();
    	$categories = filterCategoriesInUse($allRootCategories, BLOG_APPLIED_CATEGORIES_TABLE, true);
	}

	$splitCategories = splitArray($categories);

	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Blogs';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li>';
	if (isset($_REQUEST['categoryID'])) {
		$MAST_BREADCRUMB .= '<li><a href="' . buildBlogURL() . '">Blogs</a></li><li><span>'. encodeHtml($currentCategory->name) .'</span></li>';
	}
	else {
		$MAST_BREADCRUMB .= '<li><span>Blogs</span></li>';
	}

		include("blog_index.html.php");
?>