<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduImages.php");
	include_once("directoryBuilder/JaduDirectories.php");
	include_once("directoryBuilder/JaduDirectoryCategoryInformation.php");
	
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
    $categoryInfo = new DirectoryCategoryInformation();
    $category = new DirectoryCategory();

    if (isset($_REQUEST['startsWith'])) {
		$entryStartsWith = mb_strtoupper($_REQUEST['startsWith']);
	}
	else {
		$entryStartsWith = 'A';
	}

	if (isset($_REQUEST['categoryInformationID']) && is_numeric($_REQUEST['categoryInformationID'])) {
		$categoryInfo = getDirectoryCategoryInformation($_REQUEST['categoryInformationID']);
        $category = getDirectoryCategory($categoryInfo->categoryID);
		$directory = getDirectory($category->directoryID);

		$records = getAllDirectoryEntriesUnderCategory ($directory->id, $categoryInfo->id, $entryStartsWith, true);
	}
	else {
	    $records = getAllDirectoryEntriesUnderCategory ($directory->id, -1, $entryStartsWith, true);
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = $directory->name;
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>'. encodeHtml($directory->name) .'</span></li>';
	
	include("directory_az.html.php");
?>