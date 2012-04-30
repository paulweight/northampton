<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<link rel="stylesheet" type="text/css" href="<?php print getStaticContentRootURL(); ?>/site/styles/generic/homepages.css" media="screen" />
<?php 
	
	include_once("../includes/stylesheets.php");
	include_once("../includes/metadata.php");
	$metadata = getMetadataForItem (HOMEPAGES_METADATA_TABLE, $_GET['homepageID']);
	if ($metadata->subject == '') {
		$metadata->subject = METADATA_GENERIC_KEYWORDS;
	}
	if ($metadata->description == '') {
		$metadata->description = $homepage->title . ' - ' . $homepage->description;
	}
?>
	<meta name="Keywords" content="<?php print encodeHtml($metadata->subject); ?>" />
	<meta name="Description" content="<?php print encodeHtml($metadata->description); ?>" />

	<?php printMetadata(HOMEPAGES_METADATA_TABLE, HOMEPAGE_CATEGORIES_TABLE, $homepage->id, $homepage->title, "http://".DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
	
<?php
	include('../includes/right_supplements.php'); 

	include('../includes/homepages_content.php');
	
	if (isset($_GET['categoryID']) && $_GET['categoryID'] != '') {

	}
?>
	
	<?php include('../includes/bottom_supplements.php'); ?>

	<script type="text/javascript" src="<?php print getURLToWidgetJavascriptFile(); ?>"></script>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
