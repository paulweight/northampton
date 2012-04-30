<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("multimedia/JaduMultimediaGalleries.php");
	include_once("multimedia/JaduMultimediaItems.php");
	include_once("JaduAppliedCategories.php");
	include_once("JaduMetadata.php");
	
	if (isset($_GET['galleryID']) && is_numeric($_GET['galleryID']) && isset($_GET['itemID']) && is_numeric($_GET['itemID'])) {
		$gallery = getMultimediaGallery($_GET['galleryID'], array('live' => true, 'approved' => true));

		if ($gallery) {
			$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
			$categoryID = getFirstCategoryIDForItemOfType (MULTIMEDIA_GALLERY_CATEGORIES_TABLE, $gallery->id, BESPOKE_CATEGORY_LIST_NAME);	
			$currentCategory = $lgclList->getCategory($categoryID);
			$dirTree = $lgclList->getFullPath($categoryID);
			
			$galleryItem = getMultimediaGalleryItem($_GET['galleryID'], $_GET['itemID']);
			if ($galleryItem) {
				$item = $galleryItem->getItem();

				if ($previousGalleryItem = $galleryItem->getPrevious()) {
					$previousItem = $previousGalleryItem->getItem();
				}

				if ($nextGalleryItem = $galleryItem->getNext()) {
					$nextItem = $nextGalleryItem->getItem();
				}
			}
		}
	}
	else {
		header("Location: " . buildMultimediaGalleriesURL());
		exit;
	}
	
	if (!isset($gallery) || !$gallery) {
		$gallery = new MultimediaGallery();
		$dirTree = array();
	}
	
	if (!isset($galleryItem) || !$galleryItem) {
		$galleryItem = new MultimediaGalleryItem();
		$item = new MultimediaItem();
	}
	
	$maxSize = 680;
	
	// Breadcrumb, H1 and Title
	if ($item->id == '-1'){
		$MAST_HEADING = 'Gallery item not found';
	}
	else {
		$MAST_HEADING = $gallery->title;
	}
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildMultimediaGalleriesURL() . '">Galleries</a></li>';
	foreach ($dirTree as $parent) {
		$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMultimediaGalleriesURL($parent->id) . '" >'. encodeHtml($parent->name) .'</a></li>';
	}
	$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMultimediaGalleriesURL(-1, $gallery->id) . '" >'. encodeHtml($gallery->title) .'</a></li>';
	$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($item->title) .'</span></li>';
	
	include("gallery_item.html.php");
?>