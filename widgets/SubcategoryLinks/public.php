<?php
	include_once('JaduCategories.php');
	include_once('egov/JaduCL.php');

	if ('%CATEGORIES%' != '' && '%CATEGORIES%' != '%CATEGORIES'.'%') {
	
		$widgetCategoryID = '%CATEGORIES%';
		
		$widgetLeftCats = array();
		$widgetRightCats = array();
	
		// Category Links
		$widgetCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		$widgetAllCategories = $widgetCategoryList->getChildCategories($widgetCategoryID);
		$widgetParent = $widgetCategoryList->getCategory($widgetCategoryID);
	
		$widgetCategories = filterCategoriesInUse($widgetAllCategories, DOCUMENTS_APPLIED_CATEGORIES_TABLE, true);	
?>
		<div class="cateInfoWidget">
			<h2>Categories in <?php print encodeHtml($widgetParent->name); ?></h2>
<?php
		if (count($widgetCategories) > 0) {
?>
			<ul class="list icons generic">
<?php
			foreach ($widgetCategories as $widgetSubCat) {
?>
				<li class="long"><a title="<?php print encodeHtml($widgetSubCat->name); ?>" href="<?php print getSiteRootURL() . buildDocumentsCategoryURL($widgetSubCat->id); ?>"><?php print encodeHtml($widgetSubCat->name); ?></a></li>
<?php
			}
?>
			</ul>
<?php
		}
?>		
		</div>
<?php
	}
?>