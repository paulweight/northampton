<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("egov/JaduCL.php");

	//	old homepages
	include_once("websections/JaduDocuments.php");
	include_once("websections/JaduHomepageCategoryDefaults.php");
	
	// for homepages
	include_once("websections/JaduHomepages.php");	
	include_once("websections/JaduHomepageWidgetsToHomepages.php");		
	include_once("websections/JaduHomepageWidgets.php");		
	include_once("websections/JaduHomepageWidgetSettings.php");
	include_once("websections/JaduPageSupplements.php");	

	include("../includes/lib.php");

	$showHomepageContent = false;
	$dirTree = array();
	
	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
		$defaultCategoryHomepage = getHomepageCategoryDefaultForCategory($_GET['categoryID']);
		if ($defaultCategoryHomepage != null) {
			$homepage = getHomepage($defaultCategoryHomepage->homepageID, true);
			if ($homepage->id > 0) {
				$showHomepageContent = true;
				
				$homepageSections = array();
				foreach ($homepage->getWidgetsToHomepages() as $content) {
					if (!isset($homepageSections[$content->positionY])) {
						$homepageSections[$content->positionY] = array();
					}
					if ($content->stackPosition > 0) {
						if (!isset($homepageSections[$content->positionY][$content->positionX])) {
							$homepageSections[$content->positionY][$content->positionX] = array();
						}
						if (isset($homepageSections[$content->positionY][$content->positionX]) && is_object($homepageSections[$content->positionY][$content->positionX])) {
							$oldObject = $homepageSections[$content->positionY][$content->positionX];
							$homepageSections[$content->positionY][$content->positionX] = array();	
							$homepageSections[$content->positionY][$content->positionX][] = $oldObject;
						}
						$homepageSections[$content->positionY][$content->positionX][] = $content;
					}
					else {
						$homepageSections[$content->positionY][] = $content;
					}
				}
				// Following commented out for ref:20131223-16
				/*	
				if ($homepage->stylesheet != '' && $STYLESHEET != 'generic/handheld.css') {
					$STYLESHEET = $homepage->stylesheet;
				}
				*/
			}
		}
		
		//	Document Links
		$allDocuments = getAllDocumentsWithCategory($_GET['categoryID'], true, true, 'title');
		$liveDocs = array();
		
		foreach ($allDocuments as $document) {
			$header = new Versions(DOCUMENT_HEADERS_TABLE, $document->headerOriginalID, VERSIONED_DOCUMENTS_TABLE);
			if ($header->liveVersion != -1) {
				$liveDocs[$document->id] = $header->getLiveVersion();
			}
		}
		
		// Category Links
		$bespokeCategoryList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		$allCategories = $bespokeCategoryList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, DOCUMENTS_APPLIED_CATEGORIES_TABLE, true);
		$splitArray = splitArray($categories);

		// Category Links
		$currentCategory = $bespokeCategoryList->getCategory($_GET['categoryID']);
		$dirTree = $bespokeCategoryList->getFullPath($_GET['categoryID']);
		$leftCategoryID = $_GET['categoryID'];
		if (!$showHomepageContent && empty($allDocuments) && empty($categories)) {
			header("HTTP/1.0 404 Not Found");
		}
	}
	else {
		header("HTTP/1.0 404 Not Found");
	}
	
	// Breadcrumb, H1 and Title
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li>';
	$levelNo = 1;
	$count = 0;
	foreach ($dirTree as $parent) {
		if ($count < sizeof($dirTree) - 1) {
			$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildDocumentsCategoryURL($parent->id).'" >'. encodeHtml($parent->name) .'</a></li>';
		}
		else {
			$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($parent->name) .'</span></li>';
			$MAST_HEADING = $parent->name;
		}
		$count++;
		$levelNo++;
	}
	
	include("documents.html.php");
?>
