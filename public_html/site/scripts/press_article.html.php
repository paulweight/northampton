<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

<?php
	include_once("../includes/stylesheets.php");
	include_once("../includes/metadata.php");
	
	$metadata = getMetadataForItem (PRESS_RELEASES_METADATA_TABLE, $pressRelease->id);
	if ($metadata->subject != '') {
		$metadata->subject .= ',';
	}
	$metadata->subject .= METADATA_GENERIC_KEYWORDS;
	if ($metadata->description == '') {
		$metadata->description = METADATA_GENERIC_NAME . '\'s ' . $pressRelease->title . ' information';
	}
?>

	<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php print getSiteRootURL() . buildRSSURL('press');?>" />

	<meta name="Keywords" content="press releases, news, <?php print encodeHtml($metadata->subject); ?>" />	
	<meta name="Description" content="<?php print encodeHtml($metadata->description); ?>" />

	<?php printMetadata(PRESS_RELEASES_METADATA_TABLE, PRESS_RELEASES_CATEGORIES_TABLE, $pressRelease->id, $pressRelease->title, "http://".DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>	
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<p>Published <?php print formatDateTime(FORMAT_DATE_FULL, $pressRelease->pressDate);?></p>
<?php 
	if ($pressRelease->imageURL != "") { 
		if (mb_strlen(getImageProperty($pressRelease->imageURL, 'longdesc')) > 0) {
?>
	<div class="figcaption">
		<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($pressRelease->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($pressRelease->imageURL, 'altText')); ?>" />
		<p><?php print encodeHtml(getImageProperty($pressRelease->imageURL, 'longdesc')); ?></p>
	</div>
<?php
		}
		else {
?>
	<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($pressRelease->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($pressRelease->imageURL, 'altText')); ?>" />
<?php 
		}
	}
?>
	<p><?php print encodeHtml($pressRelease->summary);?></p>
	<div class="byEditor article">
		<?php print $pressRelease->content; ?>
	</div>
	
	<ul>
		<li>More articles in the <a href="<?php print getSiteRootURL() . buildPressArchiveURL(); ?>">press release archive</a></li>
		<li><a href="<?php print getSiteRootURL() . buildRSSURL('press'); ?>" target="_blank"><img src="<?php print getStaticContentRootURL(); ?>/site/images/xml.gif" alt=" " /> <?php print encodeHtml(METADATA_GENERIC_NAME); ?> press release feed</a></li>
	</ul>
	
	<!-- Social Bookmarks -->
	<?php include("../includes/social_bookmarks.php"); ?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
