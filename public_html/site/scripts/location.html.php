<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s location map and directions" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> location details" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s location map and directions" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
				
<?php 
	if ($location->alternativeURL != '') {
?>
	<p><a href="<?php print encodeHtml($location->alternativeURL); ?>"><?php print encodeHtml($location->alternativeURL); ?></a></p>
<?php
	}
	
	if ($location->mapURL != '') {
		if (mb_strlen(getImageProperty($location->mapURL, 'longdesc')) > 0) { 
?>
		<div class="figcaption">
			<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($location->mapURL); ?>" alt="<?php print encodeHtml(getImageProperty($location->mapURL, 'altText')); ?>" />		
			<p><?php print encodeHtml(getImageProperty($location->mapURL, 'longdesc')); ?></p>
		</div>
<?php
		}
		else {
?>
		<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($location->mapURL); ?>" alt="<?php print encodeHtml(getImageProperty($location->mapURL, 'altText')); ?>" />		
<?php
		}
	}
?>
	<div class="byEditor article">
		<?php print processEditorContent($location->directions); ?>
	</div>
<?php
	if (mb_strlen($address->address) > 0) {
?>
	<p><?php print encodeHtml(METADATA_GENERIC_NAME); ?>,<br /><?php print nl2br(encodeHtml($address->address)); ?></p>
<?php 
	}
?>
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
