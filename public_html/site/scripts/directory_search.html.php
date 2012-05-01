<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print encodeHtml($directory->title);?> directory, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> <?php print encodeHtml($directory->name);?> directory" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> <?php print encodeHtml($directory->name);?> directory" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> <?php print encodeHtml($directory->name);?> directory" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

<?php
    if ($showMap) {
?>
	<script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php print encodeHtml(GOOGLE_MAPS_API_KEY); ?>"></script>
	<script type="text/javascript" src="<?php print getStaticContentRootURL(); ?>/site/javascript/directory_search.js"></script>
<?php
    }
?>
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (isset($categoryInfo) && $categoryInfo->id != '-1' && $categoryInfo->imageFilename != '') {
?>
	<a id="brand" href="<?php print getSiteRootURL() . buildDirectoryCategoryURL($directory->id, (int) $_REQUEST['categoryID']); ?>">
		<img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($categoryInfo->imageFilename); ?>" alt="<?php print encodeHtml(getImageProperty($categoryInfo->imageFilename, 'altText')); ?> " />
	</a>
<?php
	}

	if ((isset($_REQUEST['keywords']) || isset($_REQUEST['postcode'])) && sizeof($records) < 1) {
?>
	<p>Sorry, no matches were found.</p>
<?php
	}
	if (isset($_REQUEST['keywords']) && mb_strlen($_REQUEST['keywords']) < 3 && empty($_REQUEST['postcode'])) {
?>
	<p>Please enter three or more characters.</p>
<?php
	}

	if (!isset($records) || sizeof($records) < 1) {
?>

	<h2>Search the directory</h2>
	<form class="basic_form xform" enctype="multipart/form-data" action="<?php print getSiteRootURL(); ?>/site/scripts/directory_search.php" method="get">
		<fieldset>
			<input type="hidden" name="directoryID" value="<?php print (int) $directory->id; ?>" />
			<p>
				<label for="keywords">Keywords</label>
				<input type="text" name="keywords" value="" id="keywords" />
			</p>
<?php
		if (sizeof($topLevelCategories) > 0) {
?>
			<p>
				<label for="category">Category</label>
				<select  id="category" name="categoryID">
					<option value="-1">All Categories</option>
<?php
				foreach ($topLevelCategories as $category) {
?>
					<option value="<?php print $category->id; ?>" <?php if ($category->id == $categoryID) print 'selected="selected"'; ?>>
						<?php print encodeHtml($category->title); ?>
					</option>
<?php
				}
?>
				</select>
			</p>
<?php
		}
		// checks to see if the directory has a 'Google Map' field, if so then show the postcode search
		$dirFields = getAllDirectoryFieldsByFieldTypeId($directory->id, 6, true);
		if (count($dirFields) > 0) {
?>
			<p>
				<label for="postcode">Near post code</label>
				<input type="text" name="postcode" value="" id="postcode" />
			</p>
<?php
		}
?>        		
			<p class="centre">
				<input type="submit" value="Search" name="search" class="genericButton grey" />
			</p>
		</fieldset>
	</form>
<?php
	}

	elseif (sizeof($records) > 0) {
?>

	<h2>Search results</h2>

	<p>The following matches were found <?php if (!empty($_REQUEST['postcode'])) print 'within a ' . encodeHtml($withinDistance) . ' miles radius of ' . encodeHtml($_REQUEST['postcode']); ?>:</p>

<?php
	if ($showMap) {
		printf('<div id="googleMap" class="googleMap" style="height:%dpx; width:%dpx; float:right;"></div>', 300, 500);
	}
?>

	<ul class="list icons directories">
<?php
	$locationsUsed = array();
    foreach ($records as $record) {
		if ($showMap) {
			$recordValues = getAllDirectoryEntryValues($record->id);
			foreach ($mapFieldIDs as $fieldID) {
				$fieldSettings = getDirectorySettingsForField ($fieldID);

				$markerImage = sprintf('http://%s/site/images/map_markers/red.png', DOMAIN);
				if (!empty($fieldSettings['MapMarker']->value)) {
					$markerImage = sprintf('http://%s/site/images/map_markers/%s', DOMAIN, encodeHtml($fieldSettings['MapMarker']->value));
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
				$markerInfo = sprintf('<a href="%s">%s</a>', buildDirectoryRecordURL($record->id, -1, $categoryInfoID), encodeHtml($record->title));

				$markerID = sprintf('%s_%s', $record->id, $fieldID);
	            printf('<input type="hidden" id="map_marker_image_%d" value="%s" />', $markerID, encodeHtml($markerImage));
	            printf('<input type="hidden" id="map_marker_info_%d" value="%s" />', $markerID, encodeHtml(urlencode($markerInfo)));
				if (!in_array($markerLocation, $locationsUsed)) {
					$locationsUsed[] = $markerLocation;
					printf('<input type="hidden" id="%d" class="mapMarkers" value="%s" />', $markerID, encodeHtml($markerLocation));
				}
			}
		}
?>
		<li class-"long">
	        <a href="<?php print buildDirectoryRecordURL($record->id, -1, $categoryInfoID); ?>">
		        <?php print encodeHtml($record->title); ?>
		    </a>
		</li>
<?php
    }
?>
	</ul>

	<p>
<?php
	if ($showPrevious) {
?>
		<a href="<?php print getSiteRootURL(); ?>/site/scripts/directory_search.php?<?php print encodeHtml($queryString); ?>&amp;offset=<?php print (int) $offset - $numRows; ?>">Previous</a>
<?php
	}

	if ($showNext && $showPrevious) {
		print " | ";
	}
	
	if ($showNext) {
?>
		<a href="<?php print getSiteRootURL(); ?>/site/scripts/directory_search.php?<?php print encodeHtml($queryString); ?>&amp;offset=<?php print (int) $offset + $numRows; ?>">Next</a>
<?php
	}
?>
	</p>
	
	<p>Would you like to <a href="<?php print buildDirectorySearchURL($directory->id, $categoryInfoID); ?>">search again</a>? </p>
<?php
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php");?>