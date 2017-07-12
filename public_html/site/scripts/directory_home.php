<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("directoryBuilder/JaduDirectories.php");
	include_once("directoryBuilder/JaduDirectoryCategoryInformation.php");
	include_once("directoryBuilder/JaduDirectoryCategoryTree.php");
	include_once("../includes/lib.php");

	if (!isset($_REQUEST['directoryID']) || !is_numeric($_REQUEST['directoryID'])) {
		header('Location: ' . getSiteRootURL());
        exit();
	}

	$directory = getDirectory($_REQUEST['directoryID'], true);

	// 404 error on invalid directoryID values or non live directories
	if ($directory->id == -1) {
		include_once('../../404.php');
		exit();
	}

    $categories = getDirectoryCategories($parentID = -1, $directory->id);
    $splitCategories = splitArray($categories);

	if (sizeof($categories) == 0) {

		$records = getAllDirectoryEntries ($directory->id, $live = 1, $categoryID = -1, 
     	                                $titleMatch = '', $userSubmitteOnly = false, $orderBy = 'title', 
     	                                $orderDir = 'ASC', $offset = 0, $numRows = 20);
     	                                
     	$splitRecords = splitarray($records);
	}
	else {
		$dirTree = array_reverse(getDirectoryCategoryAncestors($categories[0]->id));
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = $directory->name;
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li>';
	foreach ($dirTree as $parent) {
		$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildDocumentsCategoryURL($parent->id) . '" >'. encodeHtml($parent->name) .'</a></li>';
	}
	$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($header->title) .'</span></li>';
	
	include("directory_home.html.php");
?>