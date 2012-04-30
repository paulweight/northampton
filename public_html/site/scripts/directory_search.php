<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduImages.php");
	include_once("directoryBuilder/JaduDirectories.php");
	include_once("directoryBuilder/JaduDirectoryCategoryInformation.php");
	include_once("directoryBuilder/JaduDirectorySettings.php");
	include_once("directoryBuilder/JaduDirectoryFields.php");
	include_once("directoryBuilder/JaduDirectoryFieldTypes.php");
	include_once('utilities/JaduGoogleMaps.php');

	if (!isset($_REQUEST['directoryID']) || !is_numeric($_REQUEST['directoryID'])) {
		header('Location: http://' . DOMAIN);
        exit();
	}

	$categoryID = (isset($_REQUEST['categoryID']) && 
	               is_numeric($_REQUEST['categoryID'])) 
	               ? $_REQUEST['categoryID'] : -1;

	$categoryInfoID = (isset($_REQUEST['categoryInfoID']) && 
	               		is_numeric($_REQUEST['categoryInfoID'])) 
	               		? $_REQUEST['categoryInfoID'] : -1;

	$offset = (isset($_REQUEST['offset']) && is_numeric($_REQUEST['offset']))
			   ? $_REQUEST['offset'] : 0;

	$numRows = 10;
	$showPrevious = false;
	$showNext = false;
	$records = array();
	$queryString = 'directoryID=' . $_REQUEST['directoryID'] .
				   '&categoryID=' . $_REQUEST['categoryID'] . 
				   '&keywords=' . $_REQUEST['keywords'] .
				   '&categoryInfoID=' . $_REQUEST['categoryInfoID'] . 
				   '&search=true';

	$directory = getDirectory($_REQUEST['directoryID'], true);
	if ($directory->id < 1) {
		header('Location: ' . buildDirectoryCategoryURL());
		exit;
	}
	
	$categoryInfo = new DirectoryCategoryInformation();

	if (isset($_REQUEST['categoryInfoID']) && is_numeric($_REQUEST['categoryInfoID']) 
		&& $_REQUEST['categoryInfoID'] != '-1') {
		$categoryInfo = getDirectoryCategoryInformation($_REQUEST['categoryInfoID']);
	}
	elseif (isset($_REQUEST['categoryID']) && is_numeric($_REQUEST['categoryID']) 
		&& $_REQUEST['categoryID'] != '-1') {
		$tmpCat = getDirectoryCategory($_REQUEST['categoryID']);
		if ($tmpCat->parentID == -1) {
			$categoryInfo = getDirectoryCategoryInformationForCategory($tmpCat->id);
		}
	}

	$topLevelCategories = getDirectoryCategories(-1, $directory->id);

	$directorySettings = getAllDirectorySettings($directory->id, $group = 'General');

	$showMap = false;
	$mapFieldIDs = array();
	foreach ($directorySettings as $setting) {
		if ($setting->name == 'Show search results on map' && $setting->value == 1) {
			$showMap = true;
		}

		$directoryFields = getAllDirectoryFields($directory->id);
		foreach ($directoryFields as $field) {
			$directoryFieldType = getDirectoryFieldType($field->fieldTypeID);
	        if ($directoryFieldType->name == 'Google Map') {
	            $mapFieldIDs[] = $field->id;
	        }
		}
	}

	if (empty($_REQUEST['postcode']) && isset($_REQUEST['keywords']) && mb_strlen($_REQUEST['keywords']) > 2) {
		$records = searchDirectoryEntryValues($directory->id, $categoryID, $_REQUEST['keywords'], true, $offset, $numRows);

		if (sizeof(searchDirectoryEntryValues($directory->id, $categoryID, $_REQUEST['keywords'], true, $offset + $numRows, $numRows))) {
			$showNext = true;
		}
	}
	elseif (isset($_REQUEST['postcode']) && preg_match("/^[\s0-9a-zA-Z]+$/i", $_REQUEST['postcode'])) {

		$postcode = $_REQUEST['postcode'];
		$withinDistance = (isset($_REQUEST['distance']) && is_numeric($_REQUEST['distance'])) ? $_REQUEST['distance'] : 10;
		$entryValues = array();

		// get the map entry values
		if (isset($_REQUEST['keywords']) && mb_strlen($_REQUEST['keywords']) > 2) {
			// if a search has been done then get the values from the record
			$records = searchDirectoryEntryValues($directory->id, $categoryID, $_REQUEST['keywords'], true);
			foreach ($records as $record) {
				$recordValues = getAllDirectoryEntryValues($record->id);
				foreach ($mapFieldIDs as $fieldID) {
					$entryValues[] = $recordValues[$fieldID];
				}
			}
		}
		else {
			// otherwise get all map fields
			foreach ($mapFieldIDs as $mapFieldID) {
				$entryValues = array_merge($entryValues, getAllDirectoryEntryValuesForField ($mapFieldID));
			}
			
			// Filter by category
			if ($categoryID > 0) {
				foreach ($entryValues as $key => &$entryValue) {
					if (!isDirectoryEntryUnderCategory($entryValue->entryID, $categoryID)) {
						unset($entryValues[$key]);
					}
				}
			}
		}
		$metadata = getMetadataForItem (DIRECTORY_METADATA_TABLE, $_GET['directoryID']);
		$latLong = getLatLongFromPostcode($postcode, $metadata->coverage);
		list($startLat, $startLong) = mb_split("[,]", $latLong);
		list($outcode, $incode) = mb_split(" ", $postcode);
		$outcode .= ' ';

		$records = array();

		// loop through each entry value and see if it is near the post code provided
		$entryIDsFound = array();
		foreach ($entryValues as $entryValue) {
			if (!empty($entryValue->value) && 
				preg_match("/[0-9]+\.[0-9]+,-?[0-9]+\.[0-9]+/", $entryValue->value) && 
				!in_array($entryValue->entryID, $entryIDsFound)) {

				list($latitude, $longitude) = mb_split("[,]", $entryValue->value);
				$distance = getDistanceBetweenPoints($startLat, $startLong, $latitude, $longitude, 'M');
				if ($distance < $withinDistance) {
					if (!in_array($records[$entryValue->entryID], $entryIDsFound)) {
						$tmpEntry = getDirectoryEntry($entryValue->entryID);
						$records[] = $tmpEntry;
						$entryIDsFound[] = $tmpEntry->id;
					}
				}
			}
			elseif (!empty($entryValue->value) &&
				!in_array($entryValue->entryID, $entryIDsFound) &&
				!empty($outcode)) {
					
				if (mb_substr($entryValue->value, 0, mb_strlen(mb_strtolower($outcode))) == mb_strtolower($outcode) || trim(mb_strtolower($outcode)) == trim(mb_strtolower($entryValue->value))) {
					
					$tmpEntry = getDirectoryEntry($entryValue->entryID);
					$records[] = $tmpEntry;
					$entryIDsFound[] = $tmpEntry->id;
				}
			}
		}
	}
	
	if (!isset($records) || sizeof($records) < 1) {
		$showMap = false;
	}

	if ($offset > 0) {
		$showPrevious = true;
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = $directory->name .' search';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . buildDirectoriesURL() . '">Online directories</a></li>';
	$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() .buildDirectoriesURL(-1, $directory->id).'">'. encodeHtml($directory->name) .'</a></li><li><span>Search</span></li>';
	
	include("directory_search.html.php");
?>