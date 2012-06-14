<?php
	include_once("egov/JaduCL.php");
	include_once("JaduAppliedCategories.php");
	include_once("utilities/JaduNavWidgets.php");
	include_once("websections/JaduPageSupplements.php");
	include_once("websections/JaduPageSupplementWidgets.php");
	include_once("websections/JaduPageSupplementWidgetPublicCode.php");
	include_once("websections/JaduDocuments.php");
	
	/**
	 * Render any sub-category navigation
	 * 
	 * @param integer $categoryID
	 * @param array[]integer $parents Array of parent {@link CategoryItem} id values
	 * @param integer $depth Used to identify the navigation depth and indent elements correctly
	 * @return boolean False if nothing to render
	 */
	function renderNavChildren($categoryID, $parents, $depth = 0) {
		global $lgclList;
		
		if (!in_array($categoryID, $parents)) {
			return false;
		}
		
		// Get any live documents 
		$allDocuments = getAllDocumentsWithCategory($categoryID, true, true, 'header.title');
		$liveDocs = array();
		foreach ($allDocuments as &$document) {
			$header = new Versions(DOCUMENT_HEADERS_TABLE, $document->headerOriginalID, VERSIONED_DOCUMENTS_TABLE);
			if ($header->liveVersion != -1) {
				$liveDocs[$document->id] = $header->getLiveVersion();
			}
		}
		
		$allChildren = $lgclList->getChildCategories($categoryID);
		$children = array();
		if (empty($liveDocs) && empty($allChildren)) {
			return false;
		}
		else if (!empty($allChildren)) {
			$children = filterCategoriesInUseFromMultipleTables($allChildren, array(DOCUMENTS_APPLIED_CATEGORIES_TABLE, HOMEPAGE_APPLIED_CATEGORIES_TABLE), true);
		}
		
		if (empty($children) && empty($liveDocs)) {
			return false;
		}
		
		$tabDepth = '';
		if ($depth > 0) {
			for ($i = 0; $i < $depth; $i++) {
				$tabDepth .= '		';
			}
		}
		++$depth;
?>
				<?php print $tabDepth; ?><ul>
<?php
		foreach ($liveDocs as $documentID => &$documentHeader) {
?>
					<?php print $tabDepth; ?><li class="document"><a href="<?php print getSiteRootURL() . buildDocumentsURL($documentID, $categoryID); ?>"><?php print encodeHtml($documentHeader->title); ?></a></li>
<?php
		}
		
		foreach ($children as &$child) {
			$selected = false;
			if (in_array($child->id, $parents)) {
				$selected = true;
			}
			
			$subMenu = '';
			if (isset($_GET['categoryID']) && $_GET['categoryID'] == $child->id) {
				$subMenu = ' sub-menu';
			}
?>
					<?php print $tabDepth; ?><li<?php print $selected ? ' class="selected'. $subMenu . '"' : ''; ?>>
						<?php print $tabDepth; ?><a href="<?php print getSiteRootURL() . buildDocumentsCategoryURL($child->id); ?>"<?php print $selected ? ' class="selected"' : ''; ?>><?php print encodeHtml($child->name); ?></a>
<?php
			if ($selected) {
				renderNavChildren($child->id, $parents, $depth);
			}
?>
					<?php print $tabDepth; ?></li>
<?php
		}
?>
				<?php print $tabDepth; ?></ul>
<?php
	}
	
	if (isset($_GET['homepageID']) || mb_strpos($_SERVER['REQUEST_URI'], '/site/index.php') !== false) {
		include_once('websections/JaduHomepages.php');
		if (isset($_GET['homepageID'])) {
			$homepage = getHomepage($_GET['homepageID']);
		}
		else {
			$allIndependantHomepages = getAllHomepagesIndependant();
			if (count($allIndependantHomepages) > 0) {
				$homepage = getHomepage($allIndependantHomepages[0]->id);
			}
		}
		if (isset($homepage) && $homepage->hideTaxonomy == '1') {
			return;
		}
	}
	
	if (!isset($allWidgets)) {
		$allWidgets = getAllNavWidgets();
	}
	
	$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
	$allRootCategories = $lgclList->getTopLevelCategories();
	$columnRootCategories = filterCategoriesInUseFromMultipleTables($allRootCategories, array(DOCUMENTS_APPLIED_CATEGORIES_TABLE, HOMEPAGE_APPLIED_CATEGORIES_TABLE), true);
	
	$parents = array();
	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
		$categoryID = (int) $_GET['categoryID'];
		
		if (!isset($dirTree) || !is_array($dirTree)) {
			$dirTree = $lgclList->getFullPath($categoryID);
		}
		
		foreach($dirTree as &$parent) {
			if (!is_object($parent) || get_class($parent) != 'CategoryItem') {
				continue;
			}
			$parents[] = $parent->id;
		}
	}
