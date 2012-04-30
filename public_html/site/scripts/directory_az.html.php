<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($directory->name); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="directory, <?php print encodeHtml($directory->name);?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> A-Z of directory entries" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (isset($categoryInfo) && $categoryInfo->id != '-1') {
?>
    <a id="brand" href="<?php print getSiteRootURL() . buildDirectoryCategoryURL($directory->id, (int) $_REQUEST['categoryID']); ?>">
	    <img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($categoryInfo->imageFilename); ?>" alt="<?php print encodeHtml(getImageProperty($categoryInfo->imageFilename, 'altText')); ?> " />
	</a>
<?php
	}
?>

	<ul class="alphabeticNav">
<?php
            foreach (range('a','z') as $letter) {
?>
		<li><a href="<?php print buildDirectoryAZURL($directory->id, $letter, $categoryInfo->id); ?>"><span class="hidden">Records beginning with </span><?php print encodeHtml($letter); ?></a></li>
<?php
            }
?>
	</ul>

<?php
	if(sizeof($records) > 0) {
?>

	<h2>Records starting with <?php print encodeHtml($entryStartsWith); ?></h2>
	<ul>
<?php 
		foreach ($records as $record) {
?>
		<li><a href="<?php print buildDirectoryRecordURL($record->id, $category->id, $categoryInfo->id); ?>"><?php print encodeHtml($record->title); ?></a></li>
<?php
		}
?>
	</ul>

<?php
	}
	else {
?>
	<h2>Sorry, no records found starting with <?php print encodeHtml($entryStartsWith); ?></h2>
<?php
	}
?>
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php");?>