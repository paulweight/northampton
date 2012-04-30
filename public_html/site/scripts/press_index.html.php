<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php print getSiteRootURL() . buildRSSURL();?>" />

	<meta name="Keywords" content="press releases, news, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />	
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s latest press releases directory" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> press releases" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s latest press releases directory" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if ($topPressRelease === null) {
?>
	<h2>Sorry, there are currently no press releases</h2>
<?php
	} 
	else {
		$cat = $categoryList->getCategory(getFirstCategoryIDForItemOfType(PRESS_RELEASES_CATEGORIES_TABLE, $topPressRelease->id));
?>
	<ul>
		<li>
			<h2><a href="<?php print getSiteRootURL() . buildPressArticleURL($topPressRelease->id, true, $topPressRelease->title);?>"><?php print encodeHtml($topPressRelease->title);?></a></h2>
<?php 
		if ($topPressRelease->imageURL != "") { 
?>
			<a href="<?php print getSiteRootURL() . buildPressArticleURL($topPressRelease->id, true, $topPressRelease->title);?>">
				<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($topPressRelease->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($topPressRelease->imageURL, 'altText')); ?> " />
			</a>
<?php 
		} 
?>
			<p>Published on <?php print formatDateTime(FORMAT_DATE_FULL, $topPressRelease->pressDate);?> in <?php if (isset($cat)) { ?><a href="<?php print getSiteRootURL() . buildPressURL($cat->id, $cat->name) ;?>"><?php print encodeHtml($cat->name); ?> press releases</a><?php } ?></p>
			<p><?php print encodeHtml($topPressRelease->summary); ?></p>
		</li>

<?php
		foreach ($allPressReleases as $pressRelease) {
			if ($pressRelease->id != $topPressRelease->id) {
				$cat = $categoryList->getCategory(getFirstCategoryIDForItemOfType(PRESS_RELEASES_CATEGORIES_TABLE, $pressRelease->id));
?>
		<li>
			<h3>
				<a href="<?php print getSiteRootURL() . buildPressArticleURL($pressRelease->id, true, $pressRelease->title); ?>"><?php print encodeHtml($pressRelease->title);?></a>
			</h3>
<?php 
				if ($pressRelease->imageURL != '') { 
?>
			<a href="<?php print getSiteRootURL() . buildPressArticleURL($pressRelease->id, true, $pressRelease->title); ?>">
				<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($pressRelease->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($pressRelease->imageURL, 'altText')); ?>" />
			</a>
<?php 
				} 
?>
			<p>Published on <?php print formatDateTime(FORMAT_DATE_FULL, $pressRelease->pressDate);?> in <?php if (isset($cat)) { ?><a href="<?php print getSiteRootURL() . buildPressURL($cat->id, $cat->name) ;?>"><?php print encodeHtml($cat->name); ?> press releases</a><?php } ?></p>
			<p><?php print encodeHtml($pressRelease->summary); ?></p>
		</li>
<?php
			}
		}
?>
	</ul>	
	<ul>
		<li>More articles in the <a href="<?php print getSiteRootURL() . buildPressArchiveURL(); ?>">press release archive</a></li>
		<li><a href="<?php print getSiteRootURL() . buildRSSURL('press'); ?>" target="_blank"><img src="<?php print getStaticContentRootURL(); ?>/site/images/xml.gif" alt=" " /> <?php print encodeHtml(METADATA_GENERIC_NAME); ?> Press releases feed</a></li>
	</ul>

<?php
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
