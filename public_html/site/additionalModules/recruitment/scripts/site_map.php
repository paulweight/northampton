<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("egov/JaduCL.php");
	
	include_once("JaduAppliedCategories.php");
	
	include_once("websections/JaduContact.php");
	include_once("websections/JaduNews.php");	
	include_once("egov/JaduEGovMeetingMinutes.php");
	
	$address = new Address();
	
	$lgcl = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
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
	
	//	News Top level useage calculation
	$usedTopLevelCats = createItemIndex(NEWS_CATEGORIES_TABLE, $lgcl);
	$newsWithCats = sortAndFilterCategorisedNews ($usedTopLevelCats);
	
	$meetingHeaders = getAllMeetingMinutesHeaders();

	$rootEventsCategories = filterCategoriesInUse($allRootCategories, EVENTS_APPLIED_CATEGORIES_TABLE, true);

	$breadcrumb = 'sitemap';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html<?php if (TEXT_DIRECTION == 'rtl') print ' dir="rtl"'; ?> xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Site map</title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="site map, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> site map" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> site map" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> site map" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<div class="sitemap">
		<div class="info_left">
			<h2>About this website</h2>
			<ul class="list">
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/about_us.php">Accessibility statement</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/about_us.php">Access keys</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/user_settings.php">Settings for accessibility</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/website_statistics.php">Website statistics</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/whats_new_index.php">What's new</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/terms.php">Terms and disclaimer</a></li>
			</ul>
		</div>	
		<div class="info_right">
			<h2>Contacting us</h2>
			<ul class="list">
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/contact.php">Contacting us</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/feedback.php">Feedback form</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/location.php">Location map and directions</a></li>
			</ul>
		</div>					
		<div class="clear"></div>

	</div>
	
		<!-- Key Information area -->
		<div class="sitemap">
		<div class="info_left">
			<h2>Information</h2>
				<ul class="list">	
<?php
				$docCatCount = sizeof($rootCategories)-1;
				foreach ($rootCategories as $index => $rootCat) {
?>
					<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/documents.php?categoryID=<?php print $rootCat->id;?>"><?php print encodeHtml($rootCat->name);?></a></li>
<?php
				}
?>			
				</ul>
		</div>	
		<div class="info_right">
			<h2>Downloads</h2>
			<ul class="list">
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/downloads_index.php">Downloads by category</a></li>				
<?php
			$downloadCatCount = sizeof($downloadRootCategories);				
			foreach ($downloadRootCategories as $index => $rootCat) {
?>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/downloads.php?categoryID=<?php print $rootCat->id;?>"><?php print encodeHtml($rootCat->name);?> Downloads</a></li>
				
<?php
			}
?>
						
			</ul>
		</div>
		<div class="clear"></div>
	</div>				

	<!-- News and events area -->
	<div class="sitemap">
		<div class="info_left">
			<h2>News</h2>
			<ul class="list">
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/news_index.php">Latest news</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/view_feeds.php">External news feeds</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/rss_about.php">About RSS feeds</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/poll_results.php">Opinion poll results</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/poll_past_results.php">Past polls</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/whats_new_index.php">What's new on site</a></li>
			</ul>
		</div>	
			<div class="info_right">
			<h2>News by category</h2>
			<ul class="list">				
<?php
			$newsCatCount = sizeof($newsWithCats)-1;
			foreach (array_keys($newsWithCats) as $index => $topCat) {
				$cat = $lgcl->getCategory($topCat);
?>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/news_category.php?categoryID=<?php print $cat->id;?>"><?php print encodeHtml($cat->name); ?> News</a></li>
<?php
			}
?>				
			</ul>
		</div>	
		<div class="clear"></div>
	</div>	
		
	<div class="sitemap">
		<div class="info_left">
			<h2>Events</h2>
			<ul class="list">
			   <li><a href="<?php print getSiteRootURL(); ?>/site/scripts/events_index.php">Pick of the Week</a></li>
			   <li><a href="<?php print getSiteRootURL(); ?>/site/scripts/events_info.php?period=thisWeek">What's on this week</a></li>
			   <li><a href="<?php print getSiteRootURL(); ?>/site/scripts/events_info.php?period=nextWeek">Next week</a></li>
			   <li><a href="<?php print getSiteRootURL(); ?>/site/scripts/events_info.php?period=thisMonth">This month</a></li>
			   <li><a href="<?php print getSiteRootURL(); ?>/site/scripts/events_info.php?period=nextMonth">Next Month</a></li>
			   <li><a href="<?php print getSiteRootURL(); ?>/site/scripts/events_info.php?period=full">Full events listings</a></li>
		   </ul>
		   </div>
		<div class="info_right">
			<h2>Events by category</h2>
			<ul class="list">
<?php
			foreach ($rootEventsCategories as $index => $rootCat) {
?>
			   <li><a href="<?php print getSiteRootURL(); ?>/site/scripts/events.php?categoryID=<?php print $rootCat->id;?>"><?php print encodeHtml($rootCat->name); ?> events</a></li>
<?php
				}
?>
		   </ul>
		   </div>
		<div class="clear"></div>
	</div>	

		<!-- Resources area -->
		<div class="sitemap">
		<div class="info_left">
			<h2>Other resources</h2>
			<ul class="list">
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/xforms_index.php">Online forms</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/links.php">Links and web resources</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/az_home.php">A-Z of services</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/recruit_jobs.php">Job vacancies</a></li>
			</ul>
		</div>	

		<div class="info_right">
			<h2>Frequently Asked Questions</h2>
			<ul class="list">
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/faqs_index.php">FAQ's by category</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/faqs_ask.php">Ask a Question</a></li>
			</ul>
		</div>
		<div class="clear"></div>
	</div>	


<?php
		if (!Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
?>

		<div class="sitemap">
			<h2>Registration</h2>
			<ul class="list">
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/register.php">Registration form</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/forgot_password.php">Password reminder</a></li>
			</ul>
			<div class="clear"></div>
		</div>	
<?php
		}
		else if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
?>
				
		<!-- Your Account area -->
	<div class="sitemap">			
		<div class="info_left">
			<h2>Your details</h2>
				<ul class="list">
					<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/user_home.php">Your account home</a></li>
					<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/change_details.php">Change your details</a></li>
					<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/change_password.php">Change your Password</a></li>
					<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/forgot_password.php">Password Reminder</a></li>
				</ul>
		</div>	

		<div class="info_right">
		<h2>Your online forms</h2>
				<ul class="list">
					<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/user_home.php">Online forms home</a></li>
					<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/user_form_archive.php">Form archive</a></li>
				</ul>
		</div>	
		<div class="clear"></div>
	</div>	
<?php
		}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>