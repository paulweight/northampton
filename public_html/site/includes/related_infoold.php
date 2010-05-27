<?php
	$showFAQs = false;
	$showForms = false;
	$showDownloads = false;
	$showMeetings = false;
	$showDocuments = false;
	$showNews = false;
	$showEvents = false;

	$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);	
	$allCategories = array($lgclList->getCategory($_GET['categoryID']));
	
	$currentScript = basename($_SERVER['PHP_SELF']);

	//	FAQ's
	if ($currentScript != "faqs.php") {
		$greaterThan = 0;
		if ($currentScript == "faq_info.php") {
			$greaterThan = 1;
		}
		if (sizeof(filterCategoriesInUse($allCategories, FAQS_APPLIED_CATEGORIES_TABLE, true)) > $greaterThan) {
			$showFAQs = true;
		}
	}
	else {
		if (isset($_GET['faqID'])) {
			$bookmarkTitle = $faq->title;
		}
		else {
			$bookmarkTitle = $parent->name . " FAQs";
		}
	}

	//	Forms
	if ($currentScript != "forms.php" && sizeof(filterCategoriesInUse($allCategories, XFORMS_FORM_APPLIED_CATEGORIES_TABLE, true)) > 0) {
		$showForms = true;
	}
	
	if ($currentScript == 'forms.php') {
		$bookmarkTitle = $parent->name . " forms";
	}
	
	//	Downloads
	if ($currentScript != "downloads.php") {
		$greaterThan = 0;
		if ($currentScript == "download_info.php") {
			$greaterThan = 1;	
		}
		if (sizeof(filterCategoriesInUse($allCategories, DOWNLOADS_APPLIED_CATEGORIES_TABLE, true)) > $greaterThan) {
			$showDownloads = true;
		}
	}
	
	if ($currentScript == 'download_info.php') {
		$bookmarkTitle = $file->title;
	}
	
	//	Meetings
	if ($currentScript != "meetings.php") {
		$greaterThan = 0;
		if ($currentScript == "meetings_info.php") {
			$greaterThan = 1;
		}
		if (sizeof(filterCategoriesInUse($allCategories, MEETING_MINUTES_APPLIED_CATEGORIES_TABLE, true)) > $greaterThan) {
			$showMeetings = true;
		}
	}
	
	//	Documents
	if ($currentScript != "documents.php") {
		$greaterThan = 0;
		if ($currentScript == "documents_info.php") {
			$greaterThan = 1;
		}
		if (sizeof(filterCategoriesInUse($allCategories, DOCUMENTS_APPLIED_CATEGORIES_TABLE, true)) > $greaterThan) {
			$showDocuments = true;
		}
	}
	else {
		$bookmarkTitle = $parent->name;
	}
	
	if ($currentScript == 'documents_info.php') {
		$bookmarkTitle = $header->title;
	}

	//	News
	if ($currentScript != "news_category.php") {
		$greaterThan = 0;
		if ($currentScript == "news_article.php") {
			$greaterThan = 1;
		}
		if (sizeof(filterCategoriesInUse($allCategories, NEWS_APPLIED_CATEGORIES_TABLE, true)) > $greaterThan) {
			$showNews = true;
		}
	}
	else {
		$bookmarkTitle = $categoryViewing->name . " news";
	}
	
	//	Events
	if ($currentScript != "events.php") {
		$greaterThan = 0;
		if ($currentScript == "events_info.php") {
			$greaterThan = 1;
		}
		if (sizeof(filterCategoriesInUse($allCategories, EVENTS_APPLIED_CATEGORIES_TABLE, true)) > $greaterThan) {
			$showEvents = true;
		}
	}
	else {
		$bookmarkTitle = $parent->name . " events";
	}

?>

<div id="related">

<?php
		 $emailFriendString = urlencode($currentScript . "?" . $_SERVER['QUERY_STRING']);
?>
	<ul>
		<li class="relprint"><a rel="nofollow" href="#" onclick="window.print();return false;">Print this page</a></li>
		<li class="relemail"><a rel="nofollow" href="http://<?php print $DOMAIN; ?>/site/scripts/email_friend.php?link=<?php print $emailFriendString;?>">Email this to a friend</a></li>
		<li class="relcomment"><a rel="nofollow" href="http://<?php print $DOMAIN; ?>/site/scripts/pageComments.php">Comment on this page</a></li>
	</ul>

<?php
	if ($showFAQs || $showForms || $showDownloads || $showMeetings || $showDocuments || $showNews || $showEvents) {
?>
	<ul>
		<li><h2>Related items</h2></li>
		<?php if ($showForms) { ?><li class="relform"><a href="http://<?php print $DOMAIN; ?>/site/scripts/forms.php?categoryID=<?php print $categoryID;?>">Online forms</a></li><?php } ?>
		<?php if ($showDownloads) { ?><li class="reldownload"><a href="http://<?php print $DOMAIN; ?>/site/scripts/downloads.php?categoryID=<?php print $categoryID;?>">Downloads</a></li><?php } ?>
		<?php if ($showMeetings) { ?><li class="relmeet"><a href="http://<?php print $DOMAIN; ?>/site/scripts/meetings.php?categoryID=<?php print $categoryID;?>">Meetings and minutes</a></li><?php } ?>
		<?php if ($showDocuments) { ?><li class="reldocs"><a href="http://<?php print $DOMAIN; ?>/site/scripts/documents.php?categoryID=<?php print $categoryID;?>">Further information</a></li><?php } ?>
		<?php if ($showNews) { ?><li class="relnews"><a href="http://<?php print $DOMAIN; ?>/site/scripts/news_category.php?categoryID=<?php print $categoryID;?>">News</a></li><?php } ?>
		<?php if ($showEvents) { ?><li class="relevents"><a href="http://<?php print $DOMAIN; ?>/site/scripts/events.php?categoryID=<?php print $categoryID;?>">Events</a></li><?php } ?>
		<?php if ($showFAQs) { ?><li class="relfaq"><a href="http://<?php print $DOMAIN; ?>/site/scripts/faqs.php?categoryID=<?php print $categoryID;?>">Frequently asked questions</a></li><?php } ?>
	</ul> 
<?php
		 }   
?>

</div>
