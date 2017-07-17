<?php
	include_once("websections/JaduRSS.php");
	include_once("JaduLibraryFunctions.php");
	include_once("JaduConstants.php");
	include_once("utilities/JaduReadableURLs.php");

	$MAX_ITEMS = 20;
	
	$rssChannel = new RSSChannel();

	if (isset($_GET['events'])) {
		include_once("websections/JaduEvents.php");
		include_once("egov/JaduEGovMeetingMinutes.php");
		$events = array_merge(getNumEvents($MAX_ITEMS), getMeetingsAsEvents($MAX_ITEMS));
		usort($events, 'sortEventsByStartDate');
		$events = array_slice($events, 0, $MAX_ITEMS);
		
	    $rssChannel->title = DOMAIN . " Latest Events";
		$rssChannel->description = encodeXML(METADATA_GENERIC_NAME . " Latest events");
		$rssChannel->link = getSiteRootURL() . buildEventsURL();
		$rssChannel->href = getSiteRootURL() . buildRSSURL('events');
		
    	foreach ($events as $event) {
    	    $rssItem = new RSSItem();
			$rssItem->title = encodeXML($event->title);
			if ($event->isMeeting) {
				$rssItem->link = getSiteRootURL() . buildMeetingsURL(-1, 'meeting', $event->id, -1, true);
			}
			else {
				$rssItem->link = getSiteRootURL() . buildEventsURL(-1, '', $event->id);
			}
			
			$rssItem->description = encodeXML($event->summary);
			$rssItem->pubDate = date("r", $event->startDate);

			$rssChannel->addItem($rssItem);
		}
	}
	elseif (isset($_GET['forms'])) {
	    include_once("egov/JaduXFormsForm.php");
	    $forms = getXMostRecentlyCreatedXFormsForms($MAX_ITEMS);
	    $rssChannel->title = DOMAIN . " Latest Forms";
		$rssChannel->description = encodeXML(METADATA_GENERIC_NAME . " Latest forms");
		$rssChannel->link = getSiteRootURL() . buildXFormsURL();
		$rssChannel->href = getSiteRootURL() . buildRSSURL('forms');

    	foreach ($forms as $form) {
    	    $rssItem = new RSSItem();
			$rssItem->title = encodeXML($form->title);
			$rssItem->link = getSiteRootURL() . buildXFormsURL($form->id);
			$rssItem->description = '';
			$rssItem->pubDate = date("r", $form->enterDate);

			$rssChannel->addItem($rssItem);
		}
	}
	elseif (isset($_GET['downloads'])) {
	    include_once("websections/JaduDownloads.php");
	    $downloads = getXMostRecentlyCreatedDownloadFiles($MAX_ITEMS);
	    $rssChannel->title = DOMAIN . " Latest Downloads";
		$rssChannel->description = encodeXML(METADATA_GENERIC_NAME . " Latest downloads");
		$rssChannel->link = getSiteRootURL() . buildDownloadsURL();
		$rssChannel->href = getSiteRootURL() . buildRSSURL('downloads');

    	foreach ($downloads as $download) {
    	    $rssItem = new RSSItem();
			$rssItem->title = encodeXML($download->title);
			$rssItem->link = getSiteRootURL() . buildDownloadsURL(-1, $download->id);
			$rssItem->description = '';
			$rssItem->pubDate = date("r", $download->creationDate);

			$rssChannel->addItem($rssItem);
		}
	}
	elseif (isset($_GET['jobs']) && file_exists($JADU_HOME . 'recruitment/JaduRecruitmentJobs.php')) {
	    include_once("recruitment/JaduRecruitmentJobs.php");
	    $jobs = getLatestLiveJobs ($MAX_ITEMS, true);
	    $rssChannel->title = DOMAIN . " Latest Jobs";
		$rssChannel->description = encodeXML(METADATA_GENERIC_NAME . " Latest jobs");
		$rssChannel->link = getSiteRootURL() . '/site/scripts/recruit_jobs.php';

    	foreach ($jobs as $job) {
    	    $rssItem = new RSSItem();
			$rssItem->title = encodeXML($job->title);
			$rssItem->link = getSiteRootURL() . '/site/scripts/recruit_details.php?id=' . $job->id;
			$rssItem->description = encodeXML($job->description);
			$rssItem->pubDate = date("r", $job->creationDate);

			$rssChannel->addItem($rssItem);
		}
	}
	elseif (isset($_GET['podcasts'])) {
	    include_once('multimedia/JaduMultimediaRSS.php');
    	include_once('multimedia/JaduMultimediaPodcasts.php');
    	include_once('utilities/JaduAdministrators.php');
    	
    	if ($_GET['podcasts'] < 1) {
    	    $podcasts = getAllMultimediaPodcasts(array(
    	        'live' => true, 
    	        'orderBy' => 'podcast.dateCreated',
    	        'orderDir' => 'DESC',
    	        'limit' => $MAX_ITEMS
    	    ));
    	    
    	    $rssChannel->title = DOMAIN . " Latest Podcasts";
    		$rssChannel->description = encodeXML(METADATA_GENERIC_NAME . " Latest Podcasts");
    		$rssChannel->link = getSiteRootURL() . buildMultimediaPodcastsURL();
    		$rssChannel->href = getSiteRootURL() . buildRSSURL('podcasts');
    	    
    	    foreach ($podcasts as $podcast) {
        	    $rssItem = new RSSItem();
    			$rssItem->title = $podcast->title;
    			$rssItem->link = getSiteRootURL() . buildMultimediaPodcastsURL(-1, $podcast->id);
    			$rssItem->description = encodeXML($podcast->description);
    			$rssItem->pubDate = date("r", $podcast->dateCreated);
                
    			$rssChannel->addItem($rssItem);
    	    }
    	}
    	else {
        	if (!$podcast = getMultimediaPodcast($_GET['podcasts'], array('live' => true))) {
    		    header('HTTP/1.0 404 Not Found');
    		    exit();
    		}
		
    		// Forbidden is not downloadable
    		if (!$podcast->downloadable) {
    		    header('HTTP/1.1 403 Forbidden');
    		    exit();
    		}
		
       		$imageDirectory = $SECURE_SERVER . "/images/";

        	$owner = getAdministrator($podcast->ownerID);

        	$rssChannel = new MultimediaRSSChannel(false);
        	$rssChannel->title = $podcast->title;
        	$rssChannel->link = getSiteRootURL() . buildMultimediaPodcastsURL(-1, $podcast->id);
        	$rssChannel->href = getSiteRootURL() . buildRSSURL('podcasts', $podcast->id);
        	$rssChannel->description = $podcast->summary;
        	$rssChannel->pubDate = $podcast->dateCreated;
        	if (!empty($podcast->imageURL)) {
        	    $rssChannel->image = array(
            	    'url' => $imageDirectory . $podcast->imageURL,
            	    'title' => $podcast->title,
            	    'link' => getSiteRootURL() . buildMultimediaPodcastsURL(-1, $podcast->id)
            	);
        	}
        	$rssChannel->itunes = array(
        	    'summary' => $podcast->summary,
        	    'subtitle' => $podcast->summary,
        	    'author' => METADATA_GENERIC_NAME,
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
        			$rssItem->link = getSiteRootURL() . buildMultimediaPodcastsURL(-1, $podcast->id, $episode->id);
        			$rssItem->description = $episode->summary;
        			$rssItem->enclosure = array(
                        'url' => getSiteRootURL() . $item->getDownloadFilename(),
            			'length' => $item->filesize,
            			'type' => ($item->isVideo() ? 'video/mp4' : 'audio/mpeg3')
        			);
        			$rssItem->guid = $episode->id;
        			$rssItem->pubDate = $episode->dateCreated;
        			$rssItem->itunes = array(
        				'summary' => $episode->summary,
        				'subtitle' => $episode->summary,
        				'author' => METADATA_GENERIC_NAME,
        				'duration' => $item->length,
                	    'explicit' => '',
                	    'image' => !empty($episode->imageURL) ? $imageDirectory . $episode->imageURL : null
        			);
        			$rssChannel->addItem($rssItem);
        	    }
        	}
        }
	}
	elseif (isset($_GET['galleries'])) {
    	include_once('multimedia/JaduMultimediaGalleries.php');
    	include_once('utilities/JaduAdministrators.php');
		include_once('JaduImages.php');
		
		if ($_GET['galleries'] < 1) {
    	    $galleries = getAllMultimediaGalleries(array(
    	        'live' => true, 
    	        'orderBy' => 'gallery.dateCreated',
    	        'orderDir' => 'DESC',
    	        'limit' => $MAX_ITEMS
    	    ));
    	    
    	    $rssChannel->title = DOMAIN . " Latest Gallery";
    		$rssChannel->description = encodeXML(METADATA_GENERIC_NAME . " Latest Gallery");
    		$rssChannel->link = getSiteRootURL() . buildMultimediaGalleriesURL();
    		$rssChannel->href = getSiteRootURL() . buildRSSURL('galleries');
    	    
    	    foreach ($galleries as $gallery) {
        	    $rssItem = new RSSItem();
    			$rssItem->title = $gallery->title;
    			$rssItem->link = getSiteRootURL() . buildMultimediaGalleriesURL(-1, $gallery->id);
    			$rssItem->description = encodeXML($gallery->description);
    			$rssItem->pubDate = date("r", $gallery->dateCreated);
                
    			$rssChannel->addItem($rssItem);
    	    }
    	}
    	else {
        	if (!$gallery = getMultimediaGallery($_GET['galleries'], array('live' => true))) {
    		    header('HTTP/1.0 404 Not Found');
    		    exit();
    		}
		
       		$imageDirectory = $SECURE_SERVER . "/images/";

        	$owner = getAdministrator($podcast->ownerID);

        	$rssChannel = new RSSChannel(false);
        	$rssChannel->title = $gallery->title;
        	$rssChannel->link = getSiteRootURL() . buildMultimediaGalleriesURL(-1, $gallery->id);
        	$rssChannel->href = getSiteRootURL() . buildRSSURL('galleries', $gallery->id);
        	$rssChannel->description = $gallery->summary;
        	$rssChannel->pubDate = $gallery->dateCreated;

    		$items = $gallery->getItems();
    		if ($items) {
    			foreach ($items as $item) {
    				if ($image = getImage($item->imageID)) {
    	    			$rssItem = new RSSItem();
    	    			$rssItem->title = $item->title;
    	    			$rssItem->link = getSiteRootURL() . buildMultimediaGalleriesURL(-1, $gallery->id, $item->id);
    	    			$rssItem->guid = $item->id;
    	    			$rssChannel->addItem($rssItem);
    				}
    			}
    		}
    	}
	}
	else if (isset($_GET['press'])) {
	    include_once("websections/JaduPressReleases.php");
		$rssChannel->title = DOMAIN . " Latest Press Releases";
		$rssChannel->description = encodeXML(METADATA_GENERIC_NAME . " Latest press releases");
		$rssChannel->link = getSiteRootURL() . buildPressURL();
		$rssChannel->href = getSiteRootURL() . buildRSSURL('press');

    	$pressList = getAllPressReleasesByDateLimited($MAX_ITEMS, true, true);

    	foreach ($pressList as $pressRelease) {
    	    $rssItem = new RSSItem();
			$rssItem->title = encodeXML($pressRelease->title);
			$rssItem->link = getSiteRootURL() . buildPressArticleURL($pressRelease->id);
			$rssItem->description = encodeXML($pressRelease->summary);
			$rssItem->pubDate = date('r', $pressRelease->pressDate);

			$rssChannel->addItem($rssItem);
		}
	}
	else {
	    include_once("websections/JaduNews.php");
		$rssChannel->title = DOMAIN . " Latest News";
		$rssChannel->description = encodeXML(METADATA_GENERIC_NAME . " Latest news");
		$rssChannel->link = getSiteRootURL() . buildNewsURL();
		$rssChannel->href = getSiteRootURL() . buildRSSURL('news');

    	$newsList = getAllNewsByDateLimited($MAX_ITEMS, true, true);

    	foreach ($newsList as $news) {
    	    $rssItem = new RSSItem();
			$rssItem->title = encodeXML($news->title);
			$rssItem->link = getSiteRootURL() . buildNewsArticleURL($news->id);
			$rssItem->description = encodeXML($news->summary);
			$rssItem->pubDate = date('r', $news->newsDate);

			$rssChannel->addItem($rssItem);
		}
	}

	$xml = createRSSString($rssChannel);

	header("Content-type: text/xml");
	$xml = str_replace('&nbsp;', '', $xml);
	print $xml;
?>