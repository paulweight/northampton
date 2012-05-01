<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php print getSiteRootURL() . buildRSSURL();?>" />

	<meta name="Keywords" content="news, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />	
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s latest news directory" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> news" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s latest news directory" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if ($topNews === null) {
?>
	<h2>Sorry, there is currently no news</h2>
<?php
	} 
	else {
		$cat = $categoryList->getCategory(getFirstCategoryIDForItemOfType(NEWS_CATEGORIES_TABLE, $topNews->id));
?>

	<ul class="archive">
		<li class="lead">
			<h2><a href="<?php print getSiteRootURL() . buildNewsArticleURL($topNews->id, true, $topNews->title) ;?>"><?php print encodeHtml($topNews->title);?></a></h2>
			
<?php 
		if ($topNews->imageURL != "") { 
?>
			<a href="<?php print getSiteRootURL() . buildNewsArticleURL($topNews->id, true, $topNews->title) ;?>">
				<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($topNews->imageURL);?>" alt="<?php print encodeHtml(getImageProperty($topNews->imageURL, 'altText')); ?> " />
			</a>
<?php 
		} 
?>
			<p class="date">Published on <?php print formatDateTime(FORMAT_DATE_FULL, $topNews->newsDate);?> in <?php if (isset($cat)) { ?><a href="<?php print getSiteRootURL() . buildNewsURL($cat->id, $cat->name) ;?>"><?php print encodeHtml($cat->name); ?> news</a><?php } ?></p>
			<p><?php print encodeHtml($topNews->summary); ?></p>
			<div class="clear"></div>
		</li>

<?php
		foreach ($allNews as $news) {
			if ($news->id != $topNews->id) {
				$cat = $categoryList->getCategory(getFirstCategoryIDForItemOfType(NEWS_CATEGORIES_TABLE, $news->id));
?>
		<li>
			<h3>
				<a href="<?php print getSiteRootURL() . buildNewsArticleURL($news->id, true, $news->title) ;?>"><?php print encodeHtml($news->title);?></a>
			</h3>
<?php 
				if ($news->imageURL != '') { 
?>
				<a href="<?php print getSiteRootURL() . buildNewsArticleURL($news->id, true, $news->title); ?>">
					<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($news->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($news->imageURL, 'altText')); ?>" />
				</a>
<?php 
				} 
?>
			<p class="date">Published on <?php print formatDateTime(FORMAT_DATE_FULL, $news->newsDate);?> in <?php if (isset($cat)) { ?><a href="<?php print getSiteRootURL() . buildNewsURL($cat->id, $cat->name) ;?>"><?php print encodeHtml($cat->name); ?> news</a><?php } ?></p>
			<p><?php print encodeHtml($news->summary); ?></p>
			<div class="clear"></div>
		</li>
<?php
			}
		}
?>
	</ul>

	<p><strong>More articles in the <a href="<?php print getSiteRootURL() . buildNewsArchiveURL(); ?>">news archive</a></strong></p>
		<p><a class="rss" href="<?php print getSiteRootURL() . buildRSSURL();?>"><?php print encodeHtml(METADATA_GENERIC_NAME); ?> news feed</a></p>
	
<?php 	
	}
	
	$allRSSItems = getAllExternalFeedsForNews();
	if (!empty($allRSSItems)) {
?>

	<h2>Feeds from the Web</h2>
	<ul>	
<?php
		foreach ($allRSSItems as $RSSItem) {
?>
			<li><a href="<?php print getSiteRootURL(). buildFeedsURL($RSSItem->id); ?>"><?php print encodeHtml($RSSItem->name); ?></a></li>
<?php
		}
?>
	</ul>
<?php
	}
?>	

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>