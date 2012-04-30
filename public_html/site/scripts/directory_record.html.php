<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

<?php
	include_once("../includes/stylesheets.php");
	include_once("../includes/metadata.php");
	
	$metadata = getMetadataForItem (DIRECTORY_ENTRY_METADATA_TABLE, $record->id);
	if ($metadata->subject != '') {
		$metadata->subject .= ',';
	}
	$metadata->subject .= METADATA_GENERIC_KEYWORDS;
	if ($metadata->description == '') {
		$metadata->description = METADATA_GENERIC_NAME . '\'s ' . $record->title . ' information';
		foreach ($dirTree as $parent) {
			$metadata->description .= ' | ' . $parent->title;
		}
	}
?>

	<meta name="Keywords" content="<?php print encodeHtml($metadata->subject); ?>" />
	<meta name="Description" content="<?php print encodeHtml($metadata->description); ?>" />
	
<?php 
	$categories = getAllCategoriesOfType(DIRECTORY_ENTRY_CATEGORIES_TABLE, $record->id);
	if (count($categories) > 0) {
		printMetadata(DIRECTORY_ENTRY_METADATA_TABLE, DIRECTORY_ENTRY_CATEGORIES_TABLE, $record->id, $record->title, "http://".DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
	}
	else {
		printMetadata(DIRECTORY_ENTRY_METADATA_TABLE, null, $record->id, $record->title, "http://".DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
		printCategoryMetadata(DIRECTORY_CATEGORIES_TABLE, $directory->id);
	}
?>

<?php
	if ($includeGoogleMapsJavascript) {
?>
		<script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php print encodeHtml(GOOGLE_MAPS_API_KEY); ?>"></script>
		<script type="text/javascript" src="<?php print getStaticContentRootURL(); ?>/site/javascript/directory_record.js"></script>
<?php
	}
?>
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (isset($categoryInfo) && $categoryInfo->id != '-1'  && $categoryInfo->imageFilename != '') {
?>
	<a id="brand" href="<?php print getSiteRootURL() . buildDirectoryCategoryURL($directory->id, (int) $_REQUEST['directoryCategoryID']); ?>">
		<img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($categoryInfo->imageFilename); ?>" alt="<?php print encodeHtml(getImageProperty($categoryInfo->imageFilename, 'altText')); ?>" />
	</a>
<?php
	}
?>

	<h2>Record Details</h2>
	<table>
<?php
	// Build a list of all image fields. Used for map marker balloon
	$imageFields = array();
	$linkFields = array();
	
	foreach ($directoryFields as $fieldItem) {
		// This will continue to work assuming the fieldTypeID for images doesn't change
		if ($fieldItem->fieldTypeID == 5) {
			$imageFields[] = $fieldItem->id;
		}
		
		if ($fieldItem->fieldTypeID == 7) {
			$linkFields[] = $fieldItem->id;
		}
	}
	
	foreach ($directoryFields as $field) {

		if (trim($recordValues[$field->id]->value) != '') {
			$directoryFieldType = $directoryFieldTypes[$field->fieldTypeID];
			$directoryFieldValue = encodeHtml($recordValues[$field->id]->value);
			$directoryFieldValue = preg_replace('(https?://[^ ]*)', '<a href="$0">$0</a>', $directoryFieldValue);
			$directoryFieldValue = preg_replace('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', '<a href="mailto:$0">$0</a>', $directoryFieldValue);

			$fieldSettings = getDirectorySettingsForField($field->id);
			
?>
		<tr>
<?php
			print '<th>';
			if ($field->displayTitle) {
				print encodeHtml($field->title);
			}
			print'</th>';
			print '<td class="record">';
			switch ($directoryFieldType->name) {
				case 'HTML':
					print '	<div class="byEditor article">'. processEditorContent($recordValues[$field->id]->value).'</div>';
					break;
				case 'Image':
					printf('<img src="http://%s/images/%s" />', DOMAIN, $directoryFieldValue);
					break;
				case 'Google Map':
					// marker image
					$markerImage = sprintf('http://%s/site/images/map_markers/red.png', DOMAIN);
					if (!empty($fieldSettings['MapMarker']->value)) {
						$markerImage = sprintf('http://%s/site/images/map_markers/%s', DOMAIN, encodeHtml($fieldSettings['MapMarker']->value));
					}

					// marker location
					$markerLocation = trim($recordValues[$field->id]->value);
					if (!empty($markerLocation) && !preg_match("/[0-9]+\.[0-9]+,-?[0-9]+\.[0-9]+/", $markerLocation)) {
						// must be a post code so convert and store lat long
						include_once('utilities/JaduGoogleMaps.php');
						$markerLocation = getLatLongFromPostcode($markerLocation);
						$recordValues[$field->id]->value = encodeHtml($markerLocation);
						if (!empty($markerLocation)) {
							updateDirectoryEntryValue($recordValues[$field->id]);
						}
					}

					// marker info
					$markerInfo = '';
					$markerInfoFieldIDs = unserialize($fieldSettings['MarkerInfo']->value);
					if (is_array($markerInfoFieldIDs)) {
						foreach ($markerInfoFieldIDs as $fieldID) {
							if (in_array($fieldID, $imageFields)) {
								include_once('JaduImages.php');
								list($width, $height) = getimagesize(HOME_DIR . 'public_html/images/' . basename($recordValues[$fieldID]->value));
								list($scaledWidth, $scaledHeight) = scaleImg($width, $height, 100);
								$markerInfo .= sprintf('<img src="http://%s/images/%s" width="%s" height="%s" /><br />', DOMAIN, $recordValues[$fieldID]->value, $scaledWidth, $scaledHeight);
							}
							else if (in_array($fieldID, $linkFields)) {
								$recordValues[$fieldID]->value = strip_tags($recordValues[$fieldID]->value, '<a>');

								if (mb_strpos($recordValues[$fieldID]->value, '<a') === false) {
									if (mb_substr($recordValues[$fieldID]->value,0,7) !== "http://" && mb_substr($recordValues[$fieldID]->value,0,8) !== "https://") {
										$recordValues[$fieldID]->value = 'http://'.$recordValues[$fieldID]->value;
									}
									$markerInfo .= sprintf('<a href="%s">%s</a>', $recordValues[$fieldID]->value, $recordValues[$fieldID]->value);
								}
								else {
									$markerInfo .= $recordValues[$fieldID]->value;
								}
							}
							else {
								$markerInfo .= sprintf('%s<br />', $recordValues[$fieldID]->value);
							}
						}
					}
					$markerInfo = nl2br($markerInfo);

					printf('<input type="hidden" id="map_marker_image_%d" value="%s" />', $field->id, encodeHtml($markerImage));
					printf('<input type="hidden" id="map_marker_info_%d" value="%s" />', $field->id, encodeHtml(urlencode($markerInfo)));
					printf('<input type="hidden" id="map_marker_location_%d" value="%s" />', $field->id, encodeHtml($markerLocation));
					printf('<div id="map_%d" class="googleMap" style="height:%dpx; width:%dpx;"></div>', $field->id, 300, 330);
					break;
				case 'Link' :
					$recordValues[$field->id]->value = strip_tags($recordValues[$field->id]->value, '<a>');
					
					if (mb_strpos($recordValues[$field->id]->value, '<a') === false) {
						if (mb_substr($recordValues[$field->id]->value,0,7) !== "http://" && mb_substr($recordValues[$field->id]->value,0,8) !== "https://") {
							$recordValues[$field->id]->value = 'http://'.$recordValues[$field->id]->value;
						}
						printf('<a href="%s">%s</a>', $recordValues[$field->id]->value, $recordValues[$field->id]->value);
					}
					else {
						print $recordValues[$field->id]->value;
					}
					break;
				case 'Email' :
					$recordValues[$field->id]->value = strip_tags($recordValues[$field->id]->value, '<a>');
				
					if (mb_strpos($recordValues[$field->id]->value, '<a' ) === false) {
						printf('<a href="mailto:%s">%s</a>', $recordValues[$field->id]->value, $recordValues[$field->id]->value);
					}
					else {
						print $recordValues[$field->id]->value;	
					}				
					break;
				case 'HTML':
					print processEditorContent($recordValues[$field->id]->value);
					break;
				default:
					print nl2br($directoryFieldValue);
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

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php");?>