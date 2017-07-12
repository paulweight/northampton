<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");

	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("egov/JaduCL.php");

	// for homepages
	include_once("websections/JaduHomepages.php");
	include_once("websections/JaduHomepageWidgetsToHomepages.php");
	include_once("websections/JaduHomepageWidgets.php");
	include_once("websections/JaduHomepageWidgetSettings.php");

	if (!isset($_GET['homepageID']) && !is_numeric($_GET['homepageID'])) {
		header('Location:../index.php');
		exit;
	}

	// Preview homepage widgets
	include_once('utilities/JaduAdministrators.php');
	$preview = (isset($isPreviewLink) && $isPreviewLink && isset($allowPreview) && $allowPreview);
	$homepage = getHomepage($_GET['homepageID'], !$preview);
	
	if ($homepage == null || $homepage->id < 0) {
		header('Location: ' . getSiteRootURL());
		exit;
	}
	
	// get first category for homepage
	$categories = getAllCategories(HOMEPAGE_CATEGORIES_TABLE, $homepage->id);
	foreach($categories as $category) {
		if ($category->categoryType == BESPOKE_CATEGORY_LIST_NAME) {
			$_GET['categoryID'] = $category->categoryID;
			break;
		}
	}

	$widgets = $homepage->getWidgetsToHomepages();

	$homepageSections = array();
	foreach ($widgets as $content) {
		if (!isset($homepageSections[$content->positionY])) {
			$homepageSections[$content->positionY] = array();
		}
		if ($content->stackPosition > 0) {
			if (!isset($homepageSections[$content->positionY][$content->positionX])) {
				$homepageSections[$content->positionY][$content->positionX] = array();
			}
			$homepageSections[$content->positionY][$content->positionX][] = $content;
		}
		else {
			$homepageSections[$content->positionY][] = $content;
		}
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = $homepage->title;
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>'. encodeHtml($homepage->title) .'</span></li>';
	
	include("home_info.html.php");
?>
