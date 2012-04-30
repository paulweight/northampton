<?php
    include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("multimedia/JaduMultimediaGalleries.php");
	include_once("egov/JaduCL.php");

	include("../includes/lib.php");
	
	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
		$allGalleries = getAllMultimediaGalleries(array(
			'category' => $_GET['categoryID'],
			'live' => true,
			'visible' => true,
			'approved' => true
		));
		
		//	Category Links
		$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		$allCategories = $lgclList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, MULTIMEDIA_GALLERY_APPLIED_CATEGORIES_TABLE, true);
		
		//	Category Links
		$currentCategory = $lgclList->getCategory($_GET['categoryID']);
		$dirTree = $lgclList->getFullPath($_GET['categoryID']);
	}
	else {
		header("Location: " . buildMultimediaGalleriesURL());
		exit;
	}
	
	// Breadcrumb, H1 and Title
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildMultimediaGalleriesURL() . '">Galleries</a></li>';
	$levelNo = 1;
	$count = 0;
	foreach ($dirTree as $parent) {
		if ($count < sizeof($dirTree) - 1) {
			$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMultimediaGalleriesURL($parent->id) . '" >' . encodeHtml($parent->name) . '</a></li>';
		}
		else {
			$MAST_BREADCRUMB .= '<li><span>' . encodeHtml($parent->name) . '</span></li>';
			$MAST_HEADING = 'Galleries - ' . encodeHtml($parent->name);
		}
		$count++;
		$levelNo++;
	}

	$MAST_HEADING = METADATA_GENERIC_NAME;
	
	include("galleries.html.php");
?>