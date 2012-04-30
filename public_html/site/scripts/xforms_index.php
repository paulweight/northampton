<?php
	include_once("utilities/JaduStatus.php");
	
	// Force onto SSL if required.
	if ((defined('SSL_ENABLED') && SSL_ENABLED) && PROTOCOL != 'https://') {
		header("Location: ".getSecureSiteRootURL().$_SERVER['REQUEST_URI']);
		exit;
	}
	
	include_once("JaduStyles.php");
	include_once("egov/JaduXFormsForm.php");
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");
	include_once("../includes/lib.php");
	
	$bespokeCategoryList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
	$allRootCategories = $bespokeCategoryList->getTopLevelCategories();
	$rootCategories = filterCategoriesInUse($allRootCategories, XFORMS_FORM_APPLIED_CATEGORIES_TABLE, true, BESPOKE_CATEGORY_LIST_NAME);
	
	$topForms = getTopXRequestedForms (10, true);
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Online Forms';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Online forms</span></li>';

	include("xforms_index.html.php"); 
?>