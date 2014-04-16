<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduImages.php");
	include_once("directoryBuilder/JaduDirectories.php");
	include_once("directoryBuilder/JaduDirectoryEntries.php");
	include_once("directoryBuilder/JaduDirectoryCategoryInformation.php");
	include_once("directoryBuilder/JaduDirectoryCategoryTree.php");
	include_once("../includes/lib.php");

	if (!isset($_REQUEST['directoryCategoryID']) && !isset($_REQUEST['categoryID']) 
		&& !isset($_REQUEST['categoryInfoID'])) {
	    header('Location: http://' . DOMAIN);
        exit();
	}

	$records = array();
	$categoryAdverts = array();
	$subCats = array();
	$useDirectoryCategories = true;
	$categoryInfo = new DirectoryCategoryInformation();
	$liStyle = '';

	if (isset($_REQUEST['directoryCategoryID']) && is_numeric($_REQUEST['directoryCategoryID'])) {
	    $category = getDirectoryCategory($_REQUEST['directoryCategoryID']);
		$directory = getDirectory($category->directoryID, true);
		$categoryAdverts = getDirectoryCategoryAdverts($category->id);

		if (!isset($_REQUEST['categoryInfoID']) || $_REQUEST['categoryInfoID'] == -1) {
			$categoryInfo = getDirectoryCategoryInformationForCategory($category->id);

			if ($categoryInfo->id != '-1' && !empty($categoryInfo->bulletImageFilename)) {
				$liStyle = sprintf(' style="background-image: url(http://%s/images/%s)"', DOMAIN, encodeHtml($categoryInfo->bulletImageFilename));
			}
		}
	}
	elseif (isset($_REQUEST['categoryID']) && is_numeric($_REQUEST['categoryID']) && 
	    $_REQUEST['categoryID'] > 0 && !isset($_REQUEST['directoryCategoryID'])) {
		$useDirectoryCategories = false;
		$categoryList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		$category = $categoryList->getCategory($_REQUEST['categoryID']);
		$directory = getDirectory($_REQUEST['directoryID'], true);
	}
	
	if ($directory->id == -1) {
		header('Location: http://' . DOMAIN);
        exit();
	}

	if (isset($_REQUEST['categoryInfoID']) && is_numeric($_REQUEST['categoryInfoID'])) {
		$categoryInfo = getDirectoryCategoryInformation($_REQUEST['categoryInfoID']);

		if ($categoryInfo->id != '-1' && !empty($categoryInfo->bulletImageFilename)) {
			$liStyle = sprintf(' style="background-image: url(http://%s/images/%s)"', DOMAIN, encodeHtml($categoryInfo->bulletImageFilename));
		}
	}

	if ($useDirectoryCategories) {
		$records = getAllDirectoryEntries($directory->id, $live = 1, $category->id);
		$subCats = getDirectoryCategories($category->id, $directory->id);
		$splitSubCats = splitArray($subCats);		
	}
	else {
		$records = getAllDirectoryEntriesInCategory($category->id, $directory->id, true);
	}

	//if(empty($records)){
	//	header("HTTP/1.0 404 Not Found");
	//	include('../../404.php');
	//	exit;
	//}

	$dirTree = array_reverse(getDirectoryCategoryAncestors($category->id));

   // Breadcrumb, H1 and Title
	$MAST_HEADING = $category->title;
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . buildDirectoriesURL(-1, $directory->id) . '">'.encodeHtml($directory->name).'</a></li>';
	foreach ($dirTree as $cat) {
		$MAST_BREADCRUMB .= '<li><a href="' . buildDirectoryCategoryURL($directory->id, $cat->id, $categoryInfo->id) . '">'. encodeHtml($cat->title) .'</a></li>';
	}

	$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($category->title) .'</span></li>';
    
	include("directory_category.html.php");
?>
