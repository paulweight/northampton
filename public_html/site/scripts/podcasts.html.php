<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print encodeHtml($parent->name) . ', '; } ?><?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s podcasts available for download within the following categories<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> podcasts<?php foreach ($dirTree as $parent) { ?> | <?php print encodeHtml($parent->name); } ?>" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s podcasts available for download within the following categories<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
<?php
	if (count($allPodcasts) > 0) {
?>
	<h2>Available podcasts</h2>
<?php
		foreach ($allPodcasts as $podcast) {
?>
		<div class="download_box">
			<h3><a href="<?php print getSiteRootURL() . buildMultimediaPodcastsURL(-1, $podcast->id); ?>"><?php print encodeHtml($podcast->title); ?></a></h3>
			<p><?php print nl2br(encodeHtml($podcast->summary)); ?></p>
		</div>
<?php
		}
	}

	if (sizeof($categories) > 0) {
?>
	<div class="cate_info">
		<h2><?php print encodeHtml($parent->name); ?> categories</h2>
<?php
		if (sizeof($categories) > 0) {
?>
		<ul class="list icons podcast">
<?php
			foreach ($categories as $subCat) {
?>
			<li><a href="<?php print getSiteRootURL() . buildMultimediaPodcastsURL($subCat->id); ?>"><?php print encodeHtml($subCat->name); ?></a> </li>
<?php
			}
?>
		</ul>
<?php
		}
?>
	</div>
<?php
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>