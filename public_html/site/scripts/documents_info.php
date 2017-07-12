<?php
	include_once("utilities/JaduStatus.php");
	
	include_once("JaduCategories.php");
	include_once("JaduMetadata.php");
	include_once("websections/JaduDocuments.php");
	include_once("websections/JaduDocumentsCategoryDefaults.php");
	include_once("websections/JaduDocumentPasswords.php");	
	include_once("websections/JaduDocumentPageStructures.php");
	include_once("egov/JaduCL.php");

	$dirTree = array();
	$pageNumber = 1;
	$showDocument = true;
	$accessDenied = false;
	
	if (isset($_GET['pageNumber'])) {
		$pageNumber = (int) $_GET['pageNumber'];
	}
	
	if (!isset($_GET['documentID']) || $_GET['documentID'] < 1) {
		header('Location: documents_index.php');
		exit;
	}
	
	// Get the live document
	$document = getDocument($_GET['documentID'], true);
	if ($document->id > 0) {
		// Get the live version
		$document = getDocumentLiveVersion($document->id);
		$header = getDocumentHeaderLiveVersion($document->headerOriginalID);
		
		if (!isset($_SESSION['authenticaedDocumentIDs'])) {
			$_SESSION['authenticaedDocumentIDs'] = array();
		}

		if ($header->passwordId > 0 && !in_array($document->id, $_SESSION['authenticaedDocumentIDs'])) {
			if (isset($_POST['password'])) {
				$showDocument = checkDocumentPassword($document->id, $_POST['password']);
				if ($showDocument) {
				    $_SESSION['authenticaedDocumentIDs'][] = $document->id;
				}
			}
			else {
				$showDocument = false;
			}
		}

		if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
			// Check whether the user has the correct access level to view the document
			$user = Jadu_Service_User::getInstance()->getSessionUser();

			if ($user->accessLevel < $header->accessLevel) {
				$showDocument = false;
				$accessDenied = true;
			}
		}
		else if ($header->accessLevel > 1) {
			$showDocument = false;
			$accessDenied = true;
		}
		
		$allPages = getDocumentPagesLiveVersions(explode(',', $document->pageOriginalIDs));
		if (isset($allPages[$pageNumber-1])) {
		    $page = $allPages[$pageNumber-1];

			$pageStructure = getPageStructure($page->pageStructureID);
			if ($page->pageStructureID == -1 || $page->pageStructureID == '') {
				$pageStructure = getPageStructure($header->pageStructureID);
			}

			// In case don't follow hierarchy but find document from search etc.
			if (!isset($_GET['categoryID']) || $_GET['categoryID'] == -1) {
				$categoryID = getFirstCategoryIDForItemOfType(DOCUMENTS_CATEGORIES_TABLE, $document->id, BESPOKE_CATEGORY_LIST_NAME);
				$_GET['categoryID'] = $categoryID;
			}
			else {
				$categoryID = (int) $_GET['categoryID'];
				// Check document is actually assigned to the category if it supplied by query string
				if (!isCategoryAssignedToItem(DOCUMENTS_CATEGORIES_TABLE, $categoryID, $document->id)) {
					//FB26377. Allow for cases where the URL is an "old" category that the document used to belong to.
					$categoryID = getFirstCategoryIDForItemOfType(DOCUMENTS_CATEGORIES_TABLE, $document->id, BESPOKE_CATEGORY_LIST_NAME);
					$categoryRedirect = buildDocumentsURL($document->id, $categoryID);
					header( "HTTP/1.1 301 Moved Permanently" ); 
					header( "Location: " . $categoryRedirect);
					exit();
				}
			}

			// Category Links
			$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
			$currentCategory = $lgclList->getCategory($categoryID);
			$dirTree = $lgclList->getFullPath($categoryID);
		}
		else {
			// Don't display the document when the page number is invalid
			$document = new Document();
		}
	}

	if ($showDocument && $document->id == -1) {
		header("HTTP/1.0 404 Not Found");
		include '../../404.php';
		exit;
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = $header->title;
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li>';
	foreach ($dirTree as $parent) {
		$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildDocumentsCategoryURL($parent->id) . '" >'. encodeHtml($parent->name) .'</a></li>';
	}
	$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($header->title) .'</span></li>';
	
	
	include_once("JaduStyles.php");
	include("documents_info.html.php");
