<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("multimedia/JaduMultimediaGalleries.php");
	include_once("JaduAppliedCategories.php");
	include_once("JaduMetadata.php");
	
	if (!isset($_GET['galleryID']) || !is_numeric($_GET['galleryID']) || $_GET['galleryID'] < 1) {
		header("Location: " . buildMultimediaGalleriesURL());
		exit;
	}
	
	$gallery = getMultimediaGallery($_GET['galleryID'], array('live' => true, 'approved' => true));
	if ($gallery) {
		$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		$categoryID = getFirstCategoryIDForItemOfType (MULTIMEDIA_GALLERY_CATEGORIES_TABLE, $gallery->id, BESPOKE_CATEGORY_LIST_NAME);	
		$currentCategory = $lgclList->getCategory($categoryID);
		$dirTree = $lgclList->getFullPath($categoryID);
		
		$numGalleryItems = $gallery->getNumItems();
		if ($numGalleryItems > 0) {
			// Set the current page
			if (!isset($_GET['currentPage']) || $_GET['currentPage'] < 1) {
				$currentPage = 1;
			}
			else {
				$currentPage = $_GET['currentPage'];
			}
		
			$itemsPerPage = 12;
			$PAGE_NUMBERS_TO_DISPLAY = 10;
			$offset = (($currentPage-1) * $itemsPerPage);
			$pageCount = ceil($numGalleryItems / $itemsPerPage);
			if ($offset > $numGalleryItems) {
				$offset = $numGalleryItems - $itemsPerPage;
				$currentPage = $pageCount;
			}
			if ($currentPage < $pageCount) {
				$nextPage = $currentPage + 1;
			}
			if ($currentPage > 1) {
				$previousPage = $currentPage - 1;
			}
		
			$allGalleryItems = $gallery->getItems($itemsPerPage, $offset);
		}
	}
	else {
		$gallery = new MultimediaGallery();
		$dirTree = array();
	}
	
	$maxSize = 780;
	
	// Breadcrumb, H1 and Title
	if ($gallery->id == '-1'){
		$MAST_HEADING = 'Gallery not found';
	}
	else {
		$MAST_HEADING = $gallery->title;
	}
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildMultimediaGalleriesURL() . '">Galleries</a></li>';
	foreach ($dirTree as $parent) {
		$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMultimediaGalleriesURL($parent->id) .'" >'. encodeHtml($parent->name) .'</a></li>';
	}
	$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($gallery->title) .'</span></li>';
	
	include("gallery_info.html.php");
?>