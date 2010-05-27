<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	
	include_once("egov/JaduXFormsForm.php");
	include_once("egov/JaduCL.php");
	
	include_once("../includes/lib.php");	
	
	if (isset($categoryID)) {
		$allXForms = getAllFormsWithCategory($categoryID, true, true, BESPOKE_CATEGORY_LIST_NAME);
		
		//	Category Links
		$bespokeCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		$allCategories = $bespokeCategoryList->getChildCategories($categoryID);
		$categories = filterCategoriesInUse($allCategories, XFORMS_FORM_APPLIED_CATEGORIES_TABLE, true, BESPOKE_CATEGORY_LIST_NAME);
		$splitArray = splitArray($categories);
		
		//	Category Links
		$currentCategory = $bespokeCategoryList->getCategory($categoryID);
		$dirTree = $bespokeCategoryList->getFullPath($categoryID);		
	}
	else {
		$dirTree = array();
	}
	$breadcrumb = 'formCats';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $currentCategory->name;?> forms | <?php print METADATA_GENERIC_COUNCIL_NAME; ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print $parent->name . ', '; } ?><?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s online forms organised within the following categories<?php foreach ($dirTree as $parent) { print ', ' . $parent->name; } ?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> online forms<?php foreach ($dirTree as $parent) { ?> | <?php print $parent->name; ?><? } ?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s online forms organised within the following categories<?php foreach ($dirTree as $parent) { print ', ' . $parent->name; } ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="http://<?php print $DOMAIN;?>/site/javascript/prototype.js"></script>
	<script type="text/javascript" src="http://<?php print $DOMAIN;?>/site/javascript/xforms_live_search.js"></script></head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
		
	<div class="pop_az">
<?php

	if (sizeof($allXForms) > 0) {
?>
	
		<h2>Available online forms</h2>
		<ul class="list">
<?php
		foreach ($allXForms as $formItem) {
?>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/xforms_form.php?formID=<?php print $formItem->id;?>"><?php print $formItem->title;?></a></li>
<?php
		}
?>
		</ul>
<?php
	}
?>

<?php
	if (sizeof($splitArray['left']) > 0 || sizeof($splitArray['right']) > 0) {
?>
		<div class="cate_info">
			<h2><?php print $parent->name; ?> categories</h2>
<?php
		if (sizeof($splitArray['left']) > 0) {
			print '<ul class="info_left">';
			foreach ($splitArray['left'] as $subCat) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/forms.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a> </li>
<?php
			}
			print '</ul>';
		}
		
		if (sizeof($splitArray['right']) > 0) {
			print '<ul class="info_right">';
			foreach ($splitArray['right'] as $subCat) {
?>
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/forms.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a> </li>
<?php
			}
			print '</ul>';
		}
?>
		</div>
<?php
	}
?>

	</div>

	<!-- Live search -->
	<div class="search_az">
		<div>
			<h3>Search for an online form</h3>
			<p id="az_live_find">
				<label for="searchText">Begin to type and select from the appearing choices below.</label>
				<input class="field" type="text" name="searchText" id="searchText" value="" />
				<img id="loading" style="display:none;" alt="Loading" src="http://<?php print $DOMAIN;?>/site/images/loading.gif" />
			</p>
			<div id="search_results"></div>
		</div>
	</div>
	
	<br class="clear" />
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>