<?php
	include_once("utilities/JaduStatus.php");
	
	include_once("JaduCategories.php");
	include_once("JaduMetadata.php");
	include_once("websections/JaduDocuments.php");
	include_once("websections/JaduDocumentsCategoryDefaults.php");
	include_once("websections/JaduDocumentPasswords.php");	
	include_once("websections/JaduDocumentPageStructures.php");
	include_once("utilities/JaduMostPopular.php");
	include_once("egov/JaduCL.php");

	$dirTree = array();
	$pageNumber = 1;
	$showDocument = true;
	$accessDenied = false;
	$previewAllowed = false;
	
	if (isset($_GET['pageNumber']) && $_GET['pageNumber'] > 0) {
		$pageNumber = (int) $_GET['pageNumber'];
	}
	
	if (!isset($_GET['documentID']) || $_GET['documentID'] < 1) {
		header("Location: documents_index.php");
		exit();
	}
	
	// Check whether an administrator is previewing the page
	if (isset($_GET['adminID']) && isset($_GET['preview']) && isset($_GET['expire'])) {
		require_once("utilities/JaduModules.php");
		require_once("utilities/JaduModulePages.php");
		require_once("utilities/JaduAdminPageAccess.php");
		require_once('utilities/JaduAdministrators.php');
		
		if (validateAdminPreviewHash(getAdministrator($_GET['adminID']), $_GET['preview'], $_GET['expire'])) {
			$modulePagePermissions = getModulePageFromURL('/websections/websection_subsection_details.php');
			if ($modulePagePermissions->parent_id > -1) {
				$modulePageParent = getModulePage($modulePagePermissions->parent_id);
				$adminPageAccessPermissions = getAdminPageAccess($_GET['adminID'], $modulePageParent->id);
			}
			else {
				$adminPageAccessPermissions = getAdminPageAccess($_GET['adminID'], $modulePagePermissions->id);
			}

			if ($adminPageAccessPermissions->updateContent) {
				$previewAllowed = true;
			}
		}
	}
	
	if (!$previewAllowed) {
		header('Location: ' . SECURE_JADU_PATH);
		exit();
	}
	
	// Get the live document
	$document = getDocument($_GET['documentID'], false);
	if ($document->id > 0) {
		$header = getDocumentHeader($document->headerOriginalID);
		$allPages = getAllDocumentPagesForDocument($document->id);
		$page = $allPages[$pageNumber-1];
		
		// Add the edit-in-place toolbar
		addCSS(getStaticContentRootURL() . '/site/styles/generic/editor.css');
		addJavascript(SECURE_JADU_PATH . '/javascript/javascript_constants.php');
		addJavascript(SECURE_JADU_PATH . '/javascript/prototype.js');
		addJavascript(SECURE_JADU_PATH . '/javascript/preview_edit.js');
		
		$page->description = '<div id="editable">' . $page->description . '</div><script type="text/javascript">PreviewEdit.init($(\'editable\'), "documents", "' . $page->id . '", "' . $_GET['adminID'] . '", "' . $_GET['preview'] . '", "' . $_GET['expire'] . '");</script>';

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
				$document->id = -1;
			}
		}

		// Category Links
		$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		$currentCategory = $lgclList->getCategory($categoryID);
		$dirTree = $lgclList->getFullPath($categoryID);
	}

	if ($showDocument && $document->id == -1) {
		header("HTTP/1.0 404 Not Found");
	}

	$breadcrumb = 'documentsInfo';
	include_once("JaduStyles.php");
	
	include('documents_info.html.php');
?>