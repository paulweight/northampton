<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="news, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />	
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Multimedia Galleries - <?php print encodeHtml($gallery->title); ?> - <?php print encodeHtml($item->title);?>" />

	<?php printMetadata(MULTIMEDIA_GALLERY_METADATA_TABLE, MULTIMEDIA_GALLERY_CATEGORIES_TABLE, $gallery->id, $gallery->title, "http://".$DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?> 
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

	<h2><?php print encodeHtml($item->title); ?></h2>
	<div id="gallery_item" style="width: <?php print $maxSize; ?>px;">
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
		<img src="<?php print getStaticContentRootURL() . $item->getThumbnail($maxSize); ?>" alt="<?php print encodeHtml($item->title); ?>" />
<?php
		if (!empty($item->caption)) {
?>
			<p><?php print $item->caption; ?></p></div>
<?php
		}

		if( $item->width > $maxSize || $item->height > $maxSize) {
?>
		<p><img src="<?php print getStaticContentRootURL();?>/site/images/search.gif" alt="" /> <a href="<?php print getSiteRootURL();?>/images/<?php print $item->filename;?>">Full size</a></p>
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
	<h3>More in this gallery</h3>
	<ul id="gallery_near_items">
		<li class="previous">
<?php
				if (isset($previousItem) && $previousItem) {
?>
			<a href="<?php print getSiteRootURL() . buildMultimediaGalleriesURL(-1, $gallery->id, $previousGalleryItem->itemID, $previousItem->title); ?>" >
			<img src="<?php print getStaticContentRootURL() . (!$previousItem->isAudio() ? $previousItem->getThumbnail(75) : '/site/styles/css_img/audio_75.jpg'); ?>" alt="<?php print encodeHtml($previousItem->title); ?>" />
 <?php
			if ($previousItem->isAudio()) { 
				print '<img class="typeIcon" src="http://'.DOMAIN.'/site/images/listen.gif" alt="listen" />';
			}
			if ($previousItem->isVideo()) { 
				print '<img class="typeIcon" src="http://'.DOMAIN.'/site/images/watch.gif" alt="watch" />';
			}
?>
			</a>
<?php
				}
				else {
?>
			You are viewing the first item
<?php 
				}
?>
		</li>
		<li class="next"> 
<?php
				if (isset($nextItem) && $nextItem) {
?>
					<a href="<?php print getSiteRootURL() . buildMultimediaGalleriesURL(-1, $gallery->id, $nextGalleryItem->itemID, $nextItem->title); ?>">
						<img src="<?php print getStaticContentRootURL() . (!$nextItem->isAudio() ? $nextItem->getThumbnail(75) : '/site/styles/css_img/audio_75.jpg'); ?>" alt="<?php print encodeHtml($nextItem->title); ?>" />
<?php
			if ($nextItem->isAudio()) { 
				print '<img class="typeIcon" src="http://'.DOMAIN.'/site/images/listen.gif" alt="listen" />';
			}
			if ($nextItem->isVideo()) { 
				print '<img class="typeIcon" src="http://'.DOMAIN.'/site/images/watch.gif" alt="watch" />';
			}
?>
					</a>
<?php
				}
				else {
?>
					You are viewing the last item
<?php
				}
?>
		</li>
	</ul>
<?php
			}
?>
	<h3>Additional information</h3>
	<p>Created on <?php print formatDateTime(FORMAT_DATE_FULL, $item->dateCreated);?></p>
	<p><?php print (int) $gallery->getNumItems(); ?> items in this gallery</p>

<?php
}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>