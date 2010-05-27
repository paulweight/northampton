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
		header("Location: http://$DOMAIN/site/index.php");
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

	$directory = getDirectory($_REQUEST['directoryID']);
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

	if (isset($_REQUEST['search'])) {
	    if (empty($_REQUEST['postcode']) && isset($_REQUEST['keywords']) && strlen($_REQUEST['keywords']) > 2) {
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
            if (isset($_REQUEST['keywords']) && strlen($_REQUEST['keywords']) > 2) {
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
            }

            $latLong = getLatLongFromPostcode($postcode);
    		list($startLat, $startLong) = split("[,]", $latLong);
    		list($outcode, $incode) = split(" ", $postcode);
            $outcode .= ' ';

    		$records = array();

            // loop through each entry value and see if it is near the post code provided
    		$entryIDsFound = array();
    		foreach ($entryValues as $entryValue) {
    			if (!empty($entryValue->value) && 
    				preg_match("/[0-9]+\.[0-9]+,-?[0-9]+\.[0-9]+/", $entryValue->value) && 
    				!in_array($entryValue->entryID, $entryIDsFound)) {

    				list($latitude, $longitude) = split("[,]", $entryValue->value);
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
    				    
    				if (substr($entryValue->value, 0, strlen($outcode)) == $outcode) {
    				    
    				    $tmpEntry = getDirectoryEntry($entryValue->entryID);
						$records[] = $tmpEntry;
						$entryIDsFound[] = $tmpEntry->id;
    				}
    			}
    		}
	    }
	}

	if ($offset > 0) {
		$showPrevious = true;
	}

	$breadcrumb = "directorySearch";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Search</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print $directory->title;?> directory, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> <?php print $directory->name;?> directory" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> <?php print $directory->name;?> directory" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> <?php print $directory->name;?> directory" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

<?php
    if ($showMap) {
?>
        <script type="text/javascript" src="http://<?php print $DOMAIN; ?>/site/javascript/prototype.js"></script>
        <script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php print GOOGLE_MAPS_API_KEY; ?>"></script>
        <script type="text/javascript" src="http://<?php print $DOMAIN; ?>/site/javascript/directory_search.js"></script>
<?php
    }
