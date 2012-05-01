<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print encodeHtml($parent->name) . ', '; } ?><?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s galleries available for download within the following categories<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> galleries<?php foreach ($dirTree as $parent) { ?> | <?php print encodeHtml($parent->name); } ?>" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>'s galleries available for download within the following categories<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
<?php
	if (sizeof($allGalleries) > 0) {
?>
	<h2>Available galleries</h2>
	<ul class="archive">
<?php
		foreach ($allGalleries as $gallery) {
?>
	
<?php
			if ($featured = $gallery->getFeaturedItem()) {
?>
		<li><a href="<?php print getSiteRootURL() . buildMultimediaGalleriesURL(-1, $gallery->id); ?>" >
			<img src="<?php print getStaticContentRootURL() . (!$featured->isAudio() ? $featured->getThumbnail(100) : '/site/styles/css_img/audio_featured.gif'); ?>" alt="<?php print encodeHtml($featured->title); ?>" />
		</a>
<?php
			}
?>
		<h3><a href="<?php print getSiteRootURL() . buildMultimediaGalleriesURL(-1, $gallery->id); ?>"><?php print encodeHtml($gallery->title); ?></a></h3>
		<p><?php print nl2br(encodeHtml($gallery->summary)); ?></p></li>
	

<?php
		}
?>
	</ul>
<?php
	}

	if (sizeof($categories) > 0) {
?>
	<div class="cate_info">
		<h3><?php print encodeHtml($parent->name); ?> categories</h3>
		<ul class="list icons galleries">
<?php
			foreach ($categories as $subCat) {
?>
			<li><a href="<?php print getSiteRootURL() . buildMultimediaGalleriesURL($subCat->id); ?>"><?php print encodeHtml($subCat->name); ?></a> </li>
<?php
			}
?>
		</ul>
	</div>
<?php
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>