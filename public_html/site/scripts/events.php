<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("egov/JaduCL.php");
	include_once("websections/JaduEvents.php");

	include_once("../includes/lib.php"); 

	$dirTree = array();

	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {

		$allEvents = getAllEventsWithCategory($_GET['categoryID'], true);
		$splitEvents = splitArray($allEvents);
		
		//	Category Links
		$bespokeCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		$allCategories = $bespokeCategoryList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, EVENTS_APPLIED_CATEGORIES_TABLE, true);
		$splitArray = splitArray($categories);

		//	Category Links
		$currentCategory = $bespokeCategoryList->getCategory($_GET['categoryID']);
		$dirTree = $bespokeCategoryList->getFullPath($_GET['categoryID']);
		$leftCategoryID = $_GET['categoryID'];
	}
	else {
		header("Location: events_index.php");
		exit;
	}
	
	$breadcrumb = 'eventsCats';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $currentCategory->name; ?> events | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print $parent->name . ', '; } ?><?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s index of documents and pages organised within the following categories<?php foreach ($dirTree as $parent) { print ', ' . $parent->name; } ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
	
<?php
	if (sizeof($splitEvents['left']) > 0 || sizeof ($splitEvents['right']) > 0) {
?>
		<div class="cate_info">
			<h2><?php print $parent->name; ?> related events</h2>
<?php
		if (sizeof($splitEvents['left']) > 0) {
			print '<ul class="info_left">';
			foreach ($splitEvents['left'] as $event) {
?>
					<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/events_info.php?categoryID=<?php print $_GET['categoryID']; ?>&amp;eventID=<?php print $event->id; ?>"><?php print $event->title; ?></a></li>
<?php
			}
			print '</ul>';
		}
		if (sizeof($splitEvents['right']) > 0) {
			print '<ul class="info_right">';
			foreach ($splitEvents['right'] as $event) {
?>
					<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/events_info.php?categoryID=<?php print $_GET['categoryID']; ?>&amp;eventID=<?php print $event->id; ?>"><?php print $event->title; ?></a></li>
<?php
			}
			print '</ul>';
		}
?>
		</div>
<?php
	}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
