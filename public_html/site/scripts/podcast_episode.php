<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("multimedia/JaduMultimediaPodcasts.php");
	include_once("multimedia/JaduMultimediaPodcastEpisodes.php");
	include_once("multimedia/JaduMultimediaItems.php");
	include_once("JaduAppliedCategories.php");
	include_once("JaduMetadata.php");
	
	if (isset($_GET['episodeID']) && is_numeric($_GET['episodeID'])) {
	
		$episode = getMultimediaPodcastEpisode($_GET['episodeID'], array('live' => true));
		if ($episode) {
		    if (!$item = $episode->getItem()) {
		        $episode = false;
		    }
		}

        if ($episode) {
            $podcast = getMultimediaPodcast($episode->podcastID, array('live' => true));
    		if ($podcast) {
    			$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
    			$categoryID = getFirstCategoryIDForItemOfType (MULTIMEDIA_PODCAST_CATEGORIES_TABLE, $podcast->id, BESPOKE_CATEGORY_LIST_NAME);	
    			$currentCategory = $lgclList->getCategory($categoryID);
    			$dirTree = $lgclList->getFullPath($categoryID);
    		}
        }
	}
	else {
		header("Location: " . buildMultimediaPodcastsURL());
		exit;
	}
	
	if (!isset($podcast) || !$podcast) {
	    $podcast = new MultimediaPodcast();
	    $dirTree = array();
	}
	
	if (!isset($episode) || !$episode) {
	    $episode = new MultimediaPodcastEpisode();
	}
	
	$maxSize = 680;
	
	// Breadcrumb, H1 and Title
	if ($episode->id == '-1'){
		$MAST_HEADING = 'This podcast is no longer available';
	}
	else {
		$MAST_HEADING = $podcast->title;
	}
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildMultimediaPodcastsURL() . '">Podcasts</a></li>';
	foreach ($dirTree as $parent) {
		$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMultimediaPodcastsURL($parent->id) . '">'. encodeHtml($parent->name) .'</a></li>';
	}
	$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMultimediaPodcastsURL(-1, $podcast->id) . '">'. encodeHtml($podcast->title) .'</a></li>';
	$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($episode->title) .'</span></li>';
	
	include("podcast_episode.html.php");
?>