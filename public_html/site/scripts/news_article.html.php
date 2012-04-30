<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

<?php
	include_once("../includes/stylesheets.php");
	include_once("../includes/metadata.php");
	
	$metadata = getMetadataForItem (NEWS_METADATA_TABLE, $news->id);
	if ($metadata->subject != '') {
		$metadata->subject .= ',';
	}
	$metadata->subject .= METADATA_GENERIC_KEYWORDS;
	if ($metadata->description == '') {
		$metadata->description = METADATA_GENERIC_NAME . '\'s ' . $news->title . ' news';
	}
?>
	
	<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php print getSiteRootURL() . buildRSSURL();?>" />

	<meta name="Keywords" content="news, <?php print encodeHtml($metadata->subject); ?>" />	
	<meta name="Description" content="<?php print encodeHtml($news->title);?> - <?php print encodeHtml($metadata->description);?>" />

	<?php printMetadata(NEWS_METADATA_TABLE, NEWS_CATEGORIES_TABLE, $news->id, $news->title, "http://".DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if ($news->id == -1) {
?>
	<h2>Sorry, this News item is no longer available</h2>		
<?php
	}
	else {
?>

	<p>Published <?php print formatDateTime(FORMAT_DATE_FULL, $news->newsDate);?></p>
<?php 
	if ($news->imageURL != "") { 
		if (mb_strlen(getImageProperty($news->imageURL, 'longdesc')) > 0) {
?>
		<div class="figcaption">
			<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($news->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($news->imageURL, 'altText')); ?>" />
			<p><?php print encodeHtml(getImageProperty($news->imageURL, 'longdesc')); ?></p>
		</div>
<?php
		}
		else {
?>
		<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($news->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($news->imageURL, 'altText')); ?>" />
<?php 
		}
	}
?>
	<p><?php print encodeHtml($news->summary); ?></p>
	<div class="byEditor article">
		<?php print processEditorContent($news->content); ?>
	</div>

	<ul>
		<li>More articles in the <a href="<?php print getSiteRootURL() . buildNewsArchiveURL(); ?>">news archive</a></li>
		<li><a href="<?php print getSiteRootURL() . buildRSSURL();?>"><img src="<?php print getStaticContentRootURL(); ?>/site/images/xml.gif" alt=" " /> <?php print encodeHtml(METADATA_GENERIC_NAME); ?> news feed</a></li>
	</ul>

<?php 	
		$allRSSItems = getAllExternalFeedsForNews();
		if (!empty($allRSSItems)) {
?>

	<h3>Feeds from the Web</h3>
	<ul>	
<?php
			foreach ($allRSSItems AS $RSSItem) {
				print '<li><a href="' . getSiteRootURL() . buildFeedsURL($RSSItem->id).'">'.encodeHtml($RSSItem->name).'</a></li>';
			}
?>
	</ul>
		
<?php
		}
?>	
		
	<!-- Social Bookmarks -->
	<?php include("../includes/social_bookmarks.php"); ?>
		
<?php
	}
?>
	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
