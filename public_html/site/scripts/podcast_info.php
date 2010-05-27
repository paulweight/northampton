<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("multimedia/JaduMultimediaPodcasts.php");
	include_once("JaduImages.php");
	include_once("JaduAppliedCategories.php");
	include_once("JaduMetadata.php");
	
	if (isset($_GET['podcastID']) && is_numeric($_GET['podcastID'])) {
		$podcast = getMultimediaPodcast($_GET['podcastID'], array('live' => true));
		$episodes = getAllMultimediaPodcastEpisodes($podcast->id, array('live' => true));
	}
	else {
		header("Location: podcast_index.php");
		exit;
	}
	
	if (!$podcast) {
	    $dirTree = array();
	}
	else {
		$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		$categoryID = getFirstCategoryIDForItemOfType (MULTIMEDIA_PODCAST_CATEGORIES_TABLE, $podcast->id, BESPOKE_CATEGORY_LIST_NAME);	
		$currentCategory = $lgclList->getCategory($categoryID);
		$dirTree = $lgclList->getFullPath($categoryID);
		
		$numEpisodes = getNumMultimediaPodcastEpisodes($podcast->id);

    	// Set the current page
    	if (!isset($_GET['currentPage']) || $_GET['currentPage'] < 1) {
    		$currentPage = 1;
    	}
    	else {
    		$currentPage = $_GET['currentPage'];
    	}
    	
    	$itemsPerPage = 5;
    	$offset = (($currentPage-1) * $itemsPerPage);
    	$pageCount = ceil($numEpisodes / $itemsPerPage);
    	if ($offset > $numEpisodes) {
    		$offset = $numEpisodes - $itemsPerPage;
    		$currentPage = $pageCount;
    	}
    	if ($currentPage < $pageCount) {
    		$nextPage = $currentPage + 1;
    	}
    	if ($currentPage > 1) {
    		$previousPage = $currentPage - 1;
    	}

    	$criteria = array(
    	    'live' => true,
    		'orderBy' => 'episode.dateCreated',
    		'orderDir' => 'DESC',
    		'limit' => $itemsPerPage,
    		'offset' => $offset
    	);

    	$allEpisodes = getAllMultimediaPodcastEpisodes($podcast->id, $criteria);
	}
	
	$breadcrumb = 'podcastInfo';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $podcast->title;?> | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="news, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />	
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Podcasts - <?php print $podcast->title;?>" />

	<?php printMetadata(MULTIMEDIA_METADATA_TABLE, MULTIMEDIA_METADATA_TABLE, $podcast->id, $podcast->title, "http://".$DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>
	
	<script type="text/javascript" src="site/javascript/global.js"></script>	
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
<?php
if (!isset($podcast) || !$podcast) {
?>
	<h2>Sorry, this podcast is no longer available</h2>
<?php
}
else {
?>
	<div class="byEditor">
		<?php print $podcast->description; ?>
    </div>
<?php
if ($numEpisodes > 0) {
foreach ($allEpisodes as $index => $episode) {
	$item = $episode->getItem();
    if ($index == 0 && $currentPage == 1) {
?>
    <div class="lead">
    	<h2><a href="http://<?php print $DOMAIN; ?>/site/scripts/podcast_episode.php?podcastID=<?php print $podcast->id;?>&amp;episodeID=<?php print $episode->id;?>"><?php print $episode->title;?></a></h2>
<?php 
        if ($episode->imageURL != '') { 
?>
		<a href="http://<?php print $DOMAIN; ?>/site/scripts/podcast_episode.php?podcastID=<?php print $podcast->id;?>&amp;episodeID=<?php print $episode->id;?>">
		    <img src="http://<?php print $DOMAIN;?>/images/<?php print $episode->imageURL;?>" alt="<?php print getImageProperty($episode->imageURL, 'altText'); ?>" class="main_image" />
		</a>
<?php 
        } 
    }
    else {
?>
    <div class="search_result">
    	<h3><a href="http://<?php print $DOMAIN; ?>/site/scripts/podcast_episode.php?podcastID=<?php print $podcast->id;?>&amp;episodeID=<?php print $episode->id;?>"><?php print $episode->title;?></a></h3>
<?php 
        if ($episode->imageURL != '') { 
?>
		<a href="http://<?php print $DOMAIN; ?>/site/scripts/podcast_episode.php?podcastID=<?php print $podcast->id;?>&amp;episodeID=<?php print $episode->id;?>">
		    <img src="http://<?php print $DOMAIN;?>/images/<?php print $episode->imageURL;?>" alt="<?php print getImageProperty($episode->imageURL, 'altText'); ?>" class="contentimage" />
		</a>
<?php 
        } 
    }
?>
        <p class="details">Running time: <?php print secondsToTimecode($item->length); ?></p>
    	<p class="date">Published: <?php print date("l jS F Y", $episode->dateCreated);?></p>
    	<p><?php print $episode->summary;?></p>
		<p><a href="http://<?php print $DOMAIN; ?>/site/scripts/podcast_episode.php?podcastID=<?php print $podcast->id;?>&amp;episodeID=<?php print $episode->id;?>">Listen now</a></p>
    </div>
<?php
}
?>
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
if ($podcast->downloadable) {
?>
    <div class="plain_box">
    	<p class="first">
    	    <a href="http://<?php print $DOMAIN;?>/site/scripts/rss.php?podcast=<?php print $podcast->id;?>" target="_blank"><img src="http://<?php print $DOMAIN;?>/site/images/xml.gif" alt="Get this feed" /> Podcast feed</a> | 
    	    <a href="itpc://<?php print $DOMAIN;?>/site/scripts/rss.php?podcast=<?php print $podcast->id;?>" target="_blank"><img src="http://<?php print $DOMAIN;?>/site/images/podcast.gif" alt="Subscribe in iTunes" /> Subscribe with iTunes</a> | 
    	    <a href="http://<?php print $DOMAIN;?>/site/scripts/rss_podcast_about.php">About podcast feeds</a>.
    	</p>
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