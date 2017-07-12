<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

<?php
	include_once("../includes/stylesheets.php");
	include_once("../includes/metadata.php");
	
	$metadata = getMetadataForItem (MULTIMEDIA_GALLERY_METADATA_TABLE, $gallery->id);
	if ($metadata->subject != '') {
		$metadata->subject .= ',';
	}
	$metadata->subject .= METADATA_GENERIC_KEYWORDS;
	if ($metadata->description == '') {
		$metadata->description = METADATA_GENERIC_NAME . '\'s ' . $gallery->title . ' gallery';
		foreach ($dirTree as $parent) {
			$metadata->description .= ' | ' . $parent->name;
		}
	}
?>
	
	<meta name="Keywords" content="<?php print encodeHtml($metadata->subject); ?>" />	
	<meta name="Description" content="<?php print encodeHtml($gallery->title); ?> Multimedia Gallery - <?php print encodeHtml($metadata->description); ?>" />

	<?php printMetadata(MULTIMEDIA_GALLERY_METADATA_TABLE, MULTIMEDIA_GALLERY_CATEGORIES_TABLE, $gallery->id, $gallery->title, getSiteRootURL().$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?> 
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
if ($gallery->id < 1) {
?>
	<h2>Sorry, this gallery is no longer available</h2>
<?php
}
else {
?>

	<div class="byEditor article">
		<p><?php print processEditorContent($gallery->description); ?></p>
	</div>
	
<?php
	if ($numGalleryItems == 0) {
?>
	<p>There are no items in this gallery just yet.</p>
<?php
	}
	else {
?>

	<ul class="galleryInfo">
<?php
		foreach ($allGalleryItems as $index => $item) {
?>
		<li<?php if ($index % 4 == 0) { print ' class="start"'; } ?>>
			<a href="<?php print getSiteRootURL() . buildMultimediaGalleriesURL(-1, $gallery->id, $item->id, $item->title); ?>">
<?php
			if (!empty($item->caption)) {
?>
				<div class="imageCaption">
<?php			
			}
?>
				<img src="<?php print getStaticContentRootURL() . (!$item->isAudio() ? $item->getThumbnail(150) : '/site/styles/css_img/audio_150.jpg'); ?>" alt="<?php print encodeHtml($item->title); ?>" />
<?php
			if (!empty($item->caption)) {
?>
				<p><?php print $item->caption; ?></p></div>
<?php
			}

			if ($item->isAudio()) { 
				print '<img class="typeIcon" src="' . getStaticContentRootURL().'/site/images/listen.gif" alt="listen" />';
			}
			if ($item->isVideo()) { 
				print '<img class="typeIcon" src="' . getStaticContentRootURL().'/site/images/watch.gif" alt="watch" />';
			}
?>
			</a>
			<span>Published on <?php print formatDateTime(FORMAT_DATE_SHORT, $item->dateCreated);?></span>
		</li>
<?php
		}
?>
	</ul>
	
	<p>
<?php
	// Previous page
	if ($currentPage != 1) {
		$previousPage = $currentPage - 1;
		print '<a href="' . getSiteRootURL() . buildMultimediaGalleriesURL(-1, $gallery->id) . ((READABLE_URLS_ENABLED)?'?':'&') . 'currentPage=' . (int) $previousPage . '&amp;itemsPerPage=' . (int) $itemsPerPage . '">&laquo; Previous page</a>';
		if ($currentPage != $pageCount) {
			print ' | ';
		}
	}

	// Next page
	if ($currentPage != $pageCount) {
		$nextPage = $currentPage + 1;
		print '<a href="' . getSiteRootURL() . buildMultimediaGalleriesURL(-1, $gallery->id) . ((READABLE_URLS_ENABLED)?'?':'&') .'currentPage=' . (int) $nextPage . '&amp;itemsPerPage=' . (int) $itemsPerPage . '">Next page &raquo;</a>';
	}
?>
	</p>
<?php
	}
}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>