<?php
    require_once 'utilities/JaduStatus.php';
    require_once 'JaduCategories.php';
    require_once 'JaduMetadata.php';
    require_once 'websections/JaduDocuments.php';
    require_once 'websections/JaduDocumentsCategoryDefaults.php';
    require_once 'websections/JaduDocumentPasswords.php';
    require_once 'websections/JaduDocumentPageStructures.php';
    require_once 'egov/JaduCL.php';
    require_once 'JaduStyles.php';
	require_once 'utilities/JaduMostPopular.php';

    $dirTree = array();
    $pageNumber = 1;
    $showDocument = true;
    $accessDenied = false;
    $previewAllowed = false;

    if (isset($_GET['pageNumber'])) {
        $pageNumber = (int) $_GET['pageNumber'];
    }

    if (!isset($_GET['documentID']) || $_GET['documentID'] < 1) {
        header('Location: documents_index.php');
        exit;
    }

	// Check whether an administrator is previewing the page
    if (isset($isPreviewLink) && $isPreviewLink && isset($allowPreview) && $allowPreview) {
        require_once 'utilities/JaduModules.php';
        require_once 'utilities/JaduModulePages.php';
        require_once 'utilities/JaduAdminPageAccess.php';

        $modulePagePermissions = getModulePageFromURL('/websections/websection_subsection_details.php');
        if ($modulePagePermissions->parent_id > -1) {
            $adminID = Jadu_Service_Container::getInstance()->getCurrentJaduSessionAdmin()->id;
            $modulePageParent = getModulePage($modulePagePermissions->parent_id);
            $adminPageAccessPermissions = getAdminPageAccess($adminID, $modulePageParent->id);
        }
        else {
            $adminPageAccessPermissions = getAdminPageAccess($adminID, $modulePagePermissions->id);
        }
        if ($adminPageAccessPermissions->updateContent) {
            $previewAllowed = true;
        }
    }
    
    if (!$previewAllowed) {
		header('Location: ' . SECURE_JADU_PATH);
		exit;
	}
	
	// Get the live document
	$document = getDocument($_GET['documentID'], false);
	if ($document->id > 0) {
		$header = getDocumentHeader($document->headerOriginalID);
		$allPages = getAllDocumentPagesForDocument($document->id);
		$page = $allPages[$pageNumber-1];
		
		// Add the edit-in-place toolbar
		addJavascript(SECURE_JADU_PATH . '/javascript/prototype.js');
		addJavascript(SECURE_JADU_PATH . '/javascript/preview_edit.js');
		
		$page->description = '<div id="editable">' . $page->description . '</div>';

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

    // Breadcrumb, H1 and Title
    $MAST_HEADING = $header->title;
    $MAST_BREADCRUMB = '
    			<li>
                    <a href="' . getSiteRootURL() . '" rel="home">Home</a>
                </li>';
    foreach ($dirTree as $parent) {
        $MAST_BREADCRUMB .= '
                <li>
                    <a href="' . getSiteRootURL() . buildDocumentsCategoryURL($parent->id) . '">' . encodeHtml($parent->name) . '</a>
                </li>';
    }
    $MAST_BREADCRUMB .= '
                <li>
                    <span>' . encodeHtml($header->title) . '</span>
                </li>';

    require_once 'documents_info.html.php';
