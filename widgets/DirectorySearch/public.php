<?php
	include_once('JaduConstants.php');
	include_once('directoryBuilder/JaduDirectories.php');
	include_once('directoryBuilder/JaduDirectoryCategoryTree.php');
	include_once('directoryBuilder/JaduDirectoryEntries.php');
	include_once('egov/JaduCL.php');
	include_once('library/JaduStringFunctions.php');
	
	$bespokeCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$rootCategory = $bespokeCategoryList->getCategoryByName('Care Centres');
	$secondLevelCategories = $bespokeCategoryList->getChildCategories($rootCategory->id);
	
	$categories = array();
	foreach($secondLevelCategories as $category) {
		$thirdLevelCategory = $bespokeCategoryList->getChildCategories($category->id);
		
		foreach($thirdLevelCategory as $cat) {
			$categories[] = $cat->name;
		}
	}
	sort($categories);

	$directoryWidgetTitle = '%TITLE%';
	$directoryWidgetContent = '%DESCRIPTION%';
	$directoryID = '%DIRECTORYID%';

?>

	<div class="directoryWidget">

<?php
	if ($directoryWidgetTitle != '' && $directoryWidgetTitle != '%TITLE'.'%') {
?>
		<h2><?php print encodeHtml($directoryWidgetTitle); ?></h2>
<?php
	}
	if ($directoryWidgetContent != '' && $directoryWidgetContent != '%DESCRIPTION'.'%') {
?>
		<p><?php print encodeHtml($directoryWidgetContent); ?></p>

<?php
	}
?>
		
		<form method="get" action="http://<?php print DOMAIN; ?>/site/scripts/directory_search.php">
			<input type="hidden" value="<?php print $directoryID; ?>" name="directoryID" />
			<input type="hidden" value="Search" name="search" />
			
			<input type="text" class="field" value="Search by keyword..." onclick="this.value = '';" name="keywords" />
			<input type="submit" class="button SorFbutt" name="submit" value="Search" />
		</form>

		<form method="get" action="http://<?php print DOMAIN; ?>/site/scripts/directory_search.php">
			<input type="hidden" value="<?php print $directoryID; ?>" name="directoryID" />
			<input type="hidden" value="Search" name="search" />
			
			<input type="text" class="field" value="Enter your postcode..." onclick="this.value = '';" name="postcode" />
			<input type="submit" class="button SorFbutt" name="submit" value="Find" />
		</form>

		<a href="http://<?php print DOMAIN; ?>/site/scripts/directory_search.php?directoryID=<?php print $directoryID; ?>">Advanced Search</a>
	</div>