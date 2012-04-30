<?php
	include_once("websections/JaduRSS.php");
	include_once("JaduLibraryFunctions.php");
	include_once("JaduConstants.php");
	include_once("websections/JaduNews.php");
	include_once("websections/JaduPressReleases.php");
	include_once("websections/JaduEvents.php");
	include_once("websections/JaduDownloads.php");
	include_once("websections/JaduDocumentHeaders.php");
	include_once("websections/JaduDocuments.php");
	include_once("websections/JaduDocumentPages.php");
	include_once("egov/JaduXFormsForm.php");
	include_once("utilities/JaduReadableURLs.php");

	define('MAX_WHATS_NEW', 10);

	$newsList = getAllNewsByDateLimited(MAX_WHATS_NEW, true, true);
	$events = getNumEvents(MAX_WHATS_NEW, 'dateCreated DESC', true);
	$downloads = getXMostRecentlyCreatedDownloadFiles(MAX_WHATS_NEW);
	$documents = getXMostRecentlyCreatedDocuments(MAX_WHATS_NEW, true, true);
	$forms = getXMostRecentlyCreatedXFormsForms(MAX_WHATS_NEW);
	$pressList = getAllPressReleasesByDateLimited(MAX_WHATS_NEW, true, true);

	$rssChannel = new RSSChannel();
	
	$rssChannel->title = DOMAIN . " Whats new";
	$rssChannel->description = encodeXML(METADATA_GENERIC_NAME . " Whats new");
	$rssChannel->link = getSiteRootURL() . buildWhatsNewURL();
	$rssChannel->href = getSiteRootURL() . buildRSSURL('whats_new');

	foreach ($newsList as $news) {
		$rssItem = new RSSItem();
		$rssItem->title = encodeXML('News: ' . $news->title);
		$rssItem->link = getSiteRootURL() . buildNewsArticleURL($news->id);
		$rssItem->description = encodeXML($news->summary);
		$rssItem->pubDate = date('r', $news->newsDate);

		$rssChannel->addItem($rssItem);
	}
	
	foreach ($downloads as $download) {
		$rssItem = new RSSItem();
		$rssItem->title = encodeXML('Download: ' . $download->title);
		$rssItem->link = getSiteRootURL() . buildDownloadsURL(-1, -1, $download->id);
		$rssItem->description = '';
		$rssItem->pubDate = date("r", $download->creationDate);

		$rssChannel->addItem($rssItem);
	}
	
	foreach($documents as $document) {
		
		//Get the document header
		$documentHeader = getDocumentHeader($document->headerOriginalID, true);
		
		$rssItem = new RSSItem();
		$rssItem->title = encodeXML('Document: ' . $documentHeader->title);
		$rssItem->link = getSiteRootURL() . buildDocumentsURL($document->id);
		
		//Get the first document page decription to display as the summary for the document
		$pageList = getAllDocumentPagesByDocumentWithLimit($document->id, 0, 1, true);

		$description = '';
		if (count($pageList) > 0) {
			$page = $pageList[1];
	
			$description = trim($page->description);
			$description = str_replace('<p></p>', '', $description);
			$description = str_replace('<p> </p>', '', $description);
			$description = str_replace('<p>&nbsp;</p>', '', $description);
			$charIndex = strpos($description, '</p>');

			if ($charIndex > 0) {
				$itemDescription = substr($description, 0, $charIndex + 4);
				if (strlen($description) > strlen($itemDescription)) {
					$description = str_replace('</p>', '...</p>', $itemDescription);
				}
			}
		}
		
		$rssItem->description = $description;
		$rssItem->pubDate = date("r", $document->enterDate);

		$rssChannel->addItem($rssItem);
	}

	foreach ($events as $event) {
		$rssItem = new RSSItem();
		$rssItem->title = encodeXML('Event: ' . $event->title);
		$rssItem->link = getSiteRootURL() . buildEventsURL(-1, '', $event->id);
		
		//Get the interval string if exists
		$intervalString = $event->getIntervalString();
		if ($intervalString != "") {
			$intervalString = "Interval: " . $intervalString . "<br />";
		}

		//Get the time string if exists
		$timeString = $event->getTimeString(); ;
		if ($timeString != "") {
			$timeString = "Time: " . $timeString . "<br />";
		}

		//Get the event dates
		$dateString = $event->getDateString("d F Y");

		$summary = $event->summary . '<br />Date: ' . $dateString . '<br />' . $intervalString . 'Location: ' . $event->location . '<br />' . $timeString;
		$rssItem->description = encodeXML($summary);
		$rssItem->pubDate = date("r", $event->dateCreated);

		$rssChannel->addItem($rssItem);
	}
	
	foreach ($forms as $form) {
		$rssItem = new RSSItem();
		$rssItem->title = encodeXML('Form: ' . $form->title);
		$rssItem->link = getSiteRootURL() . buildXFormsURL($form->id);
		$rssItem->description = encodeXML($form->instructions);
		$rssItem->pubDate = date("r", $form->enterDate);

		$rssChannel->addItem($rssItem);
	}
	
	foreach ($pressList as $pressRelease) {
		$rssItem = new RSSItem();
		$rssItem->title = encodeXML($pressRelease->title);
		$rssItem->link = getSiteRootURL() . buildPressArticleURL($pressRelease->id);
		$rssItem->description = encodeXML($pressRelease->summary);
		$rssItem->pubDate = date('r', $pressRelease->pressDate);

		$rssChannel->addItem($rssItem);
	}

	$xml = createRSSString($rssChannel);

	header("Content-type: text/xml");
	$xml = str_replace('&nbsp;', '', $xml);
	print $xml;
?>