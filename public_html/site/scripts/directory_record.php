<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduImages.php");
	include_once("directoryBuilder/JaduDirectories.php");
	include_once("directoryBuilder/JaduDirectoryFields.php");
	include_once("directoryBuilder/JaduDirectoryCategoryInformation.php");
	include_once("directoryBuilder/JaduDirectoryEntries.php");
	include_once("directoryBuilder/JaduDirectoryCategoryFields.php");
	include_once("directoryBuilder/JaduDirectoryEntryValues.php");
	include_once("directoryBuilder/JaduDirectoryCategoryTree.php");
	include_once("directoryBuilder/JaduDirectoryFieldTypes.php");
	include_once("directoryBuilder/JaduDirectorySettings.php");
	include_once("../includes/lib.php");

	if (!isset($_REQUEST['recordID']) || !is_numeric($_REQUEST['recordID'])) {
		header('Location: http://' . DOMAIN);
		exit();
	}

	$record = getDirectoryEntry($_REQUEST['recordID']);
	// 404 error on invalid recordID values or non live directory entries
	if ($record->id == -1 || $record->live == 0) {
		include_once('../../404.php');
		exit();
	}
	$directory = getDirectory($record->directoryID, true);
	// 404 error on invalid directoryID values or non live directories
	if ($directory->id == -1 || $directory->live == 0) {
		include_once('../../404.php');
		exit();
	}
	$directoryFields = array();
	$recordValues = getAllDirectoryEntryValues($record->id);
	$dirTree = array();

	if (isset($_REQUEST['categoryID'])) {
		$category = getDirectoryCategory($_REQUEST['categoryID']);
		$dirTree = array_reverse(getDirectoryCategoryAncestors($category->id));
	}

	if (isset($_REQUEST['categoryInfoID']) && is_numeric($_REQUEST['categoryInfoID'])) {
		$categoryInfo = getDirectoryCategoryInformation($_REQUEST['categoryInfoID']);
		
		if ($categoryInfo->id != -1 && is_numeric($categoryInfo->id)) {
			$categoryFields = getAllDirectoryCategoryFields($categoryInfo->categoryID);
			foreach ($categoryFields as $categoryField) {
				$directoryFields[] = getDirectoryField ($categoryField->fieldID);
			}
		}
	}

	if (sizeof($directoryFields) < 1) {
		$directoryFields = getAllDirectoryFields($directory->id, $visibleOnly = true);
	}

	$directoryFieldTypes = array();
	$includeGoogleMapsJavascript = false;
	foreach ($directoryFields as $directoryField) {
		$directoryFieldType = getDirectoryFieldType($directoryField->fieldTypeID);
		$directoryFieldTypes[$directoryField->fieldTypeID] = $directoryFieldType;
		if ($directoryFieldType->name == 'Google Map') {
			$includeGoogleMapsJavascript = true;
		}
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = $directory->name . ' - '. $record->title;
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . buildDirectoriesURL(-1, $directory->id) . '">'.encodeHtml($directory->name).'</a></li>';
	foreach ($dirTree as $cat) {
		$MAST_BREADCRUMB .= '<li><a href="' . buildDirectoryCategoryURL($directory->id, $category->id, $categoryInfo->id) . '">'. encodeHtml($cat->title) .'</a></li>';
	}
	if (isset($_REQUEST['categoryID'])) {
		$MAST_BREADCRUMB .= '<li><a href="' . buildDirectoryCategoryURL($directory->id, $category->id, $categoryInfo->id) . '">'. encodeHtml($category->title) .'</a></li>';
	}
	$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($record->title) .'</span></li>';
	
	include("directory_record.html.php");
?>