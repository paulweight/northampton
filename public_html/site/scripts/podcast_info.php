<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("multimedia/JaduMultimediaPodcasts.php");
	include_once("JaduImages.php");
	include_once("JaduAppliedCategories.php");
	include_once("JaduMetadata.php");
	
	if (!isset($_GET['podcastID']) || !is_numeric($_GET['podcastID']) || $_GET['podcastID'] < 1) {
		header("Location: " . buildMultimediaPodcastsURL());
		exit;
	}
	
	$podcast = getMultimediaPodcast($_GET['podcastID'], array('live' => true));
	if ($podcast) {
		$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
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
			'approved' => true,
			'orderBy' => 'episode.dateCreated',
			'orderDir' => 'DESC',
			'limit' => $itemsPerPage,
			'offset' => $offset
		);

		$allEpisodes = getAllMultimediaPodcastEpisodes($podcast->id, $criteria);
	}
	else {
		$podcast = new MultimediaPodcast();
		$dirTree = array();
	}
	
	// Breadcrumb, H1 and Title
	if ($gallery->id == '-1'){
		$MAST_HEADING = 'This podcast is no longer available';
	}
	else {
		$MAST_HEADING = $podcast->title;
	}
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildMultimediaPodcastsURL() . '">Podcasts</a></li>';
	foreach ($dirTree as $parent) {
		$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMultimediaPodcastsURL($parent->id) .'" >'. encodeHtml($parent->name) .'</a></li>';
	}
	$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($podcast->title) .'</span></li>';

	include("podcast_info.html.php");
?>