<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="forms, form, application, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Online forms" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Online forms" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Online forms" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

	<!-- live search code -->
	<script type="text/javascript" src="<?php print getStaticContentRootURL(); ?>/site/javascript/livesearch.js"></script>
	<script type="text/javascript" src="<?php print getStaticContentRootURL(); ?>/site/javascript/xforms_live_search.js"></script>	
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php 
    if (!Jadu_Service_User::getInstance()->isSessionLoggedIn()){
?>
	
<?php 
	} 
		
	if (sizeof($topForms) > 0) { 
?>
	<h2>Frequently used online forms</h2>
	<ul class="list icons forms">
<?php 
		foreach($topForms as $topForm) {
?>
		<li>
			<a href="<?php print getSiteRootURL() . buildXFormsURL($topForm->id) ;?>"><?php print encodeHtml($topForm->title); ?></a> 
<?php 
			if(!Jadu_Service_User::getInstance()->isSessionLoggedIn() && $topForm->allowUnregistered == 0) { 
?>
			<img src="<?php print getStaticContentRootURL(); ?>/site/images/icon_lock.gif" alt="Padlock graphic" />
<?php 
			} 
?>
		</li>
<?php 
		} 
?> 
	</ul> 
<?php 
	} 
?>


<?php
	foreach ($rootCategories as $rootCat) {	
		$relCats = filterCategoriesInUse($bespokeCategoryList->getChildCategories($rootCat->id), XFORMS_FORM_APPLIED_CATEGORIES_TABLE, true, BESPOKE_CATEGORY_LIST_NAME);
?>
		<div class="cate_info">
			<h3><a href="<?php print getSiteRootURL() . buildFormsCategoryURL($rootCat->id); ?>"><?php print encodeHtml($rootCat->name); ?></a></h3>
<?php
			if (sizeof($relCats) > 0) {
				print '<ul class="list icons forms">';
				foreach ($relCats as $subCat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildFormsCategoryURL($subCat->id); ?>"><?php print encodeHtml($subCat->name); ?></a></li>
<?php
				}
				print '</ul>';
			}
?>
		</div>
<?php 
		} 
?>

	<!-- Live search -->
	<form action="<?php print getSiteRootURL(); ?>/site/scripts/xforms_index.php" method="get">
	<h3>Search for an online form</h3>
		<p id="az_live_find">
			<label for="xforms_searchText">Begin to type the form name and choose from the appearing choices.</label>
			<span>
				<input type="text" name="xforms_searchText" id="xforms_searchText" class="long" value="" />
				<img id="xforms_loading" style="display:none;" alt="Loading" src="<?php print getStaticContentRootURL(); ?>/site/images/loading.gif" />
			</span>
			<noscript><input name="searchXformButton" type="submit" value="Go" class="button" /></noscript>
		</p>			
		<div class="topten" id="xforms_search_results">
<?php 
	if(isset($_GET['searchXformButton'])) {
		include(HOME . 'site/includes/xforms_search_results.php' ); 
	}
?>						
		</div>
	</form>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>