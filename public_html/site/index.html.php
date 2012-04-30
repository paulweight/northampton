<?php include("./includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml(METADATA_GENERIC_NAME); ?> Homepage</title>

	<link rel="stylesheet" type="text/css" href="<?php print getStaticContentRootURL(); ?>/site/styles/generic/homepages.css" media="screen" />
<?php 
	if (isset($homepage)) {
		$metadata = getMetadataForItem(HOMEPAGES_METADATA_TABLE, $homepage->id);
		if ($metadata->subject == '') {
			$metadata->subject = METADATA_GENERIC_KEYWORDS;
		}
		if ($metadata->description == '') {
			$metadata->description = METADATA_GENERIC_DESCRIPTION;
		}
	}
	else {
		$metadata = new JaduMetadata();
	}
?>

	<?php include_once("./includes/stylesheets.php"); ?>
	<?php include_once("./includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print encodeHtml($metadata->subject); ?>" />
	<meta name="Description" content="<?php print encodeHtml($metadata->description); ?>" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Tel: <?php print encodeHtml($address->telephone); ?>" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml($metadata->description); ?>" />

	<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php print getSiteRootURL() . buildRSSURL();?>" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("./includes/opening.php"); ?>
<!-- ########################## -->
		
<?php
	if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
?>
	<h2 class="welcome"><span>Hello, <strong><?php print encodeHtml($user->getDisplayName()); ?></strong> - You are signed-in</span></h2>
<?php
	}
	
	include('./includes/homepages_content.php');

	include('./includes/bottom_supplements.php');

	if (isset($homepage)) {
?>
	<script type="text/javascript" src="<?php print getStaticContentRootURL(); ?>/site/javascript/homepage_widget.js?md=<?php print filemtime($HOME.'/site/javascript/homepage_widget.js'); ?>"></script>
<?php
	}
?>
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("./includes/closing.php"); ?>
