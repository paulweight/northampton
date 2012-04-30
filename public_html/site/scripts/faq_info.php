<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php"); 
	include_once("websections/JaduFAQ.php");
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");

	if (isset($_GET['faqID']) && is_numeric($_GET['faqID'])) {
		$faq = getFAQ($_GET['faqID']);
		// 404 error on invalid faqID values
		if ($faq->id == -1 || $faq == null) {
			include_once('../../404.php');
			exit();
		}

		if (isset($_GET['categoryID'])) {
			$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
			$categoryID = getFirstCategoryIDForItemOfType (FAQS_CATEGORIES_TABLE, $faq->id, BESPOKE_CATEGORY_LIST_NAME);	
			$currentCategory = $lgclList->getCategory($categoryID);
			$dirTree = $lgclList->getFullPath($categoryID);
		}
		else {
			$dirTree = array();
		}
	}
	else {
		header("Location: ./faqs_index.php");
		exit();
	}
	
	// Breadcrumb, H1 and Title
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildFAQURL() .'">Frequently asked questions</a></li>';
	$MAST_HEADING = 'Frequently asked questions';
	
	include("faq_info.html.php");
?>