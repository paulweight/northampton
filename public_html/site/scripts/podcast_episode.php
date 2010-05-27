<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("multimedia/JaduMultimediaPodcasts.php");
	include_once("multimedia/JaduMultimediaPodcastEpisodes.php");
	include_once("multimedia/JaduMultimediaItems.php");
	include_once("JaduAppliedCategories.php");
	include_once("JaduMetadata.php");
	
	if (isset($_GET['podcastID']) && is_numeric($_GET['podcastID']) && isset($_GET['episodeID']) && is_numeric($_GET['episodeID'])) {
		$podcast = getMultimediaPodcast($_GET['podcastID'], array('live' => true));
		if ($episode = getMultimediaPodcastEpisode($_GET['episodeID'], array('live' => true))) {
		    if (!$item = $episode->getItem()) {
		        $episode = false;
		    }
		}
		
		if (!$podcast) {
		    $dirTree = array();
		}
		else {
			$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
			$categoryID = getFirstCategoryIDForItemOfType (MULTIMEDIA_PODCAST_CATEGORIES_TABLE, $podcast->id, BESPOKE_CATEGORY_LIST_NAME);	
			$currentCategory = $lgclList->getCategory($categoryID);
			$dirTree = $lgclList->getFullPath($categoryID);
		}
	}
	else {
		header("Location: podcasts_index.php");
		exit;
	}
	
	if ($episode) {
	    multimediaPodcastEpisodeRequestMade($episode->id);
	    $episode->requests++;
	}
	
	$maxSize = 780;
	
	$breadcrumb = 'podcastEpisode';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $podcast->title;?> | <?php print $episode->title;?> |<?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="news, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />	
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Multimedia Podcasts - <?php print $podcast->title;?> - <?php print $episode->title;?>" />

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
else if (!isset($episode) || !$episode) { 
?>
	<h2>Sorry, this podcast episode is no longer available</h2>		
<?php
}
else {
?>
    <h2><?php print $episode->title; ?></h2>
    <p class="details">Running time: <?php print secondsToTimecode($item->length); ?></p>
	<p class="date">Published: <?php print date("l jS F Y", $episode->dateCreated);?></p>
	
	<div id="podcast_multimedia">
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
?>
        <div class="download_box">
<?php
        if ($podcast->downloadable) {
?>
            <p><a href="http://<?php print $DOMAIN . $item->getDownloadFilename(); ?>">Download this episode</a></p>
<?php
        }
?>
            <ul>
                <li>Viewed: <?php print $episode->requests . ' time' . ($episode->requests != 1 ? 's' : ''); ?> </li>
<?php
            if ($podcast->downloadable) {
?>
                <li>File size: <?php print humanReadableFilesize($item->filesize); ?></li>
                <li>Estimated download time: (56k = <?php print calculateDownloadTime($item->filesize, 57344) ?>)</li>
<?php
            }
?>
            </ul>
        </div>
    </div>
    
    <p class="first"><?php print $episode->summary;?></p>
    <div class="byEditor">
		<?php print $episode->description; ?>
	</div>
	
<?php
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

<!-- The Contact box -->
<?php include("../includes/contactbox.php"); ?>
<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>