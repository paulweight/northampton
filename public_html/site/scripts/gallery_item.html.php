<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="news, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />	
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Multimedia Galleries - <?php print encodeHtml($gallery->title); ?> - <?php print encodeHtml($item->title);?>" />

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
else if ($galleryItem->id < 1 || $item->id < 1) {
?>
	<h2>Sorry, this gallery item is no longer available</h2>
<?php 
}
else {
?>

	<h2 class="topTitle"><?php print encodeHtml($item->title); ?></h2>
	<p class="date">Created on <strong><?php print formatDateTime(FORMAT_DATE_FULL, $item->dateCreated);?></strong> in <a href="<?php print getSiteRootURL() . buildMultimediaGalleriesURL(-1, $gallery->id); ?>"><?php print encodeHtml($gallery->title); ?></a>. There are <?php print (int) $gallery->getNumItems(); ?> items in this gallery.</p>
	<div id="gallery_item">
<?php
	if (!empty($item->caption)) {
?>
		<div class="imageCaption">
<?php			
	}

	if ($item->isAudio()) { 
		print $item->renderMediaPlayer(300, false);

		if (!empty($item->caption)) {
?>
			<p><?php print $item->caption; ?></p></div>
<?php
		}
	}
	else if ($item->isVideo()) {
		print $item->renderMediaPlayer($maxSize, false);

		if (!empty($item->caption)) {
?>
			<p><?php print $item->caption; ?></p></div>
<?php
		}
	}
	else if ($item->isImage()) {
?>
		<img class="gallery_img" src="<?php print getStaticContentRootURL() . $item->getThumbnail($maxSize); ?>" alt="<?php print encodeHtml($item->title); ?>" />
<?php
		if (!empty($item->caption)) {
?>
			<p><?php print $item->caption; ?></p></div>
<?php
		}

		if( $item->width > $maxSize || $item->height > $maxSize) {
?>
		<p class="centre"> <a class="zoom" href="<?php print getSiteRootURL();?>/images/<?php print $item->filename;?>"><span>Full size</span><img src="<?php print getStaticContentRootURL();?>/site/styles/css_img/zoom.gif" alt="" /></a></p>
<?php
		}
	}
	if (!empty($item->description)) {
?>
		<p><?php print processEditorContent($item->description); ?></p>
<?php
	}
?>
	</div>

<?php 
	if ($gallery->getNumItems() > 1) {
?> 
	<h3 class="centre">More in this gallery</h3>
	<ul id="gallery_near_items">
		
<?php
				if (isset($previousItem) && $previousItem) {
?>
			<li class="previous"><a href="<?php print getSiteRootURL() . buildMultimediaGalleriesURL(-1, $gallery->id, $previousGalleryItem->itemID, $previousItem->title); ?>" >
			<img src="<?php print getStaticContentRootURL() . (!$previousItem->isAudio() ? $previousItem->getThumbnail(300) : '/site/styles/css_img/audio_75.jpg'); ?>" alt="<?php print encodeHtml($previousItem->title); ?>" /></li>
 <?php
			if ($previousItem->isAudio()) { 
				print '<img class="typeIcon" src="' . getStaticContentRootURL().'/site/images/listen.gif" alt="listen" />';
			}
			if ($previousItem->isVideo()) { 
				print '<img class="typeIcon" src="' . getStaticContentRootURL().'/site/images/watch.gif" alt="watch" />';
			}
?>
			</a>
<?php
				}
				else {
?>
			<li class="end">You are viewing the first item</li>
<?php 
				}
?>
		
		
<?php
				if (isset($nextItem) && $nextItem) {
?>
					<li class="next"> <a href="<?php print getSiteRootURL() . buildMultimediaGalleriesURL(-1, $gallery->id, $nextGalleryItem->itemID, $nextItem->title); ?>">
						<img src="<?php print getStaticContentRootURL() . (!$nextItem->isAudio() ? $nextItem->getThumbnail(200) : '/site/styles/css_img/audio_75.jpg'); ?>" alt="<?php print encodeHtml($nextItem->title); ?>" />
<?php
			if ($nextItem->isAudio()) { 
				print '<img class="typeIcon" src="' . getStaticContentRootURL().'/site/images/listen.gif" alt="listen" />';
			}
			if ($nextItem->isVideo()) { 
				print '<img class="typeIcon" src="' . getStaticContentRootURL().'/site/images/watch.gif" alt="watch" />';
			}
?>
					</a></li>
<?php
				}
				else {
?>
					<li class="end">You are viewing the last item</li>
<?php
				}
?>
		
	</ul>
<?php
			}
		}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>