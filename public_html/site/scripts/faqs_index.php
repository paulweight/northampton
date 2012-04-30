<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduCategories.php");
	include_once("websections/JaduFAQ.php");
	include_once("../includes/lib.php");
	
	if (isset($_GET['faqID']) && is_numeric($_GET['faqID'])) {
		$faq = getFAQ($_GET['faqID']);
		// 404 error on invalid faqID values
		if ($faq->id == -1 || $faq == null) {
			include_once('../../404.php');
			exit();
		}
	}	

	$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
	$allRootCategories = $lgclList->getTopLevelCategories();
	$rootCategories = filterCategoriesInUse($allRootCategories, FAQS_APPLIED_CATEGORIES_TABLE, true);

	$commonFAQs = getTopXFAQs (10);
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Frequently asked questions';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Frequently asked questions</span></li>';
	
	include("faqs_index.html.php");
?>