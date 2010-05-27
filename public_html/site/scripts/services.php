<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	
	include_once("egov/JaduEGovJoinedUpServices.php");
	include_once("egov/JaduEGovJoinedUpServicesContacts.php");
	include_once("egov/JaduPIDList.php");
	
	include_once("JaduCategories.php");
	include_once("JaduAppliedCategories.php");
	include_once("egov/JaduCL.php");
	
	include("../includes/lib.php");

	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
		$allRelatatedServices = getAllServicesWithCategory ($_GET['categoryID'], true);
		//	Category Links
		$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		$allCategories = $lgclList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, SERVICES_APPLIED_CATEGORIES_TABLE, true);
		$splitArray = splitArray($categories);	
		
		//	Category Links
		$currentCategory = $lgclList->getCategory($_GET['categoryID']);
		$dirTree = $lgclList->getFullPath($_GET['categoryID']);		
	}
	else {
		header("Location: az_home.php");
		exit;
	}
	$breadcrumb = 'serviceCats';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $currentCategory->name; ?> downloads | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print $parent->name . ', '; } ?><?php foreach ($dirTree as $parent) { print $parent->name . ', '; } ?><?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s Services availiable within the following categories<?php foreach ($dirTree as $parent) { print ', ' . $parent->name; } ?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> downloads<?php foreach ($dirTree as $parent) { ?> | <?php print $parent->name; } ?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s Services availiable within the following categories<?php foreach ($dirTree as $parent) { print ', ' . $parent->name; } ?>" />

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
				<li><a href="http://<?php print $DOMAIN ?>/site/scripts/services.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a> </li>
<?php
			}
			print '</ul>';
		}

		if (sizeof($splitArray['right']) > 0) {
			print '<ul class="info_right list">';
			foreach ($splitArray['right'] as $subCat) {
?>
				<li><a href="http://<?php print $DOMAIN ?>/site/scripts/services.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a> </li>
<?php
			}
			print '</ul>';
		}
?>
				<div class="clear"></div>
			</div>
<?php
	}

	if (sizeof($allRelatatedServices) > 0) {
?>
		<h2>Available Services</h2>
		<ul class="list">
<?php
		foreach ($allRelatatedServices as $service) {
?>
			<li><a href="http://<?php print $DOMAIN ?>/site/scripts/services_info.php?serviceID=<?php print $service->id ?>"><?php print $service->title; ?></a></li>
<?php
		}
?>
		</ul>
<?php
	}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>