<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	
	<?php require_once("../includes/stylesheets.php"); ?>
	<?php require_once("../includes/metadata.php"); ?>

	<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php print getSiteRootURL() . buildRSSURL();?>" />

	<meta name="Keywords" content="news, <?php print encodeHtml($currentCategory->name); ?>, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />	
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s latest news regarding <?php print encodeHtml($currentCategory->name); ?>" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Latest <?php print encodeHtml($currentCategory->name); ?>news" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s latest news regarding <?php print encodeHtml($currentCategory->name); ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->


	<ul class="archive">
<?php
	if (isset($topNews) && $topNews->id != -1) {
?>
		<li class="lead">
			<h2><a href="<?php print getSiteRootURL() . buildNewsArticleURL($topNews->id, true, $topNews->title) ;?>" ><?php print encodeHtml($topNews->title); ?></a></h2>
<?php 
			if ($topNews->imageURL != "") {
?>
			<a href="<?php print getSiteRootURL() . buildNewsArticleURL($topNews->id, true, $topNews->title) ;?>"> 
				<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($topNews->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($topNews->imageURL, 'altText')); ?> " />
			</a>
<?php 
			}
?>
			<p class="date">Published on <?php print formatDateTime(FORMAT_DATE_FULL, $topNews->newsDate);?></p>
			<p><?php print encodeHtml($topNews->summary); ?></p>
			<div class="clear"></div>
		</li>


<?php
		foreach ($allNews as $index => $news) {
?>

		<li>
			<h3><a href="<?php print getSiteRootURL() . buildNewsArticleURL($news->id, true, $news->title) ;?>" ><?php print encodeHtml($news->title);?></a></h3>
<?php 
			if ($news->imageURL != "") { 
?>
			<a href="<?php print getSiteRootURL() . buildNewsArticleURL($news->id, true, $news->title);?>" >
				<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($news->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($news->imageURL, 'altText')); ?> " />
			</a>
<?php 
			}
?>
			<p class="date">Published on <?php print formatDateTime(FORMAT_DATE_FULL, $news->newsDate);?></p>
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
		<p><a class="rss" href="<?php print getSiteRootURL() . buildCategoryRSSURL("news", $_GET['categoryID']); ?>"><?php print encodeHtml(METADATA_GENERIC_NAME . ' ' . $currentCategory->name); ?>  feed</a></p>
	

<?php
	if (sizeof($categories) > 0) {
?>
		<div class="cate_info">
			<h3>Categories in <?php print encodeHtml($currentCategory->name); ?></h3>
			<ul class="list icons news">
<?php
			foreach ($categories as $subCat) {
?>
				<li><a href="<?php print getSiteRootURL().buildNewsURL((int) $subCat->id, $subCat->name); ?>"><?php print encodeHtml($subCat->name); ?></a></li>
<?php
			}
?>
			</ul>
		</div>	
<?php
	}
?>

<?php 	
		$allRSSItems = getAllExternalFeedsForNews();
		if (!empty($allRSSItems)) {
?>

	<h3>Feeds from the Web</h3>
	<ul>	
<?php
			foreach ($allRSSItems as $RSSItem) {
				print '<li><a href="' . getSiteRootURL().'/site/scripts/view_feeds.php?view=feed&amp;feedID='.(int) $RSSItem->id.'">'.encodeHtml($RSSItem->name).'</a></li>';
			}
?>
	</ul>
<?php
		}
?>	

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
