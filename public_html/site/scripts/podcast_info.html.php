<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

<?php
	include_once("../includes/stylesheets.php");
	include_once("../includes/metadata.php");
	
	$metadata = getMetadataForItem (MULTIMEDIA_PODCAST_METADATA_TABLE, $podcast->id);
	if ($metadata->subject != '') {
		$metadata->subject .= ',';
	}
	$metadata->subject .= METADATA_GENERIC_KEYWORDS;
	if ($metadata->description == '') {
		$metadata->description = METADATA_GENERIC_NAME . '\'s ' . $podcast->title . ' podcast';
		foreach ($dirTree as $parent) {
			$metadata->description .= ' | ' . $parent->name;
		}
	}
?>
	
	<meta name="Keywords" content="<?php print encodeHtml($metadata->subject); ?>,podcast" />	
	<meta name="Description" content="<?php print encodeHtml($metadata->description); ?>" />

	<?php printMetadata(MULTIMEDIA_PODCAST_METADATA_TABLE, MULTIMEDIA_PODCAST_CATEGORIES_TABLE, $podcast->id, $podcast->title, "http://".DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>	
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
<?php
if ($podcast->id < 1) {
?>
	<h2>Sorry, this podcast is no longer available</h2>
<?php
}
else {
?>
	<div class="byEditor">

<?php
		if ($podcast->imageURL != '') { 
			if (mb_strlen(getImageProperty($podcast->imageURL, 'longdesc')) > 0)  {
?>
	<div class="figcaption">
		<img src="<?php print getStaticContentRootURL();?>/images/<?php print $podcast->imageURL; ?>" alt="<?php print encodeHtml(getImageProperty($podcast->imageURL, 'altText')); ?>" class="main_image"/>
		<p><?php print encodeHtml(getImageProperty($podcast->imageURL, 'longdesc')); ?></p>
	</div>
<?php
			}
			else {
?>
	<img class="floatRight main_image" src="<?php print getStaticContentRootURL();?>/images/<?php print $podcast->imageURL; ?>" alt="<?php print getImageProperty($podcast->imageURL, 'altText'); ?>" />
<?php
			}
		}
?>
		<?php print processEditorContent($podcast->description); ?>
	</div><ul class="archive">
<?php
	if ($numEpisodes > 0) {
		foreach ($allEpisodes as $index => $episode) { ?><li><?php
			$item = $episode->getItem();
			if ($item) {
				if ($index == 0 && $currentPage == 1) {
?>
	

		<h3><a href="<?php print getSiteRootURL() . buildMultimediaPodcastsURL(-1, $podcast->id, $episode->id); ?>"><?php print encodeHtml($episode->title); ?></a></h3>
		
<?php
		if ($episode->imageURL != '') { 
?>
		
		<a href="<?php print getSiteRootURL() . buildMultimediaPodcastsURL(-1, $podcast->id, $episode->id); ?>">
			<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($episode->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($episode->imageURL, 'altText')); ?>" class="main_image" />
		</a>
		
<?php 
		} 
?>		

<?php 
	}
	else {

		if ($episode->imageURL != '') { 
?>
		
		<a href="<?php print getSiteRootURL() . buildMultimediaPodcastsURL(-1, $podcast->id, $episode->id); ?>">
			<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($episode->imageURL);?>" alt="<?php print encodeHtml(getImageProperty($episode->imageURL, 'altText')); ?>" class="contentimage" />
		</a>
		
<?php 
		} 
?>
		<h3><a href="<?php print getSiteRootURL() . buildMultimediaPodcastsURL(-1, $podcast->id, $episode->id); ?>"><?php print encodeHtml($episode->title); ?></a></h3>
<?php
	}
?>
		<p class="date">Published: <?php print formatDateTime(FORMAT_DATE_FULL, $episode->dateCreated);?></p>
		<p><?php print encodeHtml($episode->summary); ?></p>
		<p>Running time: <?php print secondsToTimecode($item->length); ?></p>
		<p><a href="<?php print getSiteRootURL() . buildMultimediaPodcastsURL(-1, $podcast->id, $episode->id); ?>"><?php print $item->isAudio() ? 'Listen' : 'Watch'; ?> now</a></p>
	
<?php
		} 
?></li>
<?php
	} 
?>
</ul>

<?php
	if ($currentPage != 1 || $currentPage != $pageCount) {
?>
	<ul class="page_nav">
<?php
	// Previous page
	if ($currentPage != 1) {
		$previousPage = $currentPage - 1;
		print '<li><a href="' . getSiteRootURL() . buildMultimediaPodcastsURL(-1, $podcast->id) . '?currentPage=' . (int) $previousPage . '&amp;itemsPerPage=' . (int) $itemsPerPage . '">&laquo; Previous page</a></li>';
		if ($currentPage != $pageCount) {
			print ' | ';
		}
	}

	// Next page
	if ($currentPage != $pageCount) {
		$nextPage = $currentPage + 1;
		print '<li><a href="' . getSiteRootURL() . buildMultimediaPodcastsURL(-1, $podcast->id) . '?currentPage=' . (int) $nextPage . '&amp;itemsPerPage=' . (int) $itemsPerPage . '">Next page &raquo;</a></li>';
	}
?>
	</ul>
<?php
	}
}
if ($podcast->downloadable) {
?>
	<ul class="bottomList">
			<li><a href="itpc://<?php print DOMAIN . buildRSSURL('podcasts', $podcast->id); ?>">iTunes</a></li>	
			<li><a href="zcast://<?php print DOMAIN . buildRSSURL('podcasts', $podcast->id); ?>">ZENCast</a></li>	  
			<li><a href="zune://subscribe/?<?php print encodeHtml($podcast->title); ?>=<?php print DOMAIN . buildRSSURL('podcasts', $podcast->id); ?>">Zune</a></li>	 
			<li><a href="<?php print getSiteRootURL() . buildAboutPodcastRSSURL(); ?>">About podcast feeds</a></li> 
	</ul>
	
	<div class="clear"></div>
			<p><a class="rss" href="<?php print getSiteRootURL() . buildRSSURL('podcasts', $podcast->id); ?>" >RSS</a></p>
			
	
<?php
}
}
?>


<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>