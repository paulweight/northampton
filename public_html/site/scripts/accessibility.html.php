<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="Accessibility, dda, disability discrimination act, disabled access, access keys, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Accessibility features" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ###### MAIN STRUCTURE ##### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php 
	if ($about->summary != "") { 
?>
	<p><?php print encodeHtml($about->summary); ?></p>
<?php 
	} 

	if ($about->imageURL != "") { 
		if (mb_strlen(getImageProperty($about->imageURL, 'longdesc')) > 0) { 
?>
	<div class="figcaption">
		<img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($about->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($about->imageURL, 'altText')); ?> " />
		<p><?php print encodeHtml(getImageProperty($about->imageURL, 'longdesc')); ?></p>
	</div>
<?php
		}
		else {
?>
	<img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($about->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($about->imageURL, 'altText')); ?> " />
<?php 
		}
	} 
?>
	<div class="byEditor article">
		<?php print processEditorContent($about->content); ?>
	</div>
			
<!-- ###### MAIN STRUCTURE ##### -->
<?php include("../includes/closing.php"); ?>