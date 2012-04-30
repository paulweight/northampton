<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="terms, disclaimer, legal, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="Terms and disclaimers for <?php print encodeHtml(METADATA_GENERIC_NAME); ?> Online" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Terms and disclaimers" />
	<meta name="DC.description" lang="en" content="Terms and disclaimers for <?php print encodeHtml(METADATA_GENERIC_NAME); ?> Online" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
<?php
	if ($terms->summary != "") { 
?>
	<p><?php print encodeHtml($terms->summary); ?></p>
<?php 
	} 

	if ($terms->imageURL != "") { 
		if (mb_strlen(getImageProperty($terms->imageURL, 'longdesc')) > 0) { 
?>
	<div class="figcaption">
		<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($terms->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($terms->imageURL, 'altText')); ?> " />
		<p><?php print encodeHtml(getImageProperty($terms->imageURL, 'longdesc')); ?></p>
	</div>
<?php
		}
		else {
?>
		<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($terms->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($terms->imageURL, 'altText')); ?> " />
<?php 
		}
	} 
?>	
	<div class="byEditor article">	
		<?php print processEditorContent($terms->content); ?>
	</div>
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
