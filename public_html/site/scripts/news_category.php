<?php
	require_once("utilities/JaduStatus.php");
	require_once("JaduStyles.php");
	require_once("websections/JaduNews.php");
	require_once("JaduAppliedCategories.php");
	require_once("websections/JaduFeedManager.php");
	
	require_once("../includes/lib.php");
	
	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
		$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		$currentCategory = $lgclList->getCategory($_GET['categoryID']);
		
		//check to see if the category exists
		if ($currentCategory == null) {
			header("Location: ".getSiteRootURL().buildNewsURL());
			exit();			
		}
		
		// Category Links
		$allCategories = $lgclList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, NEWS_APPLIED_CATEGORIES_TABLE, true);	
		
		// need to get the top news due to versioning bug
		// where top story is based on db flag, not versioned flag
		$tempTopNews = getTopNews(true, true);
		
		$allNewsWithCat = getAllNewsWithCategory ($_GET['categoryID'], true, true);

		
		$dirTree = $lgclList->getFullPath($_GET['categoryID']);	
		
		$allNews = Array();
		foreach($allNewsWithCat as $news) {
			if ($tempTopNews != null && $news->id == $tempTopNews->id) {
				$topNews = $news;
			}
			else {
				$allNews[] = $news;
			}
		}
		
		usort($allNews, 'news_sort');
		
		if (!isset($topNews)) {
			$topNews = $allNews[0];
			unset($allNews[0]);
		}
	}
	else {
		header("Location: ".getSiteRootURL().buildNewsURL());
		exit();
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = $currentCategory->name .' news';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildNewsURL() .'" >Latest news</a></li>';
	$levelNo = 1;
	$count = 0;
	foreach ($dirTree as $parent) {
		if ($count < sizeof($dirTree) - 1) {
			$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildNewsURL($parent->id) .'" >'. encodeHtml($parent->name) .'</a></li>';
		}
		else {
			$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($parent->name) .'</span></li>';
		}
		$count++;
		$levelNo++;
	}

	include("news_category.html.php");
?>