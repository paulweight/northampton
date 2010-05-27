<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("websections/JaduNews.php");
	include_once("JaduAppliedCategories.php");
	include_once("websections/JaduFeedManager.php");
	
	$skipFirst = false;
	
	$lgcl = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$usedTopLevelCats = createItemIndex(NEWS_CATEGORIES_TABLE, $lgcl);
	$newsWithCats = sortAndFilterCategorisedNews ($usedTopLevelCats);
	
	//	This little lot checks whether the topNews for the category should be the 
	//	full News top News, or just the first for the category as a whole.
	$topNews = getTopNews(true, true);
	if ($topNews == -1) {
		$topNews = getLastNews(true, true);
	}
	
	if (isset($_GET['categoryID']) && sizeof($newsWithCats[$_GET['categoryID']]) > 0) {
		$categoryViewing = $lgcl->getCategory($_GET['categoryID']);
		
		if ($cat->id != $_GET['categoryID']) {
			$topNews = $newsWithCats[$_GET['categoryID']][0];
			$skipFirst = true;
		}
	}
	else {
		header("Location: http://$DOMAIN/site/scripts/news_index.php");
		exit();
	}

	$breadcrumb = 'newsCats';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print "$currentCategory->name "; ?> news | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<link rel="alternate" type="application/rss+xml" title="RSS" href="http://<?php print $DOMAIN;?>/site/scripts/rss.php" />

	<meta name="Keywords" content="news, <?php print $currentCategory->name; ?>, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />	
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s latest news regarding <?php print $currentCategory->name; ?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Latest <?php print "$currentCategory->name "; ?>news" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s latest news regarding <?php print $categoryViewing->name; ?>" />

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
		} 
		else {
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
			<a href="http://<?php print $DOMAIN;?>/site/scripts/news_article.php?newsID=<?php print $topNews->id;?>" ><?php print $topNews->title;?></a>
		</h2>
		<p class="date">Published <?php print date("jS F Y", $topNews->newsDate);?></p>
		<p><?php print $topNews->summary;?></p>
	</div>
	<!-- END top story -->
	

<?php	
		foreach ($newsWithCats[$categoryID] as $index => $news) {
			if ($skipFirst && $index == 0) {
			}
			else {
?>

	<div class="content_box">
		<h3>
			<a href="http://<?php print $DOMAIN;?>/site/scripts/news_article.php?newsID=<?php print $news->id;?>" ><?php print $news->title;?></a>
		</h3>
<?php 
		if ($news->imageURL != "") { 
?>
			<a href="http://<?php print $DOMAIN;?>/site/scripts/news_article.php?newsID=<?php print $news->id;?>" >
				<img src="http://<?php print $DOMAIN;?>/images/<?php print $news->imageURL;?>" alt="<?php print getImageProperty($news->imageURL, 'altText'); ?>" />
			</a>
<?php 
		}
?>
		<p class="date">Published <?php print date("jS F Y", $news->newsDate);?></p>
		<p><?php print $news->summary;?></p>
		<div class="clear"></div>
	</div>	
<?php
			}
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
