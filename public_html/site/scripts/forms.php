<?php
	include_once("utilities/JaduStatus.php");
	
	// Force onto SSL if required.
	if ((defined('SSL_ENABLED') && SSL_ENABLED) && PROTOCOL != 'https://') {
		header("Location: ".getSecureSiteRootURL().$_SERVER['REQUEST_URI']);
		exit;
	}
	
	include_once("JaduStyles.php");
	
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	
	include_once("egov/JaduXFormsForm.php");
	include_once("egov/JaduCL.php");
	
	include_once("../includes/lib.php");	

	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
		$allXForms = getAllFormsWithCategory($_GET['categoryID'], true, true, BESPOKE_CATEGORY_LIST_NAME);
		
		//	Category Links
		$bespokeCategoryList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		$allCategories = $bespokeCategoryList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, XFORMS_FORM_APPLIED_CATEGORIES_TABLE, true, BESPOKE_CATEGORY_LIST_NAME);
		
		//	Category Links
		$currentCategory = $bespokeCategoryList->getCategory($_GET['categoryID']);
		$dirTree = $bespokeCategoryList->getFullPath($_GET['categoryID']);		
	}
	else {
		header("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}
	$breadcrumb = 'formCats';
	
	include("forms.html.php");
?>