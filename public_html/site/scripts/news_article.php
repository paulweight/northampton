<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduNews.php");
	include_once("JaduAppliedCategories.php");
	include_once("JaduMetadata.php");
	include_once("websections/JaduFeedManager.php");
	include_once("utilities/JaduMostPopular.php");

	// Validate inputs
	if (!is_numeric($jadu->getInput()->get('newsID'))) {
		header('Location: ' . buildNewsURL());
		exit;
	}
	
	// Default to approved/live
	$approvedOnly = true;
	$liveOnly = true;
	
	// Check whether an administrator is previewing the page
	$allowPreview =  (isset($isPreviewLink) && $isPreviewLink && isset($allowPreview) && $allowPreview);
	if($allowPreview) {
		$approvedOnly = $liveOnly = false;
	}

	if (isset($_GET['newsID'])) {
		$news = getNews($_GET['newsID'], $liveOnly, $approvedOnly);
	}
	else {
		$news = getTopNews($liveOnly, $approvedOnly);
		if ($news->id < 1) {
			$news = getLastNews($liveOnly, $approvedOnly);
		}
	}

	//if the news item doesnt exist, re-direct to index
	if ($news->id < 1) {
		header('HTTP/1.0 404 Not Found');
		header('Location: ' . buildNewsURL());
		exit;
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = $news->title;
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildNewsURL() .'" >Latest news</a></li><li><span>'. encodeHtml($news->title) .'</span></li>';
	
	include("news_article.html.php");
?>