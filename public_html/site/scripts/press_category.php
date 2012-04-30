<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduPressReleases.php");
	include_once("JaduAppliedCategories.php");
	include_once("websections/JaduFeedManager.php");
	
	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
		$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		$categoryViewing = $lgclList->getCategory($_GET['categoryID']);
		
		//check to see if the category exists
		if ($categoryViewing == null) {
			header('Location: '.getSiteRootURL() . buildPressURL());
			exit();			
		}
		
		//	Category Links
		$allCategories = $lgclList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, PRESS_RELEASES_APPLIED_CATEGORIES_TABLE, true);
		$splitArray = splitArray($categories);	
		
		$dirTree = $lgclList->getFullPath($_GET['categoryID']);	
		
		// need to get the top press release due to versioning bug
		// where top story is based on db flag, not versioned flag
		$tempTopPressRelease = getTopPressReleases(true, true);
		
		$allPressReleasesWithCat = getAllPressReleasesWithCategory ($_GET['categoryID'], true, true, BESPOKE_CATEGORY_LIST_NAME);

		$allPressReleases = Array();
		foreach($allPressReleasesWithCat as $pressRelease) {
			if ($tempTopPressRelease != null && $pressRelease->id == $tempTopPressRelease->id) {
				$topPressRelease = $pressRelease;
			}
			else {
				$allPressReleases[] = $pressRelease;
			}
		}
		
		usort($allPressReleases, 'press_sort');
		
		if ($topPressRelease == null && sizeof($allPressReleases) > 0) {
			$topPressRelease = $allPressReleases[0];
			unset($allPressReleases[0]);
		}
	}
	else {
		header('Location: '.getSiteRootURL() . buildPressURL());
		exit();
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = $categoryViewing->name .' press releases';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildPressURL() . '" >Press releases</a></li>';
	$levelNo = 1;
	$count = 0;
	foreach ($dirTree as $parent) {
		if ($count < sizeof($dirTree) - 1) {
			$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildPressURL($parent->id, $parent->name) .'" >'. encodeHtml($parent->name) .'</a></li>';
		}
		else {
			$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($parent->name) .'</span></li>';
		}
		$count++;
		$levelNo++;
	}

	include("press_category.html.php");
?>