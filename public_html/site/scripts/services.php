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
		$allRelatatedServices = getAllServicesWithCategory($_GET['categoryID'], true, true);
		//	Category Links
		$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		$allCategories = $lgclList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, SERVICES_APPLIED_CATEGORIES_TABLE, true);
		
		//	Category Links
		$currentCategory = $lgclList->getCategory($_GET['categoryID']);
		$dirTree = $lgclList->getFullPath($_GET['categoryID']);		
	}
	else {
		header("Location: " . buildAToZURL());
		exit;
	}
	
	// Breadcrumb and H1
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildAToZURL() . '">Council servives</a></li>';
	$levelNo = 1;
	$count = 0;
	foreach ($dirTree as $parent) {
		if ($count < sizeof($dirTree) - 1) {
			$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildAZServicesCategoryURL($parent->id, $parent->name) .'" >'. encodeHtml($parent->name) .'</a></li>';
		}
		else {
			$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($parent->name) .'</span></li>';
			$MAST_HEADING = 'Council services - '.$parent->name;
		}
		$count++;
		$levelNo++;
	}
	
	include("services.html.php");
?>