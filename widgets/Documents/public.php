<?php
	include_once('websections/JaduDocuments.php');
	include_once('websections/JaduDocumentsCategoryDefaults.php');
      
	$widgetShowDefaultContent = false;
	$widgetCategoryID = '';
	
	if (isset($_GET['categoryID'])) {
		$widgetCategoryID = $_GET['categoryID'];
	}
	
	if ('%CATEGORIES%' != '%'.'CATEGORIES%') {
		$widgetCategoryID = '%CATEGORIES%';
	}
      
	if ($widgetCategoryID != '' && $widgetCategoryID > 0) {

		// Category Links
		$widgetCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		$widgetAllCategories = $widgetCategoryList->getChildCategories($widgetCategoryID);
		$widgetParent = $widgetCategoryList->getCategory($widgetCategoryID);

		// Document Links
		$widgetAllDocuments = getAllDocumentsWithCategory($widgetCategoryID, true, true);
	

	// IF THE PAGE HAS NO DEFAULT CONTENT OR DOCUMENTS then second level LGCL will be displayed in the area below              
?>
<div class="documentListWidget">
	<h2>Information in <?php print encodeHtml($widgetParent->name); ?></h2>
<?php
		if (sizeof($widgetAllDocuments) > 0) {
?>
	<ul class="list icons documents">
<?php
			foreach ($widgetAllDocuments as $widgetDocument) {                                                 
				$widgetDocumentHeader = getDocumentHeader($widgetDocument->headerOriginalID, true);
?>
		<li class="long"><a href="<?php print getSiteRootURL() . buildDocumentsURL($widgetDocument->id, $widgetCategoryID); ?>"><?php print encodeHtml($widgetDocumentHeader->title); ?></a></li>
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