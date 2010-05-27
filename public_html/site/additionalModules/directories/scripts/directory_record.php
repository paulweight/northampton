<?php
	session_start();
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
		header("Location: http://$DOMAIN/site/index.php");
        exit();
	}

	$record = getDirectoryEntry($_REQUEST['recordID']);
	$directory = getDirectory($record->directoryID);
	$directoryFields = array();
	$recordValues = getAllDirectoryEntryValues($record->id);
	$dirTree = array();

	if ($record->live == 0) {
	    header("Location: http://$DOMAIN/site/index.php");
	    exit();
	}

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

	$breadcrumb = "directoryRecord";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - <?php print $record->title; ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

<?php
    if ($includeGoogleMapsJavascript) {
?>
        <script type="text/javascript" src="http://<?php print $DOMAIN; ?>/site/javascript/prototype.js"></script>
        <script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php print GOOGLE_MAPS_API_KEY; ?>"></script>
        <script type="text/javascript" src="http://<?php print $DOMAIN; ?>/site/javascript/directory_record.js"></script>
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
    <a id="brand" href="http://<?php print $DOMAIN; ?>/site/scripts/directory_category.php?directoryCategoryID=<?php print $_REQUEST['directoryCategoryID']; ?>">
	    <img src="http://<?php print $DOMAIN . '/images/' . $categoryInfo->imageFilename; ?>" alt="<?php print getImageProperty($categoryInfo->imageFilename, 'altText'); ?>" />
	</a>
<?php
	}
?>
	<div class="serviceDetails">
	<h2>Service Details</h2>
	<table>
<?php
    foreach ($directoryFields as $field) {
    	if (trim($recordValues[$field->id]->value) != '') {
    	    $directoryFieldType = $directoryFieldTypes[$field->fieldTypeID];
    		$recordValues[$field->id]->value = preg_replace('(https?://[^ ]*)', '<a href="$0">$0</a>', $recordValues[$field->id]->value);
    		$recordValues[$field->id]->value = preg_replace('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', '<a href="mailto:$0">$0</a>', $recordValues[$field->id]->value);

    		$fieldSettings = getDirectorySettingsForField ($field->id);
?>
	    <tr>
<?php
            print '<th>';
        // if ($field->displayTitle == '1') {
                print htmlentities($field->title);
        //  }
            print'</th>';
            print '<td class="record">';
            switch ($directoryFieldType->name) {
                case 'Image':
                    printf('<img src="http://%s/images/%s" />', DOMAIN, $recordValues[$field->id]->value);
                    break;
                case 'Google Map':
					// marker image
					$markerImage = sprintf('http://%s/site/images/map_markers/red.png', DOMAIN);
					if (!empty($fieldSettings['MapMarker']->value)) {
						$markerImage = sprintf('http://%s/site/images/map_markers/%s', DOMAIN, $fieldSettings['MapMarker']->value);
					}

					// marker location
					$markerLocation = trim($recordValues[$field->id]->value);
					if (!empty($markerLocation) && !preg_match("/[0-9]+\.[0-9]+,-?[0-9]+\.[0-9]+/", $markerLocation)) {
						// must be a post code so convert and store lat long
						include_once('utilities/JaduGoogleMaps.php');
						$markerLocation = getLatLongFromPostcode($markerLocation);
						$recordValues[$field->id]->value = $markerLocation;
						if (!empty($markerLocation)) {
						    updateDirectoryEntryValue($recordValues[$field->id]);
					    }
					}

					// marker info
					$markerInfo = '';
					$markerInfoFieldIDs = unserialize($fieldSettings['MarkerInfo']->value);
					if (is_array($markerInfoFieldIDs)) {
						foreach ($markerInfoFieldIDs as $fieldID) {
							$markerInfo .= sprintf('%s<br />', $recordValues[$fieldID]->value);
						}
					}
					$markerInfo = nl2br($markerInfo);

                    printf('<input type="hidden" id="map_marker_image_%s" value="%s" />', $field->id, $markerImage);
                    printf('<input type="hidden" id="map_marker_info_%s" value="%s" />', $field->id, urlencode($markerInfo));
					printf('<input type="hidden" id="map_marker_location_%s" value="%s" />', $field->id, $markerLocation);
                    printf('<div id="map_%s" class="googleMap" style="height:%spx; width:%spx;"></div>', $field->id, 300, 500);
                    break;
                default:
                    $recordValues[$field->id]->value = preg_replace('/(https?:\/\/[^ ]*)$/', '<a href="$0">$0</a>', $recordValues[$field->id]->value . ' ');
            		$recordValues[$field->id]->value = preg_replace('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', '<a href="mailto:$0">$0</a>', $recordValues[$field->id]->value);
                    print nl2br($recordValues[$field->id]->value);
            }
            print '</td>';
?>
	    </tr>
<?php
		}
    }
?>
	</table>
	
<?php
    if ($directory->showSocialBookmarks == '1') {
        $bookmarkTitle = $record->title;
        include_once('../includes/social_bookmarks.php');
	}
?>
	</div>
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php");?>