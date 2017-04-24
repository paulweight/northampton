<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s search results" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Search results" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s search results" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php echo \Jadu\Service\Container::getInstance()->getSiteSearch()->getSearchResults(); ?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
