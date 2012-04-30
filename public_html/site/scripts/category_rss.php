<?php
	include_once("websections/JaduRSS.php");
	include_once("JaduLibraryFunctions.php");
	include_once("JaduConstants.php");
	include_once("websections/JaduNews.php");
	include_once("websections/JaduFAQ.php");
	include_once("websections/JaduHomepages.php");
	include_once("websections/JaduPressReleases.php");
	include_once("websections/JaduEvents.php");
	include_once("websections/JaduDownloads.php");
	include_once("websections/JaduDownloadFiles.php");
	include_once("websections/JaduDocumentHeaders.php");
	include_once("websections/JaduDocuments.php");
	include_once("websections/JaduDocumentPages.php");
	include_once("egov/JaduXFormsForm.php");
	include_once("utilities/JaduReadableURLs.php");

	function getCategoryName($categoryID) 
	{
		$categoryName = ' ';

		$categoryList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		$categoryItem = $categoryList->GetCategory($categoryID);

		if ($categoryItem != null && $categoryItem->id == $categoryID) {
			$categoryName = ' ' . $categoryItem->name .  ' ';
		}

		return $categoryName;
	}
	
	define('MAX_ITEMS', 10);
	$categoryName = ' ';
	
	if (isset($_GET['content']) && $_GET['content'] != '') {
	
		$rssChannel = new RSSChannel();
		$rssChannel->title = '';
		$rssChannel->description = encodeXML(getSiteRootURL());
		
		//Determine which content type we need to generate an RSS Feed for
		switch ($_GET['content']) {
			case 'news':
				$newsList = array();
				$categoryID = -1;
			
				//Get news items
				if ($_GET['categoryID'] == null || !is_numeric($_GET['categoryID'])) {
					$newsList = getAllNewsByDateLimited(MAX_ITEMS, true, true);
				}
				else {
					$categoryID = $_GET['categoryID'];
					$newsList = getAllNewsWithCategory($categoryID, true, true, BESPOKE_CATEGORY_LIST_NAME, 'news.newsDate DESC', MAX_ITEMS, -1, -1);
					$categoryName = getCategoryName($categoryID);
				}

				$rssChannel->title = 'Latest' . $categoryName . 'News from ' . METADATA_GENERIC_NAME;
				$rssChannel->link = getSiteRootURL() . buildCategoryRSSURL($_GET['content'], $categoryID);
				$rssChannel->href = getSiteRootURL() . buildCategoryRSSURL($_GET['content'], $categoryID);

				foreach ($newsList as $news) {
					$rssItem = new RSSItem();
					$rssItem->title = encodeXML($news->title);
					$rssItem->link = getSiteRootURL() . buildNewsArticleURL($news->id);
					$rssItem->description = encodeXML($news->summary);
					$rssItem->pubDate = date('r', $news->newsDate);

					$rssChannel->addItem($rssItem);
				}
				break;    
			case 'press':
				$pressList = array();

				//Get press release items
				if ($_GET['categoryID'] == null || !is_numeric($_GET['categoryID'])) {
					$pressList = getAllPressReleasesByDateLimited(MAX_ITEMS, true, true);
				}
				else {
					$categoryID = $_GET['categoryID'];
					$pressList = getAllPressReleasesWithCategory($categoryID, true, true, BESPOKE_CATEGORY_LIST_NAME, 'pressRelease.pressDate DESC', -1, -1);
					$categoryName = getCategoryName($categoryID); 
				}

				$rssChannel->title = 'Latest' . $categoryName . 'Press Releases from ' . METADATA_GENERIC_NAME;
				$rssChannel->link = getSiteRootURL() . buildCategoryRSSURL($_GET['content'], $categoryID);
				$rssChannel->href = getSiteRootURL() . buildCategoryRSSURL($_GET['content'], $categoryID);
				
				foreach ($pressList as $pressRelease) {
					$rssItem = new RSSItem();
					$rssItem->title = encodeXML($pressRelease->title);
					$rssItem->link = getSiteRootURL() . buildPressArticleURL($pressRelease->id);
					$rssItem->description = encodeXML($pressRelease->summary);
					$rssItem->pubDate = date('r', $pressRelease->pressDate);

					$rssChannel->addItem($rssItem);
				}

				break;
			case 'events':
				$eventsList = array(); 

				//Get event items
				if ($_GET['categoryID'] == null || !is_numeric($_GET['categoryID'])) {
					$eventsList = getNumEvents(MAX_ITEMS, 'dateCreated DESC', true);
				}
				else {
					$categoryID = $_GET['categoryID'];
					$eventsList = getAllEventsOrderedWithCategory($categoryID, true, true, BESPOKE_CATEGORY_LIST_NAME, 'dateCreated DESC');
					$categoryName = getCategoryName($categoryID);
				}

				$rssChannel->title = 'Latest' . $categoryName . 'Events from ' . METADATA_GENERIC_NAME;
				$rssChannel->link = getSiteRootURL() . buildCategoryRSSURL($_GET['content'], $categoryID);
				$rssChannel->href = getSiteRootURL() . buildCategoryRSSURL($_GET['content'], $categoryID);
				
				foreach ($eventsList as $event) {
					$rssItem = new RSSItem();
					$rssItem->title = encodeXML($event->title);
					$rssItem->link = getSiteRootURL() . buildEventsURL(-1, '', $event->id);
					
					//Get the interval string if exists
					$intervalString = $event->getIntervalString();
					if ($intervalString != '') {
						$intervalString = 'Interval: ' . $intervalString . '<br />';
					}

					//Get the time string if exists
					$timeString = $event->getTimeString(); ;
					if ($timeString != '') {
						$timeString = 'Time: ' . $timeString . '<br />';
					}

					//Get the event dates
					$dateString = $event->getDateString(FORMAT_DATE_MEDIUM);

					$summary = $event->summary . '<br />Date: ' . $dateString . '<br />' . $intervalString . 'Location: ' . $event->location . '<br />' . $timeString;
					$rssItem->description = encodeXML($summary);
					$rssItem->pubDate = date('r', $event->dateCreated);

					$rssChannel->addItem($rssItem);
				}

				break;
			case 'documents':
				$docList = array();

				//Get document items
				if ($_GET['categoryID'] == null || !is_numeric($_GET['categoryID'])) {
					$docList = getXMostRecentlyCreatedDocuments(MAX_ITEMS, true, true);
				}
				else {
					$categoryID = $_GET['categoryID'];
					$docList = getAllDocumentsWithCategory($categoryID, true, true, 'enterDate DESC', -1);
					$categoryName = getCategoryName($categoryID); 
				}

				$rssChannel->title = 'Latest' . $categoryName . 'Documents from ' . METADATA_GENERIC_NAME;
				$rssChannel->link = getSiteRootURL() . buildCategoryRSSURL($_GET['content'], $categoryID);
				$rssChannel->href = getSiteRootURL() . buildCategoryRSSURL($_GET['content'], $categoryID);
				
				foreach($docList as $document) {
					
					//Get the document header
					$documentHeader = getDocumentHeader($document->headerOriginalID, true);
					
					$rssItem = new RSSItem();
					$rssItem->title = encodeXML($documentHeader->title);
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
					$rssItem->pubDate = date('r', $document->enterDate);

					$rssChannel->addItem($rssItem);
				}

				break;
			case 'faqs':

				$faqList = array();

				//Get faq items
				if ($_GET['categoryID'] == null || !is_numeric($_GET['categoryID'])) {
					$faqList = getAllFAQsOrderedWithRecent(30, '1', true, 'creationDate DESC'); 
				}
				else {
					$categoryID = $_GET['categoryID'];
					$faqList = getAllFAQsOrderedWithCategory($categoryID, '1', true, 'creationDate DESC'); 
					$categoryName = getCategoryName($categoryID);
				}

				$rssChannel->title = 'Latest' . $categoryName . 'FAQs from ' . METADATA_GENERIC_NAME;
				$rssChannel->link = getSiteRootURL() . buildCategoryRSSURL($_GET['content'], $categoryID);
				$rssChannel->href = getSiteRootURL() . buildCategoryRSSURL($_GET['content'], $categoryID);

				foreach($faqList as $faqItem) {
				
					$rssItem = new RSSItem();
					$rssItem->title = encodeXML($faqItem->title);
					$rssItem->link = getSiteRootURL() . buildIndividualFAQURL($faqItem->id, true);
					$rssItem->description = encodeXML('<u>Question</u>: ' . $faqItem->question . '<br /><br /><u>Answer</u>: ' . $faqItem->answer);
					$rssItem->pubDate = date('r', $faqItem->creationDate);

					$rssChannel->addItem($rssItem);
				}

				break;
			case 'forms':
				$xFormsList = array();

				//Get press release items
				if ($_GET['categoryID'] == null || !is_numeric($_GET['categoryID'])) {
					$xFormsList = getXMostRecentlyCreatedXFormsForms(MAX_ITEMS, 'enterDate DESC');
				}
				else {
					$categoryID = $_GET['categoryID'];
					$xFormsList = getAllFormsOrderedWithCategory($categoryID, true, true, BESPOKE_CATEGORY_LIST_NAME, 'enterDate DESC');
					$categoryName = getCategoryName($categoryID);
				}

				$rssChannel->title = 'Latest' . $categoryName . 'Forms from ' . METADATA_GENERIC_NAME;
				$rssChannel->link = getSiteRootURL() . buildCategoryRSSURL($_GET['content'], $categoryID);
				$rssChannel->href = getSiteRootURL() . buildCategoryRSSURL($_GET['content'], $categoryID);

				foreach ($xFormsList as $form) {
					$rssItem = new RSSItem();
					$rssItem->title = encodeXML($form->title);
					$rssItem->link = getSiteRootURL() . buildXFormsURL($form->id);
					$rssItem->description = encodeXML($form->instructions);
					$rssItem->pubDate = date('r', $form->enterDate);

					$rssChannel->addItem($rssItem);
				}
							
				break;
			case 'homepages':
				$homepageList = array();

				//Get homepage items
				if ($_GET['categoryID'] == null || !is_numeric($_GET['categoryID'])) {
					header('Location: ' . getSiteRootURL());
					exit();
				}
				else {
					$categoryID = $_GET['categoryID'];
					$homepageList = getAllHomepagesOrderedInCategory($categoryID, true, 'itemID DESC');
					$categoryName = getCategoryName($categoryID);
				}

				$rssChannel->title = 'Latest' . $categoryName . 'Homepages from ' . METADATA_GENERIC_NAME;
				$rssChannel->link = getSiteRootURL() . buildCategoryRSSURL($_GET['content'], $categoryID);
				$rssChannel->href = getSiteRootURL() . buildCategoryRSSURL($_GET['content'], $categoryID);

				foreach ($homepageList as $homepageItem) {
					
					if ($homepageItem->id > 0) {
						
						$rssItem = new RSSItem();
						$rssItem->title = encodeXML($homepageItem->title);
						$rssItem->link = getSiteRootURL() . buildHomeURL($homepageItem->id, true);
						$rssItem->description = encodeXML($homepageItem->description);
						
						$rssChannel->addItem($rssItem);
					}
				}

				break;
			case 'downloads':
				$downloadFileList = array();

				//Get download items
				if ($_GET['categoryID'] == null || !is_numeric($_GET['categoryID'])) {
					$downloadFileList = getXMostRecentlyCreatedDownloadFiles(MAX_ITEMS, true, true, true);
				}
				else {
					$categoryID = $_GET['categoryID'];

					$downloadFileList = getAllDownloadFilesWithCategory($categoryID, 'creationDate DESC');
					$categoryName = getCategoryName($categoryID);
				}

				$rssChannel->title = 'Latest' . $categoryName . 'Download Files from ' . METADATA_GENERIC_NAME;
				$rssChannel->link = getSiteRootURL() . buildCategoryRSSURL($_GET['content'], $categoryID);
				$rssChannel->href = getSiteRootURL() . buildCategoryRSSURL($_GET['content'], $categoryID);

				foreach ($downloadFileList as $downloadFile) {
					$rssItem = new RSSItem();
					$rssItem->title = encodeXML('Download: ' . $downloadFile->title);
					$rssItem->link = getSiteRootURL() . buildDownloadsURL(-1, $downloadFile->id, -1, false, 'downloads', true);
					
					$criteria = array();
					$criteria['approved'] = '1';
					$criteria['live'] = '1';
					
					$download = getDownload(downloadFileItem.DownloadID, $criteria);

					
					$rssItem->description = '';
					$rssItem->pubDate = date('r', $downloadFile->creationDate);

					$rssChannel->addItem($rssItem);
				}

				break;
			default:
				header('Location: ' . getSiteRootURL());
				exit();
		}

		$xml = createRSSString($rssChannel);

		header('Content-type: text/xml');
		$xml = str_replace('&nbsp;', '', $xml);
		print $xml;
	}
	else {
		header('Location: ' . getSiteRootURL());
		exit();
	}
?>