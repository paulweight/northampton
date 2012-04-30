<?php include_once("../includes/doctype.php"); ?>
<head>
	<title>Download not found | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="downloads, download, documents, pdf, word, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> download" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> download" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> download" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
if (!isset($download) || $download->id < 1) {
?>
	<h2>Sorry, this download is no longer available</h2>
<?php
}
else if (!$allowDownload) {
?>
	<h2>Sorry, this download is password protected. You must first <a href="<?php print buildDownloadsURL(-1, $file->id, $download->id); ?>">authenticate yourself</a>.</h2>
<?php
}
else {
?>
	<h2>Sorry, this file is no longer available for download</h2>
<?php
}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>