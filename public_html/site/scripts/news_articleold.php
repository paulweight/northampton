<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("websections/JaduNews.php");
	include_once("JaduAppliedCategories.php");
	include_once("JaduMetadata.php");
	
	include_once("websections/JaduFeedManager.php");
	include_once("utilities/JaduMostPopular.php");
	
	//validate inputs
	if (!is_numeric($_GET['newsID'])) {
		header("Location:  http://$DOMAIN/site/scripts/news_index.php");
		exit();
	}
	
	if (isset($_GET['newsID'])) {
		$news = getNews($_GET['newsID'], true, true);
	}
	else {
		$news = getTopNews(true, true);
		if ($news == -1) {
			$news = getLastNews(true, true);
		}
	}
	
	//if the news item doesnt exist, re-direct to index
	if ($news == -1) {
		header("Location:  http://$DOMAIN/site/scripts/news_index.php");
		exit();
	}
		
	// most popular
	if (strpos($_SERVER['HTTP_REFERER'], 'google_results.php') !== false && isset($_GET['newsID'])) {

		$url = '/site/scripts/news_article.php?newsID=' . $_GET['newsID'];

		$mostPopularItem = getMostPopularItem ('url', $url);

		if ($mostPopularItem->id != -1) {
			$mostPopularItem->hits++;
			updateMostPopularItem($mostPopularItem);
		}
		else {
			$mostPopularItem->hits = 1;
			$mostPopularItem->url = $url;
			$mostPopularItem->title = $news->title;

			newMostPopularItem($mostPopularItem);
		}
	}

	$breadcrumb = 'newsArticle';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $news->title;?> | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<link rel="alternate" type="application/rss+xml" title="RSS" href="http://<?php print $DOMAIN;?>/site/scripts/rss.php" />

	<meta name="Keywords" content="news, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />	
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> News - <?php print $news->title;?> - <?php print $news->summary;?>" />

	<?php printMetadata(NEWS_METADATA_TABLE, NEWS_CATEGORIES_TABLE, $news->id, $news->title, "http://".$DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
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
	<p class="date">Published <?php print date("l jS F y", $news->newsDate);?></p>
<?php 
	if ($news->imageURL != "") { 
?>
	<img src="http://<?php print $DOMAIN; ?>/images/<?php print $news->imageURL;?>" alt="<?php print getImageProperty($news->imageURL, 'altText'); ?>" class="contentimage" />
<?php 
	}
?>
	<p class="first"><?php print $news->summary;?></p>
	<div class="byEditor">
		<?php print $news->content;?>
	</div>

	<p class="rssfeed"><a href="http://<?php print $DOMAIN;?>/site/scripts/rss.php" target="_blank">News RSS</a></p>
	<p class="first"><a href="http://<?php print $DOMAIN; ?>/site/scripts/news_archive.php">News archive</a></p>
		
<?php
	}
?>


	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>