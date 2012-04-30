<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php"); 
	include_once("websections/JaduFAQ.php");
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");
	include_once("../includes/lib.php");
	
	if (isset($_GET['faqID']) && is_numeric($_GET['faqID'])) {
		$faq = getFAQ($_GET['faqID'], FAQ_PROCESSED);
		// 404 error on invalid faqID values
		if ($faq->id == -1 || $faq == null) {
			include_once('../../404.php');
			exit();
		}
	}

	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
		$allFAQs = getAllFAQsWithCategory ($_GET['categoryID'], FAQ_PROCESSED);
		
		//	Category Links
		$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		
		// 404 error on invalid category ID
		if ($lgclList->getCategory($_GET['categoryID']) === null) {
			include_once('../../404.php');
			exit();	
		}
		
		$allCategories = $lgclList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, FAQS_APPLIED_CATEGORIES_TABLE, true);

		// Check if current page has FAQs or categories to show, if not show 404
		if (empty($categories) && empty($allFAQs)) {
			include_once('../../404.php');
			exit();
		}
		
		//	Category Links
		$currentCategory = $lgclList->getCategory($_GET['categoryID']);
		$dirTree = $lgclList->getFullPath($_GET['categoryID']);		
	} 
	else {
		$dirTree = array();
	}
	
	// Breadcrumb, H1 and Title
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildFAQURL() .'">Frequently asked questions</a></li>';
			$levelNo = 1;
			$count = 0;
			foreach ($dirTree as $parent) {
				if ($count < sizeof($dirTree) - 1) {
					$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildFAQURL(false, $parent->id) .'" >'. encodeHtml($parent->name) .'</a></li>';
				}
				else {
					$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($parent->name) .'</span></li>';
					$MAST_HEADING = 'Frequently asked questions - ' .$parent->name;
				}
				$count++;
				$levelNo++;
			}
	
	include("faqs.html.php");
?>