?>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (isset($categoryInfo) && $categoryInfo->id != '-1') {
?>
    <a id="brand" href="http://<?php print $DOMAIN; ?>/site/scripts/directory_category.php?directoryCategoryID=<?php print $_REQUEST['categoryID']; ?>">
	    <img src="http://<?php print $DOMAIN . '/images/' . $categoryInfo->imageFilename; ?>" alt="<?php print getImageProperty($categoryInfo->imageFilename, 'altText'); ?> " />
	</a>
<?php
	}

	if ((isset($_REQUEST['keywords']) || isset($_REQUEST['postcode'])) 
	                                                && sizeof($records) < 1) {
?>
        <p class="first">Sorry, no matches were found.</p>
<?php
	}
	if (isset($_REQUEST['keywords']) && strlen($_REQUEST['keywords']) < 3 
		&& empty($_REQUEST['postcode'])) {
?>
        <p class="first">Please enter three or more characters.</p>
<?php
	}

	if (!isset($records) || sizeof($records) < 1) {
?>

		<h2 class="legend">Search the directory</h2>
		<form action="http://<?php print $DOMAIN; ?>/site/scripts/directory_search.php" method="get" class="basic_form">
    		<fieldset>
        	    <input type="hidden" name="directoryID" value="<?php print $directory->id; ?>" />
        		<p>
        			<label for="keywords">Keywords</label>
        			<input type="text" class="field" name="keywords" value="" id="keywords" />
        		</p>
<?php
            if (sizeof($topLevelCategories) > 0) {
?>
        		<p>
        			<label for="category">Category</label>
        			<select class="field"  id="category" name="categoryID">
        			    <option value="-1">All Categories</option>
<?php
                    foreach ($topLevelCategories as $category) {
?>
                        <option value="<?php print $category->id; ?>" <?php if ($category->id == $categoryID) print 'selected="selected"'; ?>>
        					<?php print $category->title; ?>
        				</option>
<?php
                    }
?>
        			</select>
        		</p>
<?php
            }
?>
                <p>
        			<label for="postcode">Near post code</label>
        			<input type="text" class="field" name="postcode" value="" id="postcode" />
        		</p>
        		<p class="center">
        			<input type="submit" value="Search" name="search" class="button" />
        		</p>
    		</fieldset>
		</form>
<?php
	}

	elseif (sizeof($records) > 0) {
?>
	<div class="display_box">
		<h2>Search Results</h2>

	<p class="first">The following matches were found:</p>

<?php
	if ($showMap) {
		printf('<div id="googleMap" class="googleMap" style="height:%spx; width:%spx; float:right;"></div>', 300, 500);
	}
?>

	<ul class="list">
<?php
	$locationsUsed = array();
    foreach ($records as $record) {
		if ($showMap) {
			$recordValues = getAllDirectoryEntryValues($record->id);
			foreach ($mapFieldIDs as $fieldID) {
				$fieldSettings = getDirectorySettingsForField ($fieldID);

				$markerImage = sprintf('http://%s/site/images/map_markers/red.png', DOMAIN);
				if (!empty($fieldSettings['MapMarker']->value)) {
					$markerImage = sprintf('http://%s/site/images/map_markers/%s', DOMAIN, $fieldSettings['MapMarker']->value);
				}

				// marker location
				$markerLocation = trim($recordValues[$fieldID]->value);
				if (!empty($markerLocation) && !preg_match("/[0-9]+\.[0-9]+,-?[0-9]+\.[0-9]+/", $markerLocation)) {
					// must be a post code so convert and store lat long
					$markerLocation = getLatLongFromPostcode($markerLocation);
					$recordValues[$fieldID]->value = $markerLocation;
					updateDirectoryEntryValue($recordValues[$fieldID]);
				}

				// marker info
				$markerInfo = sprintf('<a href="http://%s/site/scripts/directory_record.php?directoryID=%s&categoryInfoID=%s&recordID=%s">%s</a>', DOMAIN, $directory->id, $categoryInfoID, $record->id, $record->title);

				$markerID = sprintf('%s_%s', $record->id, $fieldID);
	            printf('<input type="hidden" id="map_marker_image_%s" value="%s" />', $markerID, $markerImage);
	            printf('<input type="hidden" id="map_marker_info_%s" value="%s" />', $markerID, urlencode($markerInfo));
				if (!in_array($markerLocation, $locationsUsed)) {
					$locationsUsed[] = $markerLocation;
					printf('<input type="hidden" id="%s" class="mapMarkers" value="%s" />', $markerID, $markerLocation);
				}
			}
		}
?>
		<li>
		    <a href="http://<?php print $DOMAIN; ?>/site/scripts/directory_record.php?directoryID=<?php print $directory->id;?>&amp;categoryInfoID=<?php print $categoryInfoID; ?>&amp;recordID=<?php print $record->id ?>">
		        <?php print htmlentities($record->title); ?>
		    </a>
		</li>	
<?php
    }
?>
	</ul>
<?php
	if ($showPrevious) {
?>
		<a href="http://<?php print DOMAIN; ?>/site/scripts/directory_search.php?<?php print $queryString; ?>&amp;offset=<?php print $offset - $numRows; ?>">Previous</a>
<?php
	}
	
	if ($showNext && $showPrevious) {
		print "  | ";
	}
	
	if ($showNext) {
?>
		<a href="http://<?php print DOMAIN; ?>/site/scripts/directory_search.php?<?php print $queryString; ?>&amp;offset=<?php print $offset + $numRows; ?>">Next</a>
<?php
	}
	
?>
	</div>
	<p class="first">Would you like to <a href="http://<?php print $DOMAIN; ?>/site/scripts/directory_search.php?directoryID=<?php print $directory->id; if (isset($categoryInfo) && $categoryInfo->id != -1) print '&amp;categoryInfoID=' . $categoryInfo->id; ?>">search again</a>? </p>
<?php
	}
?>

    <!-- The Contact box -->
    <?php include("../includes/contactbox.php"); ?>
    <!-- END of the Contact box -->
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php");?>