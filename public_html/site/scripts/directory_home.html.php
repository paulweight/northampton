<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

<?php
	include_once("../includes/stylesheets.php");
	include_once("../includes/metadata.php");
	
	$metadata = getMetadataForItem (DIRECTORY_METADATA_TABLE, $directory->id);
	if ($metadata->subject != '') {
		$metadata->subject .= ',';
	}
	$metadata->subject .= METADATA_GENERIC_KEYWORDS;
	if ($metadata->description == '') {
		$metadata->description = METADATA_GENERIC_NAME . '\'s ' . $directory->name . ' information';
		foreach ($dirTree as $parent) {
			$metadata->description .= ' | ' . $parent->title;
		}
	}
?>

	<meta name="Keywords" content="<?php print encodeHtml($metadata->subject); ?>,directory" />
	<meta name="Description" content="<?php print encodeHtml($metadata->description); ?>" />

	<?php printMetadata(DIRECTORY_METADATA_TABLE, DIRECTORY_CATEGORIES_TABLE, $directory->id, $directory->name, "http://".DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<p><?php print nl2br(encodeHtml($directory->description)); ?></p>

	<ul>
		<li>View the <a href="<?php print buildDirectoryAZURL($directory->id); ?>">A to Z of records</a></li>
<?php
	if ($directory->allowPublicSubmissions == '1') {
?>
		<li>Make a <a href="<?php print buildDirectoriesURL(-1, $directory->id, true); ?>">submission to this directory</a></li>
<?php
	}
?>
	</ul>

<?php
    if (sizeof($categories) > 0) {
?>
    	<div class="cate_info">
    		<h2>Categories in <?php print encodeHtml($directory->name); ?></h2>
<?php
        if (sizeof($categories) > 0) {
?>
    		<ul class="list">
<?php
            foreach ($categories as $category) {
                $categoryInfo = getDirectoryCategoryInformationForCategory($category->id);
?>
    			<li><a href="<?php print buildDirectoryCategoryURL($directory->id, $category->id, $categoryInfo->id); ?>"><?php print encodeHtml($category->title); ?></a></li>
<?php
            }
?>
    		</ul>
<?php
        }
?>
    	</div>
<?php
    }

	if(sizeof($records) > 0) {
?>
   	<div class="cate_info">
    	<h2>Browse <?php print encodeHtml($directory->name); ?></h2>

<?php
        if (sizeof($records) > 0) {
?>
    		<ul class="list">
<?php
            foreach ($records as $record) {
?>
    			<li><a href="<?php print buildDirectoryRecordURL($record->id); ?>"><?php print encodeHtml($record->title); ?></a></li>
<?php
            }
?>
    		</ul>
<?php
        }
?>
	</div>
<?php
	}
?>
	<form enctype="multipart/form-data" action="<?php print getSiteRootURL(); ?>/site/scripts/directory_search.php" method="get">
		<legend>Search the directory</legend>
			<fieldset>
			<input type="hidden" name="directoryID" value="<?php print (int) $directory->id; ?>" />
			<p>
				<label for="keywords">Keywords</label>
				<input type="text" name="keywords" value="" id="keywords" />
				<input type="submit" value="Search" name="search" />
			</p>
			<p>Try the <a href="<?php print buildDirectorySearchURL($directory->id); ?>">advanced search</a></p>
		</fieldset>
	</form>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>