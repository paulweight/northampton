<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("multimedia/JaduMultimediaGalleries.php");
	include_once("JaduAppliedCategories.php");
	include_once("JaduMetadata.php");
	
	if (isset($_GET['galleryID']) && is_numeric($_GET['galleryID']) && $_GET['galleryID'] > 0) {
		$gallery = getMultimediaGallery($_GET['galleryID'], array('live' => true));
	}
	else {
		header("Location: galleries_index.php");
		exit;
	}
	
	if (!$gallery) {
	    $dirTree = array();
	}
	else {
		$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		$categoryID = getFirstCategoryIDForItemOfType (MULTIMEDIA_GALLERY_CATEGORIES_TABLE, $gallery->id, BESPOKE_CATEGORY_LIST_NAME);	
		$currentCategory = $lgclList->getCategory($categoryID);
		$dirTree = $lgclList->getFullPath($categoryID);
		
		$numGalleryItems = getNumMultimediaGalleryItems($gallery->id);
		if ($numGalleryItems > 0) {
        	// Set the current page
        	if (!isset($_GET['currentPage']) || $_GET['currentPage'] < 1) {
        		$currentPage = 1;
        	}
        	else {
        		$currentPage = $_GET['currentPage'];
        	}
    	
        	$itemsPerPage = 12;
        	$PAGE_NUMBERS_TO_DISPLAY = 10;
        	$offset = (($currentPage-1) * $itemsPerPage);
        	$pageCount = ceil($numGalleryItems / $itemsPerPage);
        	if ($offset > $numGalleryItems) {
        		$offset = $numGalleryItems - $itemsPerPage;
        		$currentPage = $pageCount;
        	}
        	if ($currentPage < $pageCount) {
        		$nextPage = $currentPage + 1;
        	}
        	if ($currentPage > 1) {
        		$previousPage = $currentPage - 1;
        	}

        	$criteria = array(
        		'orderBy' => 'galleryItem.position',
        		'orderDir' => 'ASC',
        		'limit' => $itemsPerPage,
        		'offset' => $offset
        	);
    	
        	$allGalleryItems = getAllMultimediaGalleryItems($gallery->id, $criteria);
        }
	}
	
	$maxSize = 780;
	
	$breadcrumb = 'galleryInfo';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $gallery->title;?> | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="news, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />	
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Multimedia Galleries - <?php print $gallery->title;?>" />

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
else {
?>
	<div class="byEditor"><?php print $gallery->description; ?></div>
<?php
	if ($numGalleryItems == 0) {
?>
	<p>There are no items in this gallery yet.</p>
<?php
	}
	else {
?>
	<ul id="gallery_list">
<?php
		foreach ($allGalleryItems as $index => $galleryItem) {
		    $item = $galleryItem->getItem();
?>
		<li<?php if ($index % 4 == 0) { print ' class="start"'; } ?>>
			<a href="http://<?php print $DOMAIN; ?>/site/scripts/gallery_item.php?galleryID=<?php print $gallery->id;?>&amp;galleryItemID=<?php print $galleryItem->id;?>">
				<img src="http://<?php print $DOMAIN . (!$item->isAudio() ? $item->getThumbnail(150) : '/site/styles/css_img/audio_150.jpg'); ?>" alt="<?php print $item->title; ?>" />
			</a><br />
			<a href="http://<?php print $DOMAIN; ?>/site/scripts/gallery_item.php?galleryID=<?php print $gallery->id;?>&amp;galleryItemID=<?php print $galleryItem->id;?>"><?php print $item->title; ?></a>
		</li>
<?php
		}
?>
	</ul>
	
	<div class="page_nav">
<?php
    // Previous page
    if ($currentPage != 1) {
    	$previousPage = $currentPage - 1;
    	print '<a href="'.modifyRequestParameters('currentPage='.$previousPage.'&itemsPerPage='.$itemsPerPage).'">&laquo; Previous page</a>';
        if ($currentPage != $pageCount) {
            print ' | ';
        }
    }

    // Next page
    if ($currentPage != $pageCount) {
    	$nextPage = $currentPage + 1;
    	print '<a href="'.modifyRequestParameters('currentPage='.$nextPage.'&itemsPerPage='.$itemsPerPage).'">Next page &raquo;</a>';
    }
?>
    </div>
<?php
	}
}
?>

<!-- Related information -->
<?php include("../includes/related_info.php"); ?>

<!-- The Contact box -->
<?php include("../includes/contactbox.php"); ?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>