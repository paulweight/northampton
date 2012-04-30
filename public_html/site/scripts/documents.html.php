<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	
	<link rel="stylesheet" type="text/css" href="<?php print getStaticContentRootURL(); ?>/site/styles/generic/homepages.css" media="screen" />
<?php 
	
	include_once("../includes/stylesheets.php");
	include_once("../includes/metadata.php");
	
	$metadata = getMetadataForItem (HOMEPAGES_METADATA_TABLE, $homepage->id);
	if ($metadata->subject == '') {
		$metadata->subject = METADATA_GENERIC_KEYWORDS;
	}
	if ($metadata->description == '') {
		$metadata->description = $homepage->title . ' - ' . $homepage->description;
	}
?>

	<meta name="Keywords" content="<?php print encodeHtml($metadata->subject); ?>" />
	<meta name="Description" content="<?php print encodeHtml($metadata->description); ?>" />
	
	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> online information<?php foreach ($dirTree as $parent) { ?> | <?php print encodeHtml($parent->name); ?><?php } ?>" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>s index of documents and pages organised within the following categories<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy;<?php foreach ($dirTree as $parent) { print encodeHtml($parent->name) . ';'; } ?>" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include( $HOME . "site/includes/opening.php"); ?>
<!-- ########################## -->
<?php
	if (!$showHomepageContent && empty($allDocuments) && empty($categories)) {
		print '<h2>Sorry, this page is no longer available</h2>';
	}
	else if ($showHomepageContent) {
		include('../includes/homepages_content.php');
	}
?>
		<!-- END category homepage content -->
		
		<!-- Bottom Supplements -->
<?php include('../includes/bottom_supplements.php'); ?>
		<!-- End bottom supplements -->
<?php
	if (sizeof($liveDocs) > 0) {
?>

	<h2>Information on <?php print encodeHtml($currentCategory->name); ?></h2>

<?php
		if (sizeof($liveDocs) > 0) {
			print '<ul class="list">';
			foreach ($liveDocs as $documentID => $header) {
?>
		<li><a href="<?php print getSiteRootURL() . buildDocumentsURL($documentID, $_GET['categoryID']);?>"><?php print encodeHtml($header->title); ?></a></li>
<?php
			}
			print '</ul>';
		}
?>

<?php
	}

	if (sizeof($categories) > 0) {
?>
	<h2>Categories in <?php print encodeHtml($currentCategory->name); ?></h2>
<?php
		if (sizeof($categories) > 0) {
			print '<ul class="list">';
			foreach ($categories as $subCat) {
?>
		<li><a href="<?php print getSiteRootURL() .buildDocumentsCategoryURL($subCat->id) ?>"><?php print encodeHtml($subCat->name); ?></a></li>
<?php
			}
			print '</ul>';
		}
	}
?>		
	
	<p><a href="<?php print getSiteRootURL() . buildCategoryRSSURL("documents", $_GET['categoryID']); ?>" target="_blank"><img src="<?php print getStaticContentRootURL(); ?>/site/images/xml.gif" alt=" " /> <?php print encodeHtml(METADATA_GENERIC_NAME . ' ' . $currentCategory->name); ?>  feed</a></p>
	
<?php
	if (isset($homepage) && $homepage->id > 0) {
?>
	<script type="text/javascript" src="<?php print getURLToWidgetJavascriptFile(); ?>"></script>
<?php
	}
?>
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include( $HOME . "site/includes/closing.php"); ?>