?>

<!-- googleoff: index -->

		


<div class="side-nav">
	<div class="clear"></div>
<?php
	if (isset($indexPage) || $indexPage) {
?>
	
<?php
	}
	else {
?>
<?php
	if (isset($allWidgets[0])) {
		$allLinks = getAllNavWidgetLinksInNavWidget($allWidgets[0]->id);
?>
	<div id="top-tasks">
	<h3 class="red"><a href="#top-tasks" class="expand
	
<?php
	if (!isset($indexPage) || !$indexPage) {
?>
	
<?php
	}
	else {
?>
		down

<?php
	}
?>
	
	">
	
	<?php print encodeHtml($allWidgets[0]->title); ?></a></h3>
		<ul class="tasks">
<?php
			foreach ($allLinks as &$widgetLink) {
?>
			<li><a href="<?php print encodeHtml($widgetLink->link); ?>"><?php print encodeHtml($widgetLink->title); ?></a></li>
<?php
			}
?>
		</ul>
	</div>
<?php
	}

	}
?>
	<div id="service-list">
	<h3 class="red"><a href="<?php print getSiteRootURL(); ?>/info">Services</a></h3>
		<ul>
<?php
	foreach ($columnRootCategories as &$columnRootCategory) {
?>
			<li<?php print (in_array($columnRootCategory->id, $parents)) ? ' class="selected"' : ''; ?>>
				<a href="<?php print getSiteRootURL() . buildDocumentsCategoryURL($columnRootCategory->id); ?>"<?php print (in_array($columnRootCategory->id, $parents)) ? ' class="selected"' : ''; ?>><?php print encodeHtml($columnRootCategory->name); ?></a>
<?php
		if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
			renderNavChildren($columnRootCategory->id, $parents);
		}
?>
			</li>
<?php
		}
		// Include any additional links from the second nav widget
		if (isset($allWidgets[1])) {
			$allLinks = getAllNavWidgetLinksInNavWidget($allWidgets[1]->id);
			foreach ($allLinks as &$widgetLink) {
?>
			<li class="additional">
				<a href="<?php print encodeHtml($widgetLink->link); ?>"><?php print encodeHtml($widgetLink->title); ?></a>
			</li>
<?php
			}
		}
?>
		</ul>
		
		
	</div>

</div>
		<!-- Left-hand Supplements -->
		<div class="leftSupplements">
<?php
		$showLeftSupplements = false;
		
		if (($_SERVER['SCRIPT_NAME'] == '/site/scripts/faq_info.php' || 
			$_SERVER['SCRIPT_NAME'] == '/site/scripts/faqs_index.php' || 
			$_SERVER['SCRIPT_NAME'] == '/site/scripts/faqs.php') && 
			isset($faq) && $faq->id > 0) {
			$showLeftSupplements = true;
			$contentType = 'faq';
			$itemID = $faq->id;
		}
		else if (($_SERVER['SCRIPT_NAME'] == '/site/scripts/documents_info.php' || $_SERVER['SCRIPT_NAME'] == '/preview/documents_info.php') &&
			isset($page) && $page->id > 0) {
			$showLeftSupplements = true;
			$contentType = 'document';
			$itemID = $page->id;
		}
		else if (($_SERVER['SCRIPT_NAME'] == '/site/scripts/home_info.php' || 
			$_SERVER['SCRIPT_NAME'] == '/site/scripts/documents.php' || 
			$_SERVER['SCRIPT_NAME'] == '/site/index.php') &&
			isset($homepage) && $homepage->id > 0) {
			$showLeftSupplements = true;
			$contentType = 'homepage';
			$itemID = $homepage->id;
		}
		
		if ($showLeftSupplements) {
			$leftSupplements = getAllPageSupplements(array(
				'contentType' => $contentType, 
				'itemID' => $itemID, 
				'locationOnPage' => 'left'
			), 'position ASC');
			
			foreach ($leftSupplements as $supplement) {
				$record = $supplement->getRecord();
				if ($record->id > 0) {
					$publicCode = getSupplementPublicCode($supplement->supplementWidgetID, $supplement->locationOnPage);
					include(HOME . '/site/includes/supplements/' . $publicCode->code);
				}
			}
			
			unset($record);
			unset($publicCode);
		}
?>
		</div>
		<!-- End left-hand supplements -->

<!-- googleon: index -->
