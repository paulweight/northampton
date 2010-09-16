<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduNews.php");
	include_once("JaduCategories.php");
	include_once("JaduAppliedCategories.php");
	include_once("websections/JaduFeedManager.php");

	$NUM_OF_NEWS_INDEX_ITEMS = 12;
	
	$topNews = getTopNews(true, true);
	if ($topNews == -1) {
		$topNews = getLastNews(true, true);
	}

	$bespokeCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$usedTopLevelCats = createItemIndex(NEWS_CATEGORIES_TABLE, $bespokeCategoryList);
	$newsWithCats = sortAndFilterCategorisedNews ($usedTopLevelCats);
	
	$allNews = getAllNewsByDateLimited($NUM_OF_NEWS_INDEX_ITEMS, true, true);
	
	// ensure that item is not the top news.
	$latestNews = array();
	$repeated = false;
	
	foreach($allNews as $news) {
		if($news->id == $topNews->id) {
			$repeated = true;
		}
	}
	if($repeated) {
		$allNews = getAllNewsByDateLimited($NUM_OF_NEWS_INDEX_ITEMS + 1, true, true);
		foreach($allNews as $news) {
			if($news->id != $topNews->id) {
				$latestNews [] = $news;
			}
		}
	}
	else {
		$latestNews = $allNews;
	}
		
	$breadcrumb = 'newsIndex';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Latest news | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<link rel="alternate" type="application/rss+xml" title="RSS" href="http://<?php print $DOMAIN;?>/site/scripts/rss.php" />

	<meta name="Keywords" content="news, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />	
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s latest news directory" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> news" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s latest news directory" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->


<?php
	if ($topNews == -1) {
?>

	<h2>Sorry, there is currently no news</h2>

<?php
	} else {
?>

	<!-- Top story -->
	<div class="lead">
	
<?php 
	if ($topNews->imageURL != "") { 
?>
		<a href="http://<?php print $DOMAIN;?>/site/scripts/news_article.php?newsID=<?php print $topNews->id;?>">
			<img src="http://<?php print $DOMAIN;?>/images/<?php print $topNews->imageURL;?>" alt="<?php print getImageProperty($topNews->imageURL, 'altText'); ?> " class="contentimage" />
		</a>
<?php
	}
?>
		<h2>
			<a href="http://<?php print $DOMAIN;?>/site/scripts/news_article.php?newsID=<?php print $topNews->id;?>">
			<?php print htmlentities($topNews->title, ENT_QUOTES, 'UTF-8');?></a>
		</h2>

<?php 
	
	$categoryID = getFirstCategoryIDForItemOfType (NEWS_CATEGORIES_TABLE, $topNews->id, BESPOKE_CATEGORY_LIST_NAME);
	$bespokeCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$currentCategory = $bespokeCategoryList->getCategory($categoryID);
?>
		<p class="date">Published <?php print date("jS F y", $topNews->newsDate);?> in <a href="http://<?php print $DOMAIN; ?>/site/scripts/news_category.php?categoryID=<?php print $currentCategory->id;?>"><?php print $currentCategory->name ?> news</a></p>
		<p><?php print str_replace('£', '&pound;', $topNews->summary);?></p>
	</div>

		<!-- News top 20 -->	
	<?php
		foreach ($latestNews as $newsItem) {
		$categoryID = getFirstCategoryIDForItemOfType (NEWS_CATEGORIES_TABLE, $newsItem->id, BESPOKE_CATEGORY_LIST_NAME);
		$bespokeCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		$currentCategory = $bespokeCategoryList->getCategory($categoryID);
	?>
		<div class="content_box">
	<?php 
		if ($newsItem->imageURL != "") { 
	?>
			<a href="http://<?php print $DOMAIN;?>/site/scripts/news_article.php?newsID=<?php print $newsItem->id;?>">
				<img src="http://<?php print $DOMAIN;?>/images/<?php print $newsItem->imageURL;?>" alt="<?php print getImageProperty($newsItem->imageURL, 'altText'); ?> "  />
			</a>
	<?php 
		} 
	?>
		<h3>
			<a href="http://<?php print $DOMAIN;?>/site/scripts/news_article.php?newsID=<?php print $newsItem->id;?>"><?php print htmlentities($newsItem->title, ENT_QUOTES, 'UTF-8');?></a>
		</h3>
			<p class="date">Published <?php print date("jS F y", $newsItem->newsDate);?> in <a href="http://<?php print $DOMAIN; ?>/site/scripts/news_category.php?categoryID=<?php print $currentCategory->id;?>"><?php print $currentCategory->name ?> news</a></p>
			<p><?php print str_replace('£', '&pound;', $newsItem->summary);?></p>
		</div>
	<?php
			
		}
	?>
	
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