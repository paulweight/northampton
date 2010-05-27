<?php
	session_start();
    require_once('JaduConstants.php');
	require_once('JaduCategories.php');
    require_once('websections/JaduDocuments.php');
    require_once('websections/JaduDownloads.php');
    require_once('websections/JaduNews.php');
    require_once('websections/JaduFAQ.php');
	include_once("egov/JaduCL.php");

    $documents = getAllDocuments(true, true);
	$downloads = getAllDownloads();
	$news = getAllNews(true, true);
	$faqs = getAllFAQs(FAQ_PROCESSED);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Crawl page</title>
	</head>
	<body>
<?php

	//-- DOCUMENTS
	print '<h1>Documents</h1>';
	
    foreach ($documents as $document) {
		$header = getDocumentHeader($document->headerOriginalID, true);
		$pages = getAllDocumentPagesForDocument($document->id, true);
		$categories = getAllCategories(DOCUMENTS_CATEGORIES_TABLE, $document->id);
		
		foreach ($categories as $category) {
			if ($category->categoryType == BESPOKE_CATEGORY_LIST_NAME) {
			
				if (sizeof($pages) <= 1) {
					print '<a href="http://'.$DOMAIN.'/site/scripts/documents_info.php?categoryID='.
						$category->categoryID.'&amp;documentID='.$document->id.'" >'.$header->title.
						'</a><br />'."\n";
				}
				else {
					foreach ($pages as $page) {
						print '<a href="http://'.$DOMAIN.'/site/scripts/documents_info.php?categoryID='.
							$category->categoryID.'&amp;documentID='.$document->id.'&amp;pageNumber='.
							$page->pageNumber.'" >'.$header->title.' &gt; '.$page->title.'</a><br />'."\n";
					}
				}
			}
		}
		
        
    }

	
	//-- DOWNLOADS
	print '<h1>Downloads</h1>';
	
	foreach ($downloads as $download) {
		$files = getAllDownloadFilesForDownload($download->id);
		$categories = getAllCategories(DOWNLOADS_CATEGORIES_TABLE, $download->id);
		
		foreach ($categories as $category) {
			if ($category->categoryType == BESPOKE_CATEGORY_LIST_NAME) {
				foreach ($files as $file) {
					print '<a href="http://'.$DOMAIN.'/site/scripts/download_info.php?categoryID='.$category->categoryID.'&amp;downloadID='.$download->id.'&amp;fileID='.$file->id.'">'.$download->title.' &gt; '.$file->title.'</a><br />'."\n";
				}
			}
		}
	}
	
	//-- NEWS
	print '<h1>News</h1>';

	foreach ($news as $newsItem) {
		$categories = getAllCategories(NEWS_CATEGORIES_TABLE, $newsItem->id);
		
		foreach ($categories as $category) {
			if ($category->categoryType == BESPOKE_CATEGORY_LIST_NAME) {
				print '<a href="http://'.$DOMAIN.'/site/scripts/news_article.php?categoryID='.$category->categoryID.'&amp;newsID='.$newsItem->id.'">'.$newsItem->title.'</a><br />'."\n";
			}
		}
	}


	//-- FAQS
	print '<h1>FAQs</h1>';
	
	foreach ($faqs as $faq) {
		$categories = getAllCategories(FAQS_CATEGORIES_TABLE, $faq->id);
		
		foreach ($categories as $category) {
			if ($category->categoryType == BESPOKE_CATEGORY_LIST_NAME) {
				print '<a href="http://'.$DOMAIN.'/site/scripts/faq_info.php?categoryID='.$category->categoryID.'&amp;faqID='.$faq->id.'">'.$faq->question.'</a><br />'."\n";
			}
		}
	}
	
?>
	</body>
</html>
