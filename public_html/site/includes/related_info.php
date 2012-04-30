<?php
	include_once('utilities/JaduModulePages.php');

	$showFAQs = false;
	$showForms = false;
	$showDownloads = false;
	$showMeetings = false;
	$showDocuments = false;
	$showNews = false;
	$showEvents = false;
	$showServices = false;
	$showBlogs = false;
	$showDirectories = false;
	$showDirectoryEntries = false;
	$directoryIDs = array();	

if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
	$categoryID = $_GET['categoryID'];
	$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
	$allCategories = array($lgclList->getCategory($categoryID));
	
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
		$bookmarkTitle = $fileItem->title;
	}
	
   	//	Podcasts
   	$showPodcasts = false;
   	if ($currentScript != "podcasts.php") {
   		$greaterThan = 0;
   		if ($currentScript == "podcast_info.php") {
   			$greaterThan = 1;	
   		}
   		if (sizeof(filterCategoriesInUse($allCategories, MULTIMEDIA_PODCAST_APPLIED_CATEGORIES_TABLE, true)) > $greaterThan) {
   			$showPodcasts = true;
   		}
   	}

   	if ($currentScript == 'podcast_info.php') {
   		$bookmarkTitle = $podcast->title;
   	}

   	//	Galleries
   	$showGalleries = false;
   	if ($currentScript != "galleries.php") {
   		$greaterThan = 0;
   		if ($currentScript == "gallery_info.php") {
   			$greaterThan = 1;	
   		}
   		if (sizeof(filterCategoriesInUse($allCategories, MULTIMEDIA_GALLERY_APPLIED_CATEGORIES_TABLE, true)) > $greaterThan) {
   			$showGalleries = true;
   		}
   	}

   	if ($currentScript == 'gallery_info.php') {
   		$bookmarkTitle = $gallery->title;
   	}

	if (getModulePageFromName('Directories')->id != -1) {
		include_once(HOME_DIR .'jadu/directoryBuilder/JaduDirectories.php');
		include_once(HOME_DIR .'jadu/directoryBuilder/JaduDirectoryEntries.php');
		// directories
		if ($currentScript != "directories_index.php") {
    		$greaterThan = 0;
    		if ($currentScript == "directory_home.php") {
    			$greaterThan = 1;	
    		}
    		if (sizeof(filterCategoriesInUse($allCategories, DIRECTORY_APPLIED_CATEGORIES_TABLE, true)) > $greaterThan) {
    			$showDirectories = true;
    		}
    	}

		// directory entries
		$allDirectories = getAllDirectories($adminID = -1, $live = 1, $categoryID);
		foreach($allDirectories as $directory) {
			$directoryIDs[] = $directory->id;
		}
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
		$bookmarkTitle = $currentCategory->name . " news";
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

	//	Services
	if ($currentScript != "services.php") {
		$greaterThan = 0;
		if ($currentScript == "services_info.php") {
			$greaterThan = 1;
		}
		if (sizeof(filterCategoriesInUse($allCategories, SERVICES_APPLIED_CATEGORIES_TABLE, true)) > $greaterThan) {
			$showServices = true;
		}
	}
	else {
		$bookmarkTitle = $parent->name . " services";
	}
	
	//	Blogs
	if ($currentScript != "blog_index.php") {
		if (sizeof(filterCategoriesInUse($allCategories, BLOG_APPLIED_CATEGORIES_TABLE, true)) > 0) {
			$showBlogs = true;
		}
	}
	else {
		$bookmarkTitle = $parent->name . " blogs";
	}

?>

<div id="related">
	<h3>Related items</h3>
	<ul>
		<?php if ($showForms) { ?><li class="relform"><a href="<?php print getSiteRootURL() . buildFormsCategoryURL($categoryID); ?>">Related Forms</a></li><?php } ?>
		<?php if ($showDownloads) { ?><li class="reldownload"><a href="<?php print getSiteRootURL() . buildDownloadsURL($categoryID); ?>" >Related Downloads</a></li><?php } ?>
		<?php if ($showPodcasts) { ?><li class="relpodcast"><a href="<?php print getSiteRootURL() . buildMultimediaPodcastsURL($categoryID); ?>">Related Podcasts</a></li><?php } ?>
		<?php if ($showGalleries) { ?><li class="relgallery"><a href="<?php print getSiteRootURL() . buildMultimediaGalleriesURL($categoryID); ?>">Related Galleries</a></li><?php } ?>
		<?php if ($showMeetings) { ?><li class="relmeet"><a href="<?php print getSiteRootURL() . buildMeetingsURL($categoryID); ?>">Related Meetings &amp; Minutes</a></li><?php } ?>
		<?php if ($showDocuments) { ?><li class="reldocs"><a href="<?php print getSiteRootURL() . buildDocumentsCategoryURL($categoryID); ?>">Related Documents</a></li><?php } ?>
		<?php if ($showNews) { ?><li class="relnews"><a href="<?php print getSiteRootURL() . buildNewsURL($categoryID); ?>">Related News</a></li><?php } ?>
		<?php if ($showEvents) { ?><li class="relevents"><a href="<?php print getSiteRootURL() . buildEventsURL($categoryID); ?>">Related Events</a></li><?php } ?>
		<?php if ($showFAQs) { ?><li class="relfaq"><a href="<?php print getSiteRootURL() . buildFAQURL(false, $categoryID); ?>">Related FAQs</a></li><?php } ?>
		<?php if ($showServices) { ?><li class="relfaq"><a href="<?php print getSiteRootURL(). buildAZServicesCategoryURL($categoryID); ?>">Related Services</a></li><?php } ?>
		<?php if ($showBlogs) { ?><li class="relnews"><a href="<?php print getSiteRootURL() . buildBlogURL($categoryID);?>">Related Blogs</a></li><?php } ?>
<?php
		// show a related link for each directory if an entry in that directory
		// is in this category
		if (count($directoryIDs) > 0) {
			foreach ($directoryIDs as $directoryID) {
				$directory = getDirectory($directoryID);
?>
			<li class="reldocs"><a href="<?php print buildDirectoriesURL(-1, $directory->id); ?>" ><?php print encodeHtml($directory->name); ?></a></li>
<?php
			}
		}
		$currentScriptURL = base64_encode($_SERVER['REQUEST_URI']);
		$currentScriptEmailURL = $_SERVER['REQUEST_URI'];
?>
		<li class="relprint"><a rel="nofollow" href="#" onclick="window.print();return false;">Print this page</a></li>
		<li class="relemail"><a id="emailFriendLink" rel="nofollow" href="<?php print getSiteRootURL() . buildEmailFriendURL($currentScriptEmailURL); ?>">Email this to a friend</a></li>
	</ul>
</div>
<?php
}
?>
