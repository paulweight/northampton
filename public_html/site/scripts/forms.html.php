<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print encodeHtml($parent->name) . ', '; } ?><?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>'s online forms organised within the following categories<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> online forms<?php foreach ($dirTree as $parent) { ?> | <?php print encodeHtml($parent->name); ?><?php } ?>" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>'s online forms organised within the following categories<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
		
<?php
	if (sizeof($allXForms) > 0) {
?>
	<h2>Available online forms</h2>
	<ul>
<?php
		foreach ($allXForms as $formItem) {
?>
		<li><a href="<?php print getSecureSiteRootURL() . buildXFormsURL($formItem->id);?>"><?php print encodeHtml($formItem->title); ?></a></li>
<?php
		}
?>
	</ul>
	
<?php
	}

	if (sizeof($categories) > 0) {
?>
		<div class="cate_info">
			<h2><?php print encodeHtml($parent->name); ?> categories</h2>
<?php
		if (sizeof($categories) > 0) {
			print '<ul class="list">';
			foreach ($categories as $subCat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildFormsCategoryURL($subCat->id); ?>"><?php print encodeHtml($subCat->name); ?></a> </li>
<?php
			}
			print '</ul>';
		}
?>
		</div>
<?php
	}
?>

	<p><a href="<?php print getSiteRootURL() . buildCategoryRSSURL("forms", $_GET['categoryID']); ?>" target="_blank"><img src="<?php print getStaticContentRootURL(); ?>/site/images/xml.gif" alt=" " /> <?php print encodeHtml(METADATA_GENERIC_NAME . ' ' . $currentCategory->name); ?>  feed</a></p>
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>