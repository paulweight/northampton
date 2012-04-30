<?php
	include_once("egov/JaduCL.php");
	include_once("JaduAppliedCategories.php");
	include_once("utilities/JaduNavWidgets.php");
	include_once("websections/JaduPageSupplements.php");
	include_once("websections/JaduPageSupplementWidgets.php");
	include_once("websections/JaduPageSupplementWidgetPublicCode.php");
	
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

	$allWidgets = getAllNavWidgets();
	$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
	$allRootCategories = $lgclList->getTopLevelCategories();

	$columnRootCategories = filterCategoriesInUseFromMultipleTables($allRootCategories, array(DOCUMENTS_APPLIED_CATEGORIES_TABLE, HOMEPAGE_APPLIED_CATEGORIES_TABLE), true);
?>
<!-- googleoff: index -->
<div id="columnPrimary">	

	<div class="navWidget">
	<h2>Categories</h2>
	<ul>
<?php
	foreach ($columnRootCategories as $category) {
?>
		<li><a href="<?php print getSiteRootURL(); print buildDocumentsCategoryURL($category->id);?>"><?php print encodeHtml($category->name); ?></a></li>
<?php
	}
?>
	</ul>    
	</div>

<?php
		if (sizeof($allWidgets) > 0) {
			foreach ($allWidgets as $widget) {
				$allLinks = getAllNavWidgetLinksInNavWidget ($widget->id);
?>
	<div class="navWidget">
		<h2><?php print encodeHtml($widget->title); ?></h2>
		<ul>
<?php
			foreach ($allLinks as $widgetLink) {
				print '<li><a href="' . encodeHtml($widgetLink->link) . '">' . encodeHtml($widgetLink->title) . '</a></li>';
			}
?>
		</ul>
	</div>
<?php
			}
		}
?>
		<!-- Left-hand Supplements -->
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
		<!-- End left-hand supplements -->
</div>
<!-- googleon: index -->
