<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("egov/JaduXFormsForm.php");
	
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");

	include_once("../includes/lib.php");
	
	$bespokeCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$allRootCategories = $bespokeCategoryList->getTopLevelCategories();
	$rootCategories = filterCategoriesInUse($allRootCategories, XFORMS_FORM_APPLIED_CATEGORIES_TABLE, true, BESPOKE_CATEGORY_LIST_NAME);
	
	$topForms = getTopXRequestedForms (10, true);
	
	$breadcrumb = 'xformsIndex';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Online forms | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="forms, form, application, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online forms" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online forms" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online forms" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

	<!-- live search code -->
	<script type="text/javascript" src="http://<?php print $DOMAIN;?>/site/javascript/prototype.js"></script>
	<script type="text/javascript" src="http://<?php print $DOMAIN;?>/site/javascript/xforms_live_search.js"></script>

</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php 
    if (!isset($_SESSION['userID'])){
?>
		<p class="first">Some forms may require you to <a href="http://<?php print $DOMAIN;?>/site/index.php?sign_in=true">sign in</a> to your account. To create an account, <a href="http://<?php print $DOMAIN;?>/site/scripts/register.php">register here</a>.</p>

<?php 
		} 
		
		if (sizeof($topForms) > 0) { 
?>
	<div class="pop_az">
		<h2>Most popular online forms</h2>
		<ol>
<?php 
			foreach($topForms as $topForm) {
?>
			<li>
				<a href="http://<?php print $DOMAIN;?>/site/scripts/xforms_form.php?formID=<?php print $topForm->id;?>"><?php print $topForm->title; ?></a> 
<?php 
				if(!isset($_SESSION['userID']) && $topForm->allowUnregistered == 0) { 
?>
				<img class="lock" src="http://<?php print $DOMAIN; ?>/site/images/icon_lock.gif" alt="Padlock graphic" title="You must sign-in to use this form" />
<?php 
				} 
?>
			</li>
<?php 
			} 
?> 
		</ol>
	</div>
<?php 
		} 
?>

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

<?php

	foreach ($rootCategories as $rootCat) {	
		$relCats = filterCategoriesInUse($bespokeCategoryList->getChildCategories($cat->id), XFORMS_FORM_APPLIED_CATEGORIES_TABLE, true, BESPOKE_CATEGORY_LIST_NAME);
		splitArray($relCats);
?>
	<div class="cate_info">
		<h2><a href="http://<?php print $DOMAIN; ?>/site/scripts/forms.php?categoryID=<?php print $rootCat->id;?>"><?php print $rootCat->name; ?></a></h2>
<?php
		if (sizeof($splitArray['left']) > 0) {
			print '<ul class="info_left">';
			foreach ($splitArray['left'] as $subCat) {
?>
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/forms.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a></li>
<?php
			}
			print '</ul>';
		}
		
		if (sizeof($splitArray['right']) > 0) {
			print '<ul class="info_right">';
			foreach ($splitArray['right'] as $subCat) {
?>
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/forms.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a></li>
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
	<!-- END of the Contact box -->

	<script type="text/javascript">
		var theButton = document.getElementById('nonJavascriptButton');
		theButton.style.display = 'none';
	</script>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>