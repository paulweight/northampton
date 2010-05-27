<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("multimedia/JaduMultimediaGalleries.php");
	include_once("multimedia/JaduMultimediaItems.php");
	include_once("JaduAppliedCategories.php");
	include_once("JaduMetadata.php");
	
	if (isset($_GET['galleryID']) && is_numeric($_GET['galleryID']) && isset($_GET['galleryItemID']) && is_numeric($_GET['galleryItemID'])) {
		$gallery = getMultimediaGallery($_GET['galleryID'], array('live' => true));

		if (!$gallery) {
		    $dirTree = array();
		}
		else {
			$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
			$categoryID = getFirstCategoryIDForItemOfType (MULTIMEDIA_GALLERY_CATEGORIES_TABLE, $gallery->id, BESPOKE_CATEGORY_LIST_NAME);	
			$currentCategory = $lgclList->getCategory($categoryID);
			$dirTree = $lgclList->getFullPath($categoryID);
			
			$galleryItem = getMultimediaGalleryItem($_GET['galleryItemID']);
    		$item = $galleryItem->getItem();
			
			if ($previousGalleryItem = $galleryItem->getPrevious()) {
                $previousItem = $previousGalleryItem->getItem();
            }
			
			if ($nextGalleryItem = $galleryItem->getNext()) {
			    $nextItem = $nextGalleryItem->getItem();
			}
			
			if ($galleryItem) {
        	    multimediaGalleryItemRequestMade($galleryItem->id);
        	    $galleryItem->requests++;
        	}
		}
	}
	else {
		header("Location: galleries_index.php");
		exit;
	}
	
	$maxSize = 780;
	$breadcrumb = 'galleryItem';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $gallery->title;?> - <?php print $item->title;?> | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="news, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />	
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Multimedia Galleries - <?php print $gallery->title;?> - <?php print $item->title;?>" />

	<?php printMetadata(MULTIMEDIA_METADATA_TABLE, MULTIMEDIA_METADATA_TABLE, $gallery->id, $gallery->title, "http://".$DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
<?php
if (!isset($gallery) || !$gallery) { 
?>
	<h2>Sorry, this gallery is no longer available</h2>		
<?php
}
else if (!isset($galleryItem) || !$galleryItem || !isset($item) || !$item) {
?>
	<h2>Sorry, this gallery item is no longer available</h2>
<?php 
}
else {
?>
    <div style="margin-bottom: 15px;">
        <div style="float: left; width: 45%;">
            <h2><?php print $item->title; ?></h2>
            <p class="date">Published: <?php print date("l jS F Y", $item->dateCreated);?></p>
            <p class="details">Number of items in this gallery: <?php print $gallery->getNumItems(); ?></p>
            <p class="details">This item has been viewed <?php print $galleryItem->requests . ' time' . ($galleryItem->requests != 1 ? 's' : ''); ?></p>
        </div>
        <div style="float: right; width: 45%; text-align: right;">
            <h4>More in this gallery</h4>
            <ul id="gallery_near_items">
                <li class="previous">
<?php
                if ($previousGalleryItem && $previousItem) {
?>
                    <a href="http://<?php print $DOMAIN; ?>/site/scripts/gallery_item.php?galleryID=<?php print $gallery->id;?>&amp;galleryItemID=<?php print $previousGalleryItem->id;?>" title="Previous item: <?php print $previousItem->title; ?>">
                        <img src="http://<?php print $DOMAIN . (!$previousItem->isAudio() ? $previousItem->getThumbnail(75) : '/site/styles/css_img/audio_75.jpg'); ?>" alt="<?php print $previousItem->title; ?>" />
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
                if ($nextGalleryItem && $nextItem) {
?>
                    <a href="http://<?php print $DOMAIN; ?>/site/scripts/gallery_item.php?galleryID=<?php print $gallery->id;?>&amp;galleryItemID=<?php print $nextGalleryItem->id;?>" title="Next item: <?php print $nextItem->title; ?>">
                        <img src="http://<?php print $DOMAIN . (!$nextItem->isAudio() ? $nextItem->getThumbnail(75) : '/site/styles/css_img/audio_75.jpg'); ?>" alt="<?php print $nextItem->title; ?>" />
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
        </div>
        <div class="clear"></div>
    </div>
	<div id="gallery_item">
<?php
	if ($item->isAudio()) { 
?>
    	<object width="300" height="20">
        <param name="movie" value="http://<?php print $DOMAIN; ?>/site/javascript/mediaplayer/player.swf"></param>
        <param name="flashvars" value="autostart=false&amp;file=http://<?php print $DOMAIN; ?>/multimedia/<?php print $item->id; ?>/<?php print $item->id; ?>.mp3"></param>
        <embed src="http://<?php print $DOMAIN; ?>/site/javascript/mediaplayer/player.swf" type="application/x-shockwave-flash" width="300" height="20" flashvars="autostart=false&amp;file=http://<?php print $DOMAIN; ?>/multimedia/<?php print $item->id; ?>/<?php print $item->id; ?>.mp3"></embed>
        </object>
<?php
	}
	else if ($item->isVideo()) {
    	include_once('JaduImages.php');
    	list($width, $height) = scaleImg($item->width, $item->height, $maxSize);
?>
    	<object width="<?php print $width; ?>" height="<?php print $height; ?>">
        <param name="movie" value="http://<?php print $DOMAIN; ?>/site/javascript/mediaplayer/player.swf"></param>
        <param name="flashvars" value="autostart=false&amp;file=http://<?php print $DOMAIN; ?>/multimedia/<?php print $item->id; ?>/<?php print $item->id; ?>.flv&amp;image=http://<?php print $DOMAIN . $item->getThumbnail($maxSize); ?>"></param>
        <embed src="http://<?php print $DOMAIN; ?>/site/javascript/mediaplayer/player.swf" type="application/x-shockwave-flash" width="<?php print $width; ?>" height="<?php print $height; ?>" flashvars="autostart=false&amp;file=http://<?php print $DOMAIN; ?>/multimedia/<?php print $item->id; ?>/<?php print $item->id; ?>.flv&amp;image=http://<?php print $DOMAIN . $item->getThumbnail($maxSize); ?>"></embed>
        </object>
<?php
	}
	else if ($item->isImage()) {
?>
	    <img src="http://<?php print $DOMAIN . $item->getThumbnail($maxSize); ?>" alt="<?php print $item->title;?>" />
<?php
	}
?>
    </div>
<?php
}
?>

<!-- The Contact box -->
<?php include("../includes/contactbox.php"); ?>
<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>