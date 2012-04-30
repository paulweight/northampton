<?php
	include_once('utilities/JaduStatus.php');
	include_once('JaduStyles.php');
	include_once('JaduMetadata.php');
	include_once('JaduCategories.php');
	include_once('websections/JaduDownloads.php');
	include_once('websections/JaduDownloadPasswords.php');
	include_once('egov/JaduCL.php');
	include_once('JaduUpload.php');
	include_once('utilities/JaduMostPopular.php');
	if (defined('RETAIL_PRODUCT_DOWNLOADS_ENABLED') && RETAIL_PRODUCT_DOWNLOADS_ENABLED) {
		include_once('retail/JaduRetailProducts.php');
		include_once('retail/JaduRetailProductsToDownloads.php');
		include_once('retail/JaduRetailReadableURLs.php');
	}

	// Initialise the criteria
	$criteria = array(
	    'approved' => true,
	    'live' => true
	);
	
	$showDownload = false;
	$addToBasket = false;
	$showProductLink = false;
	if (isset($_GET['fileID']) && is_numeric($_GET['fileID'])) {
		$fileID = $_GET['fileID'];
		
		if (defined('RETAIL_PRODUCT_DOWNLOADS_ENABLED') && RETAIL_PRODUCT_DOWNLOADS_ENABLED) {
			$retailMapping = getAllOnlineProductsToDownloadRecordsForDownloadFileID($fileID);

			if (!empty($retailMapping)) {
				if (sizeof($retailMapping) == 1) {
					$addToBasket = true;
					$product = getProduct($retailMapping[0]->productID);
					if ($product->id == -1) {
						$addToBasket = false;
					}
				}
				else {
					$showProductLink = true;
				}
			}
		}

		$fileItem = getDownloadFile($fileID, $criteria);
		if ($fileItem !== null && $fileItem->id > 0) {
			$download = getDownload($fileItem->downloadID, $criteria);
		}
	}
	else if (isset($_GET['downloadID']) && is_numeric($_GET['downloadID'])) {
		$download = getDownload($_GET['downloadID'], $criteria);
		if ($download->id > 0) {
			$allFiles = getAllDownloadFilesForDownload($download->id, $criteria);
		}
	}

	if (isset($download) && $download->id > 0) {
		if ($download->passwordID > 0 && isset($_POST['password']) && checkDownloadPassword($download->id, $_POST['password'])) {
			$_SESSION['authenticaedDownloadIDs'][] = $download->id;
		}
		
		if ($download->passwordID < 1 || (isset($_SESSION['authenticaedDownloadIDs']) && in_array($download->id, $_SESSION['authenticaedDownloadIDs']))) {
			$showDownload = true;
		}
		
		$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		$categoryID = getFirstCategoryIDForItemOfType(DOWNLOADS_CATEGORIES_TABLE, $download->id, BESPOKE_CATEGORY_LIST_NAME);
		$_GET['categoryID'] = $categoryID;
		$currentCategory = $lgclList->getCategory($categoryID);
		$dirTree = $lgclList->getFullPath($categoryID);
	}
	else {
		header('HTTP/1.0 404 Not Found');
		$dirTree = array();
	}


	// Breadcrumb, H1 and Title
	if ($download->id == '-1'){
		$MAST_HEADING = 'Download not found';
	}
	else {
		$MAST_HEADING = $download->title;
	}
	
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildDownloadsURL(). '">Downloads</a></li>';
	foreach ($dirTree as $parent) {
		$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildDownloadsURL($parent->id).'" >'. encodeHtml($parent->name) .'</a></li>';
	}
	$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($download->title) .'</span></li>';

	$title = '';
	if(isset($fileItem)) {
		$title .= $fileItem->title . ' - ';
	}
	if (isset($download)) {
		$title .= $download->title . ' - ';
	}
	
	include("download_info.html.php");
?>