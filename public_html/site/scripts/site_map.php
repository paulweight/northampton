<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("egov/JaduCL.php");
	
	include_once("JaduAppliedCategories.php");
	
	include_once("websections/JaduContact.php");
	include_once("websections/JaduNews.php");	
	include_once("egov/JaduEGovMeetingMinutes.php");
	
	$address = new Address();
	
	$lgcl = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$allRootCategories = $lgcl->getTopLevelCategories();	
	
	//	Documents top level useage	
	$rootDocumentCategories = filterCategoriesInUse($allRootCategories, DOCUMENTS_APPLIED_CATEGORIES_TABLE, true);
	$rootHomepageCategories = filterCategoriesInUse($allRootCategories, HOMEPAGE_APPLIED_CATEGORIES_TABLE, true);

	$categoriesUsed = array();
	$rootCategories = array();
	
	foreach ($rootDocumentCategories as $item) {
		$categoriesUsed[] = $item->id;
		$rootCategories[] = $item;
	}
	
	foreach ($rootHomepageCategories as $item) {
		if (!in_array($item->id, $categoriesUsed)) {
			$categoriesUsed[] = $item->id;
			$rootCategories[] = $item;
		}
	}

	$formRootCategories = filterCategoriesInUse($allRootCategories, XFORMS_FORM_APPLIED_CATEGORIES_TABLE, true);
	$downloadRootCategories = filterCategoriesInUse($allRootCategories, DOWNLOADS_APPLIED_CATEGORIES_TABLE, true);
	
	if (defined('MOD_MULTIMEDIA') && MOD_MULTIMEDIA) {
    	$galleryRootCategories = filterCategoriesInUse($allRootCategories, MULTIMEDIA_GALLERY_APPLIED_CATEGORIES_TABLE, true);
    	$podcastRootCategories = filterCategoriesInUse($allRootCategories, MULTIMEDIA_PODCAST_APPLIED_CATEGORIES_TABLE, true);
    }

	//	News Top level useage calculation
	$usedTopLevelCats = createItemIndex(NEWS_CATEGORIES_TABLE, $lgcl);
	$newsWithCats = sortAndFilterCategorisedNews ($usedTopLevelCats);
	
	$meetingHeaders = getAllMeetingMinutesHeaders();

	$rootEventsCategories = filterCategoriesInUse($allRootCategories, EVENTS_APPLIED_CATEGORIES_TABLE, true);

	$breadcrumb = 'sitemap';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Site map | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="site map, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> site map" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> site map" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> site map" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<div id="columnLeft" class="cate_info">

		<ul>
			<li class="noHash"><h2><?php print METADATA_GENERIC_COUNCIL_NAME;?></h2></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/documents_info.php?documentID=657&amp;pageNumber=1">Main contact details</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/xforms_form.php?formID=18">Feedback form</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/location.php">Location map and directions</a></li>
			<!-- <li><a href="http://<?php print $DOMAIN;?>/site/scripts/council_democracy_index.php">Councillors</a></li> -->
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/az_home.php">Our services</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/xforms_index.php">Online forms</a></li>
		</ul>
		
		<ul>
			<li class="noHash"><h2>Council information on...</h2></li>
<?php
		$docCatCount = sizeof($rootCategories)-1;
		foreach ($rootCategories as $index => $rootCat) {
?>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/documents.php?categoryID=<?php print $rootCat->id;?>"><?php print $rootCat->name;?></a></li>
<?php
		}
?>			
		</ul>

		<ul>
			<li class="noHash"><h2>News</h2></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/news_index.php">Latest news</a></li>
			<!--<li><a href="http://<?php print $DOMAIN;?>/site/scripts/press_index.php">Press releases</a></li>-->
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/news_archive.php">News archive</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/view_feeds.php">External news feeds</a></li>
		</ul>

			

		
		<ul>
			<li class="noHash"><h2>Events</h2></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/events_index.php">Pick of the Week</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/events_info.php?period=thisWeek">What's on this week</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/events_info.php?period=nextWeek">Next week</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/events_info.php?period=thisMonth">This month</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/events_info.php?period=nextMonth">Next Month</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/events_info.php?period=full">Full events listings</a></li>
		</ul>
		   
	</div>
			
	<div id="columnRight" class="cate_info">


			<ul>
				<li class="noHash"><h2>About this website</h2></li>
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/accessibility.php">Accessibility statement</a></li>
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/user_settings.php">Settings for accessibility</a></li>
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/website_statistics.php">Website statistics</a></li>
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/whats_new_index.php">What's new</a></li>
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/terms.php">Terms and disclaimer</a></li>
			</ul>

<?php
		if (!isset($_SESSION['userID'])) {
?>


		
		<ul>
			<li class="noHash"><h2>Registration</h2></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/register.php">Register for an account</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/forgot_password.php">Password reminder</a></li>
		</ul>
	
<?php
		}
		else if (isset($_SESSION['userID'])) {
?>
				
		<!-- Your Account area -->
		
		<ul>
			<li class="noHash"><h2>Your details</h2></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/user_home.php">Your account</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/change_details.php">Change your registration details</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/change_password.php">Change your Password</a></li>
		</ul>

		
		<ul>
			<li class="noHash"><h2>Your online forms</h2></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/user_home.php">Your forms</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/user_form_archive.php">Form archive</a></li>
		</ul>	
<?php
		}
?>

		<ul>
			<li class="noHash"><h2>Other resources</h2></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/xforms_index.php">Online forms</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/links.php">Links and web resources</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/az_home.php">A-Z of services</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/recruit_jobs.php">Job vacancies</a></li>
		</ul>

		<ul>
			<li class="noHash"><h2>Downloads</h2></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/downloads_index.php">Downloads by category</a></li>				
<?php
		$downloadCatCount = sizeof($downloadRootCategories);				
		foreach ($downloadRootCategories as $index => $rootCat) {
?>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/downloads.php?categoryID=<?php print $rootCat->id;?>"><?php print $rootCat->name;?> Downloads</a></li>
			
<?php
		}
?>
					
		</ul>


		<ul>
			<li class="noHash"><h2>Frequently Asked Questions</h2></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/faqs_index.php">FAQs by category</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/faqs_ask.php">Ask a Question</a></li>
		</ul>

	</div>
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->


<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
