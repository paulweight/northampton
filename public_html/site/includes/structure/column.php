<?php
	include_once("egov/JaduCL.php");
	include_once("JaduAppliedCategories.php");
	include_once("utilities/JaduNavWidgets.php");
	include_once("websections/JaduPageSupplements.php");
	include_once("websections/JaduPageSupplementWidgets.php");
	include_once("websections/JaduPageSupplementWidgetPublicCode.php");

	$allWidgets = getAllNavWidgets();        

	$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$allRootCategories = $lgclList->getTopLevelCategories();
	$rootDocumentCategories = filterCategoriesInUse($allRootCategories, DOCUMENTS_APPLIED_CATEGORIES_TABLE, true);
	$rootHomepageCategories = filterCategoriesInUse($allRootCategories, HOMEPAGE_APPLIED_CATEGORIES_TABLE, true);

	$categoriesUsed = array();
	$rootCategories = array();

	foreach ($rootDocumentCategories as $index => $item) {
			$categoriesUsed[] = $item->id;
			$rootCategories[] = $item;
	}
	foreach ($rootHomepageCategories as $index => $item) {
			if (!in_array($item->id, $categoriesUsed)) {
					$categoriesUsed[] = $item->id;
					$rootCategories[] = $item;
			}
	}
	ksort($rootCategories);
	
	$currentID = $dirTree[0]->id;
	$bespokeCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$allSecondLevelCategories = $bespokeCategoryList->getChildCategories($currentID);
	$secondLevelCategories = filterCategoriesInUse($allSecondLevelCategories, DOCUMENTS_APPLIED_CATEGORIES_TABLE, true);	
?>
<div id="column_nav">

<!-- ################ First nav widget -->
<?php
	if (sizeof($allWidgets) > 0) {
		$i = 0;
		foreach ($allWidgets as $widget) {
			if ($i == 0) {
				$allLinks = getAllNavWidgetLinksInNavWidget ($widget->id);
?>
	<h2><?php print htmlentities($widget->title);?></h2>
	<ul class="navWidget">
<?php
	foreach ($allLinks as $widgetLink) {
		print '<li><a href="' . htmlentities($widgetLink->link) . '">' . htmlentities($widgetLink->title) . '</a></li>';
	}
?>
	</ul>
<?php
			}
			$i++;
		}
	}
?>


<!-- ################ LGNL Select -->
	<form action="http://<?php print $DOMAIN;?>/site/scripts/documents.php" method="get" name="catNav">
		<h3>Council information on...</h3>
		<select class="select" name="categoryID" onchange="submitform('catNav')">
			<option>Select a category</option>
<?php
foreach ($rootCategories as $category) {
?>
			<option value="<?php print $category->id;?>" <?php if ($currentID == $category->id) { print 'selected="selected"'; } ?>><?php print $category->name; ?></option>
<?php
}
?>
		</select>
		<noscript><input type="submit" value="Go" class="button" id="go"/></noscript>
	</form>

<!-- ################ Rest of widgets -->
<?php
	if (sizeof($allWidgets) > 0) {
		$i = 0;
		foreach ($allWidgets as $widget) {
			if ($i > 0) {
			$allLinks = getAllNavWidgetLinksInNavWidget ($widget->id);
?>
	<h2><?php print htmlentities($widget->title);?></h2>
	<ul class="navWidget"><?php
		foreach ($allLinks as $widgetLink) {
			print '<li><a href="' . htmlentities($widgetLink->link) . '">' . htmlentities($widgetLink->title) . '</a></li>';
		}
?></ul>
<?php
			}
			$i++;
		}
	}
?>


<?php
		
	//Left-hand Supplements -->
		// get left-hand supplements 
		if (isset($page->id) || isset($homepage->id)) {
			if (isset($page->id)) {
				$leftSupplements = getAllPageSupplements('', $page->id, '', 'Left');
			}
			elseif (isset($homepage->id)) {
				$leftSupplements = getAllPageSupplements('', '', $homepage->id, 'Left');
			}

			// loop through each supplement
			foreach ($leftSupplements as $supplement) {
				// include supplement front-end code
				$publicCode = getSupplementPublicCode($supplement->supplementWidgetID, $supplement->locationOnPage);
				$supplementWidget = getPageSupplementWidget($supplement->supplementWidgetID);

				include_once($supplementWidget->classFile);

				$record = new $supplementWidget->className;
				$record->id = $supplement->supplementRecordID;
				$record->get();
				include($HOME . '/site/includes/supplements/' . $publicCode->code);
			}
		}
?>
	<!-- End left-hand supplements -->

<div id="google_translate_element"></div><script>
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'en'
  }, 'google_translate_element');
}
</script><script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</div>
