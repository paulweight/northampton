<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("egov/JaduCL.php");
	include_once("JaduAppliedCategories.php");
	include_once("websections/JaduContact.php");
	include_once("websections/JaduNews.php");	
	include_once("egov/JaduEGovMeetingMinutes.php");
	
	$address = new Address();
	
	$lgcl = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
	$allRootCategories = $lgcl->getTopLevelCategories();	
	
	//	Documents top level useage	
	$rootDocumentCategories = filterCategoriesInUse($allRootCategories, DOCUMENTS_APPLIED_CATEGORIES_TABLE, true);
	$rootHomepageCategories = filterCategoriesInUse($allRootCategories, HOMEPAGE_APPLIED_CATEGORIES_TABLE, true);
	$rootFormCategories = filterCategoriesInUse($allRootCategories, XFORMS_FORM_APPLIED_CATEGORIES_TABLE, true);
	$rootDownloadCategories = filterCategoriesInUse($allRootCategories, DOWNLOADS_APPLIED_CATEGORIES_TABLE, true);
	$rootGalleryCategories = filterCategoriesInUse($allRootCategories, MULTIMEDIA_GALLERY_APPLIED_CATEGORIES_TABLE, true);
	$rootPodcastCategories = filterCategoriesInUse($allRootCategories, MULTIMEDIA_PODCAST_APPLIED_CATEGORIES_TABLE, true);
	$rootNewsCategories = filterCategoriesInUse($allRootCategories, NEWS_APPLIED_CATEGORIES_TABLE, true);
	$rootPressCategories = filterCategoriesInUse($allRootCategories, PRESS_RELEASES_APPLIED_CATEGORIES_TABLE, true);
	$rootEventsCategories = filterCategoriesInUse($allRootCategories, EVENTS_APPLIED_CATEGORIES_TABLE, true);	
	$meetingHeaders = getAllMeetingMinutesHeaders();

	$categoriesUsed = array();
	$rootCategories = array();
	
	foreach ($rootDocumentCategories as $item) {
		$categoriesUsed[] = $item->id;
		$rootCategories[] = $item;
	}
	
	foreach ($rootHomepageCategories as $item) {
		if (!in_array($item->id, $categoriesUsed)) {
			$categoriesUsed[] = $item->id;
			$rootCategories[] = $item;
		}
	}
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Site map';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Site map</span></li>';
	
	include("site_map.html.php");
?>