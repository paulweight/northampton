<?php
	include_once("websections/JaduRSS.php");
	include_once("JaduLibraryFunctions.php");
	include_once("JaduConstants.php");

	$MAX_ITEMS = 20;
	
	$rssChannel = new RSSChannel();

	if (isset($_GET['events'])) {
	    include_once("websections/JaduEvents.php");
	    $events = getNumEvents($MAX_ITEMS);
	    $rssChannel->title = $DOMAIN . " Latest Events";
		$rssChannel->description = encodeXML(METADATA_GENERIC_COUNCIL_NAME . " Latest events");
		$rssChannel->link = 'http://' . $DOMAIN . '/site/scripts/events_index.php';

    	foreach ($events as $event) {
    	    $rssItem = new RSSItem();
			$rssItem->title = encodeXML($event->title);
			$rssItem->link = 'http://' . $DOMAIN . '/site/scripts/events_info.php?eventID=' . $event->id;
			$rssItem->description = encodeXML($event->summary);
			$rssItem->pubDate = date("r", $event->dateCreated);

			$rssChannel->addItem($rssItem);
		}
	}
	elseif (isset($_GET['forms'])) {
	    include_once("egov/JaduXFormsForm.php");
	    $forms = getXMostRecentlyCreatedXFormsForms($MAX_ITEMS);
	    $rssChannel->title = $DOMAIN . " Latest Forms";
		$rssChannel->description = encodeXML(METADATA_GENERIC_COUNCIL_NAME . " Latest forms");
		$rssChannel->link = 'http://' . $DOMAIN . '/site/scripts/xforms_index.php';

    	foreach ($forms as $form) {
    	    $rssItem = new RSSItem();
			$rssItem->title = encodeXML($form->title);
			$rssItem->link = 'http://' . $DOMAIN . '/site/scripts/xforms_form.php?formID=' . $form->id;
			$rssItem->description = '';
			$rssItem->pubDate = date("r", $form->enterDate);

			$rssChannel->addItem($rssItem);
		}
	}
	elseif (isset($_GET['downloads'])) {
	    include_once("websections/JaduDownloads.php");
	    $downloads = getXMostRecentlyCreatedDownloadFiles($MAX_ITEMS);
	    $rssChannel->title = $DOMAIN . " Latest Downloads";
		$rssChannel->description = encodeXML(METADATA_GENERIC_COUNCIL_NAME . " Latest downloads");
		$rssChannel->link = 'http://' . $DOMAIN . '/site/scripts/downloads_index.php';

    	foreach ($downloads as $download) {
    	    $rssItem = new RSSItem();
			$rssItem->title = encodeXML($download->title);
			$rssItem->link = 'http://' . $DOMAIN . '/site/scripts/download_info.php?fileID=' . $download->id;
			$rssItem->description = '';
			$rssItem->pubDate = date("r", $download->creationDate);

			$rssChannel->addItem($rssItem);
		}
	}
	elseif (isset($_GET['jobs']) && file_exists($JADU_HOME . 'recruitment/JaduRecruitmentJobs.php')) {
	    include_once("recruitment/JaduRecruitmentJobs.php");
	    $jobs = getLatestLiveJobs ($MAX_ITEMS, true);
	    $rssChannel->title = $DOMAIN . " Latest Jobs";
		$rssChannel->description = encodeXML(METADATA_GENERIC_COUNCIL_NAME . " Latest jobs");
		$rssChannel->link = 'http://' . $DOMAIN . '/site/scripts/recruit_jobs.php';

    	foreach ($jobs as $job) {
    	    $rssItem = new RSSItem();
			$rssItem->title = encodeXML($job->title);
			$rssItem->link = 'http://' . $DOMAIN . '/site/scripts/recruit_details.php?id=' . $job->id;
			$rssItem->description = encodeXML($job->description);
			$rssItem->pubDate = date("r", $dateCreated->creationDate);

			$rssChannel->addItem($rssItem);
		}
	}
	elseif (isset($_GET['podcast'])) {
	    include_once('multimedia/JaduMultimediaRSS.php');
    	include_once('multimedia/JaduMultimediaPodcasts.php');
    	include_once('utilities/JaduAdministrators.php');
    	
    	if (!$podcast = getMultimediaPodcast($_GET['podcast'], array('live' => true))) {
		    header('HTTP/1.0 404 Not Found');
		    exit();
		}
		
		// Forbidden is not downloadable
		if (!$podcast->downloadable) {
		    header('HTTP/1.1 403 Forbidden');
		    exit();
		}
		
    	if ($PLATFORM == LINUX) {
    		$imageDirectory = $SECURE_SERVER . "/images/";
    	}
    	else {
    		$imageDirectory = 'http://' . $DOMAIN . "/images/";
    	}

    	$owner = getAdministrator($podcast->ownerID);

    	$rssChannel = new MultimediaRSSChannel(false);
    	$rssChannel->title = $podcast->title;
    	$rssChannel->link = 'http://' . $DOMAIN . '/site/scripts/podcast_info.php?podcastID=' . $podcast->id;
    	$rssChannel->description = $podcast->summary;
    	$rssChannel->pubDate = $podcast->dateCreated;
    	if (!empty($podcast->imageURL)) {
    	    $rssChannel->image = array(
        	    'url' => $imageDirectory . $podcast->imageURL,
        	    'title' => $podcast->title,
        	    'link' => 'http://' . $DOMAIN . '/site/scripts/podcast_info.php?podcastID=' . $podcast->id
        	);
    	}
    	$rssChannel->itunes = array(
    	    'summary' => $podcast->summary,
    	    'subtitle' => $podcast->summary,
    	    'author' => METADATA_GENERIC_COUNCIL_NAME,
    	    'owner' => array(
    	        'name' => $owner->name,
    	        'email' => $owner->email
    	    ),
    	    'keywords' => '',
    	    'explicit' => '',
    	    'image' => !empty($podcast->imageURL) ? $imageDirectory . $podcast->imageURL : null
    	);

    	$episodes = getAllMultimediaPodcastEpisodes($podcast->id, array('live' => true));

    	if ($episodes) {
    	    foreach ($episodes as $episode) {
    	        $item = $episode->getItem();

    			$rssItem = new MultimediaRSSItem();
    			$rssItem->title = $episode->title;
    			$rssItem->link = 'http://' . $DOMAIN . '/site/scripts/podcast_episode.php?podcastID=' . $podcast->id . '&amp;episodeID=' . $episode->id;
    			$rssItem->description = $episode->summary;
    			$rssItem->enclosure = array(
                    'url' => 'http://' . $DOMAIN . $item->getDownloadFilename(),
        			'length' => $item->filesize,
        			'type' => ($item->isVideo() ? 'video/mp4' : 'audio/mpeg3')
    			);
    			$rssItem->guid = $episode->id;
    			$rssItem->pubDate = $episode->dateCreated;
    			$rssItem->itunes = array(
    				'summary' => $episode->summary,
    				'subtitle' => $episode->summary,
    				'author' => METADATA_GENERIC_COUNCIL_NAME,
    				'duration' => $item->length,
            	    'explicit' => '',
            	    'image' => !empty($episode->imageURL) ? $imageDirectory . $episode->imageURL : null
    			);
    			$rssChannel->addItem($rssItem);
    	    }
    	}
	}
	elseif (isset($_GET['gallery'])) {
    	include_once('multimedia/JaduMultimediaGalleries.php');
    	include_once('utilities/JaduAdministrators.php');
		include_once('JaduImages.php');
    	
    	if (!$gallery = getMultimediaGallery($_GET['gallery'], array('live' => true))) {
		    header('HTTP/1.0 404 Not Found');
		    exit();
		}
		
    	if ($PLATFORM == LINUX) {
    		$imageDirectory = $SECURE_SERVER . "/images/";
    	}
    	else {
    		$imageDirectory = 'http://' . $DOMAIN . "/images/";
    	}

    	$owner = getAdministrator($podcast->ownerID);

    	$rssChannel = new RSSChannel(false);
    	$rssChannel->title = $gallery->title;
    	$rssChannel->link = 'http://' . $DOMAIN . '/site/scripts/gallery_info.php?galleryID=' . $gallery->id;
    	$rssChannel->description = $gallery->summary;
    	$rssChannel->pubDate = $gallery->dateCreated;

		$items = $gallery->getItems();
		if ($items) {
			foreach ($items as $item) {
				if ($image = getImage($item->imageID)) {
	    			$rssItem = new RSSItem();
	    			$rssItem->title = $item->title;
	    			$rssItem->link = 'http://' . $DOMAIN . '/site/scripts/gallery_item.php?galleryID=' . $gallery->id . '&amp;itemID=' . $item->id;
	    			$rssItem->enclosure = array(
	                    'url' => 'http://' . $DOMAIN . '/images/' . $image->filename,
	        			'length' => $item->filesize
	    			);
	    			$rssItem->guid = $item->id;
	    			$rssChannel->addItem($rssItem);
				}
			}
		}
	}
	else {
	    include_once("websections/JaduNews.php");
		$rssChannel->title = $DOMAIN . " Latest News";
		$rssChannel->description = encodeXML(METADATA_GENERIC_COUNCIL_NAME . " Latest news");
		$rssChannel->link = 'http://' . $DOMAIN . '/site/scripts/news_index.php';

    	$newsList = getAllNewsByDateLimited($MAX_ITEMS, true, true);

    	foreach ($newsList as $news) {
    	    $rssItem = new RSSItem();
			$rssItem->title = encodeXML($news->title);
			$rssItem->link = 'http://' . $DOMAIN . '/site/scripts/news_article.php?newsID=' . $news->id;
			$news->summary = str_replace('Õ', "'", $news->summary);
			$news->summary = str_replace('£', '&#163;', $news->summary);
			$rssItem->description = encodeXML($news->summary);
			$rssItem->pubDate = $news->getNewsDateISO8601();
			$rssChannel->addItem($rssItem);
		}
	}

	$xml = createRSSString($rssChannel);

	header("Content-type: text/xml; charset=utf-8");
	$xml = str_replace('&nbsp;', '', $xml);
	print $xml;
	exit();
?>