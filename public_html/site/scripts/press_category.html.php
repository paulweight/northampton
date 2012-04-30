<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php print getSiteRootURL() . buildRSSURL('press'); ?>" />

	<meta name="Keywords" content="press releases, news, <?php print encodeHtml($categoryViewing->name); ?>, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />	
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s latest press releases regarding <?php print encodeHtml($categoryViewing->name); ?>" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Latest <?php print encodeHtml($categoryViewing->name); ?> press releases" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s latest press releases regarding <?php print encodeHtml($categoryViewing->name); ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
		if ($topPressRelease == null) {
?>

		<h2>Sorry, there are currently no <?php print encodeHtml($categoryViewing->name); ?> press releases</h2>

<?php
		} 
		else {
?>

	<ul>
		<!-- Top story -->
<?php
	if(count($topPressRelease) > 0){
?>
		<li>
			<h2><a href="<?php print getSiteRootURL() . buildPressArticleURL($topPressRelease->id, true, $topPressRelease->title); ?>" ><?php print encodeHtml($topPressRelease->title); ?></a></h2>
<?php 
			if ($topPressRelease->imageURL != "") {
?>
				<a href="<?php print getSiteRootURL() . buildPressArticleURL($topPressRelease->id, true, $topPressRelease->title); ?>">
					<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($topPressRelease->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($topPressRelease->imageURL, 'altText')); ?> " class="contentimage" />
				</a>
<?php 
			}
?>
				<p class="date">Published on <?php print formatDateTime(FORMAT_DATE_FULL, $topPressRelease->pressDate);?></p>
				<p><?php print encodeHtml($topPressRelease->summary); ?></p>
		</li>
<?php
			}
?>
		<!-- END top story -->
	

<?php	
		if (count($allPressReleases) > 0) {
			foreach ($allPressReleases as $pressRelease) {
?>
			<li>
				<h3><a href="<?php print getSiteRootURL() . buildPressArticleURL($pressRelease->id, true, $pressRelease->title); ?>"><?php print encodeHtml($pressRelease->title); ?></a></h3>
<?php 
				if ($pressRelease->imageURL != "") { 
?>
					<a href="<?php print getSiteRootURL() . buildPressArticleURL($pressRelease->id, true, $pressRelease->title); ?>" ><img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($pressRelease->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($pressRelease->imageURL, 'altText')); ?> " class="contentimage" /></a>
<?php 
				}
?>
				<p class="date">Published on <?php print formatDateTime(FORMAT_DATE_FULL, $pressRelease->pressDate);?></p>
				<p><?php print encodeHtml($pressRelease->summary); ?></p>
			</li>	
<?php
			}
		}
?>
	</ul>

		<ul>
			<li>More articles in the <a href="<?php print getSiteRootURL() . buildPressArchiveURL(); ?>">press release archive</a>.</li>
			<li><a href="<?php print getSiteRootURL() . buildRSSURL('press');?>" target="_blank"><img src="<?php print getStaticContentRootURL(); ?>/site/images/xml.gif" alt="Get this feed" /> <?php print encodeHtml(METADATA_GENERIC_NAME); ?> press releases feed</a></li>
			<li><a href="<?php print getSiteRootURL() . buildAboutRSSURL() ;?>">About RSS</a>.</li>
		</ul>
<?php
	}
?>

<?php
	if (sizeof($categories) > 0 ) {
?>
		<div class="cate_info">
			<h2>Categories in <?php print encodeHtml($categoryViewing->name); ?></h2>	
			<ul class="list">
<?php
			foreach ($categories as $subCat) {
?>
				<li><a href="<?php print getSiteRootURL().buildPressURL((int) $subCat->id, $subCat->name); ?>"><?php print encodeHtml($subCat->name); ?></a></li>
<?php
			}
?>
			</ul>
		</div>	
<?php
	}
?>

	<p><a href="<?php print getSiteRootURL() . buildCategoryRSSURL("press", $_GET['categoryID']); ?>" target="_blank"><img src="<?php print getStaticContentRootURL(); ?>/site/images/xml.gif" alt="Get this feed" /> <?php print encodeHtml(METADATA_GENERIC_NAME . ' ' . $categoryViewing->name); ?>  feed</a></p>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>