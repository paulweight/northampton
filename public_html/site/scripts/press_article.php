<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("websections/JaduPressReleases.php");
	include_once("JaduAppliedCategories.php");
	include_once("JaduMetadata.php");
	include_once("websections/JaduFeedManager.php");


	include_once("utilities/JaduMostPopular.php");
	
	if (isset($_GET['pressReleasesID'])) {
		$PressReleases = getPressReleases($_GET['pressReleasesID'], true, true);
	}
	else {
		$PressReleases = getTopPressReleases(true, true);
		if ($PressReleases == -1) {
			$PressReleases = getLastPressReleases(true, true);
		}
	}
	
	// most popular
	if (strpos($_SERVER['HTTP_REFERER'], 'google_results.php') !== false && isset($_GET['pressReleasesID'])) {

		$url = '/site/scripts/press_article.php?pressReleaseID=' . $_GET['pressReleaseID'];

		$mostPopularItem = getMostPopularItem ('url', $url);

		if ($mostPopularItem->id != -1) {
			$mostPopularItem->hits++;
			updateMostPopularItem($mostPopularItem);
		}
		else {
			$mostPopularItem->hits = 1;
			$mostPopularItem->url = $url;
			$mostPopularItem->title = $PressReleases->title;

			newMostPopularItem($mostPopularItem);
		}
	}
	
	$breadcrumb = 'PressReleasesArticle';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print (isset($PressReleases->title)) ? $PressRelease->title : 'Press release item no longer availiable' ;?> | Press releases | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<link rel="alternate" type="application/rss+xml" title="RSS" href="http://<?php print $DOMAIN;?>/site/scripts/rss.php" />

	<meta name="Keywords" content="press releases, news, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />	
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Press releases - <?php print $PressReleases->title;?> - <?php print $PressReleases->summary;?>" />

	<?php printMetadata(PRESS_RELEASES_METADATA_TABLE, PRESS_RELEASES_CATEGORIES_TABLE, $PressReleases->id, $PressReleases->title, "http://".$DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>	
	
	<script type="text/javascript" src="<?php print $PROTOCOL.$DOMAIN;?>/site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if ($PressReleases == -1) {
?>
	<h2>Sorry, this Press release item is no longer available</h2>		
<?php
	}
	else {
?>
	<p class="date">Published <?php print date("l jS F y", $PressReleases->pressDate);?></p>
<?php 
	if ($PressReleases->imageURL != "") { 
?>
		<img src="http://<?php print $DOMAIN; ?>/images/<?php print $PressReleases->imageURL;?>" alt="<?php print getImageProperty($PressReleases->imageURL, 'altText'); ?>" class="main_image" />
<?php 
	}
?>
	<p class="first"><?php print $PressReleases->summary;?></p>
	<div class="byEditor"><?php print $PressReleases->content;?></div>
	
	<p class="rssfeed"><a href="http://<?php print $DOMAIN;?>/site/scripts/pressRss.php" target="_blank">Press release RSS</a></p>
	<p class="first"><a href="http://<?php print $DOMAIN; ?>/site/scripts/press_archive.php">Press release archive</a></p>
<?php
	}
?>
	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>