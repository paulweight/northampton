<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("multimedia/JaduMultimediaPodcasts.php");
	include_once("egov/JaduCL.php");

	include("../includes/lib.php");
	
	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
		$allPodcasts = getAllMultimediaPodcasts(array(
			'category' => $_GET['categoryID'],
			'live' => true,
			'visible' => true
		));
		
		// Category Links
		$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		$allCategories = $lgclList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, MULTIMEDIA_PODCAST_APPLIED_CATEGORIES_TABLE, true);
		$splitArray = splitArray($categories);
		
		// Category Links
		$currentCategory = $lgclList->getCategory($_GET['categoryID']);
		$dirTree = $lgclList->getFullPath($_GET['categoryID']);
	}
	else {
		header("Location: podcasts_index.php");
		exit;
	}
	
	$breadcrumb = 'podcastCats';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $currentCategory->name; ?> podcasts | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print $parent->name . ', '; } ?><?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s podcasts available for download within the following categories<?php foreach ($dirTree as $parent) { print ', ' . $parent->name; } ?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> podcasts<?php foreach ($dirTree as $parent) { ?> | <?php print $parent->name; } ?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s podcasts available for download within the following categories<?php foreach ($dirTree as $parent) { print ', ' . $parent->name; } ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
<?php
if (sizeof($splitArray['left']) > 0 || sizeof($splitArray['right']) > 0) {
?>
	<div class="cate_info">
		<h2><?php print $parent->name; ?> categories</h2>
<?php
	if (sizeof($splitArray['left']) > 0) {
?>
		<ul class="info_left list">
<?php
		foreach ($splitArray['left'] as $subCat) {
?>
			<li><a href="http://<?php print $DOMAIN ?>/site/scripts/podcasts.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a> </li>
<?php
		}
?>
		</ul>
<?php
	}
	
	if (sizeof($splitArray['right']) > 0) {
?>
		<ul class="info_right list">
<?php
		foreach ($splitArray['right'] as $subCat) {
?>
			<li><a href="http://<?php print $DOMAIN ?>/site/scripts/podcasts.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a> </li>
<?php
		}
		print '</ul>';
	}
?>
		<div class="clear"></div>
	</div>
<?php
}

if (count($allPodcasts) > 0) {
?>
	<h2>Available podcasts</h2>
<?php
	foreach ($allPodcasts as $podcast) {
		$podcast->title = htmlentities($podcast->title, ENT_QUOTES, 'UTF-8');
		$podcast->summary = htmlentities($podcast->summary, ENT_QUOTES, 'UTF-8');
?>
		<div class="download_box">
			<h3><a href="http://<?php print $DOMAIN ?>/site/scripts/podcast_info.php?podcastID=<?php print $podcast->id; ?>"><?php print $podcast->title ;?></a></h3>
			<p><?php print nl2br($podcast->summary); ?></p>
		</div>
<?php
	}
}

include("../includes/related_info.php");
?>
	
<!-- The Contact box -->
<?php include("../includes/contactbox.php"); ?>
<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>