<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="site map, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> site map" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> site map" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> site map" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	
			<h2>About this website</h2>
			<ul class="list icons documents">
				<li><a href="<?php print getSiteRootURL() . buildAccessibilityURL();?>">Accessibility statement and access keys</a></li>
				<li><a href="<?php print getSiteRootURL() . buildUserSettingsURL();?>">Settings for accessibility</a></li>
				<li><a href="<?php print getSiteRootURL() . buildStatisticsURL();?>">Website statistics</a></li>
				<li><a href="<?php print getSiteRootURL() . buildWhatsNewURL();?>">What's new</a></li>
				<li><a href="<?php print getSiteRootURL() . buildTermsURL();?>">Terms and disclaimer</a></li>
			</ul>
		
			<h2>Contacting us</h2>
			<ul class="list icons documents">
				<li><a href="<?php print getSiteRootURL() . buildContactURL();?>">Contact us</a></li>
				<li><a href="<?php print getSiteRootURL() . buildFeedbackURL();?>">Feedback form</a></li>
				<li><a href="<?php print getSiteRootURL() . buildLocationURL();?>">Location map and directions</a></li>
			</ul>
		
			<h2>Services and information</h2>
			<ul class="list icons services">
				<li><a href="<?php print getSiteRootURL() . buildAToZURL();?>">A-Z of services</a></li>
				<li><a href="<?php print getSiteRootURL() . buildXFormsURL();?>">Online forms</a></li>
				
<?php
				$docCatCount = sizeof($rootCategories)-1;
				foreach ($rootCategories as $index => $rootCat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildDocumentsCategoryURL($rootCat->id);?>"><?php print encodeHtml($rootCat->name);?></a></li>
<?php
				}
?>	
				
				<li><a href="<?php print getSiteRootURL() . buildJobsURL();?>">Job vacancies</a></li>
				<li><a href="<?php print getSiteRootURL() . buildLinksURL();?>">Links and web resources</a></li>
			</ul>
		
			<h2>Downloads</h2>
			<ul class="list icons downloads">
				<li><a href="<?php print getSiteRootURL() . buildDownloadsURL();?>">Popular downloads</a></li>				
<?php
			$downloadCatCount = sizeof($rootDownloadCategories);				
			foreach ($rootDownloadCategories as $index => $rootCat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildDownloadsURL($rootCat->id);?>"><?php print encodeHtml($rootCat->name);?> downloads</a></li>
				
<?php
			}
?>				
			</ul>
		
			<h2>News</h2>
			<ul class="list icons news">
				<li><a href="<?php print getSiteRootURL() . buildNewsURL();?>">Latest news</a></li>
				<li><a href="<?php print getSiteRootURL() . buildNewsArchiveURL();?>">News archive</a></li>
				<li><a href="<?php print getSiteRootURL() . buildFeedsURL();?>">External news feeds</a></li>
				<li><a href="<?php print getSiteRootURL() . buildPastPollResultsURL();?>">Past polls</a></li>
				<li><a href="<?php print getSiteRootURL() . buildWhatsNewURL();?>">What's new on site</a></li>
			</ul>
		
			<h2>News by category</h2>
			<ul class="list icons news">				
<?php
			$newsCatCount = count($rootNewsCategories);
			foreach ($rootNewsCategories as $index => $rootCat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildNewsURL($rootCat->id);?>"><?php print encodeHtml($rootCat->name);?> news</a></li>
<?php
			}
?>				
			</ul>
		
			<h2>Galleries</h2>
			<ul class="list icons galleries">	
				<li><a href="<?php print getSiteRootURL() . buildMultimediaGalleriesURL(); ?>">Galleries by category</a></li>	
<?php
			$galleryCatCount = count($rootGalleryCategories)-1;
			foreach ($rootGalleryCategories as $index => $rootCat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildMultimediaGalleriesURL($rootCat->id); ?>"><?php print encodeHtml($rootCat->name);?> galleries</a></li>
<?php
			}
?>			
			</ul>
		
			<h2>Podcasts</h2>
			<ul class="list icons podcasts">
				<li><a href="<?php print getSiteRootURL() . buildMultimediaPodcastsURL(); ?>">Podcasts by category</a></li>				
<?php
			$podcastCatCount = count($rootPodcastCategories);				
			foreach ($rootPodcastCategories as $index => $rootCat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildMultimediaPodcastsURL($rootCat->id); ?>"><?php print encodeHtml($rootCat->name);?> podcasts</a></li>

<?php
			}
?>
			</ul>
		
			<h2>Press releases</h2>
			<ul class="list icons press">
				<li><a href="<?php print getSiteRootURL() . buildPressURL();?>">Press releases</a></li>
				<li><a href="<?php print getSiteRootURL() . buildPressArchiveURL();?>">Press release archive</a></li>
			</ul>
		
			<h2>Press releases by category</h2>
			<ul class="list icons press">				
<?php
			$pressCatCount = count($rootPressCategories);
			foreach ($rootPressCategories as $index => $rootCat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildPressURL($rootCat->id);?>"><?php print encodeHtml($rootCat->name);?> press releases</a></li>
<?php
			}
?>				
			</ul>
		
			<h2>Events</h2>
			<ul class="list icons events">
			   <li><a href="<?php print getSiteRootURL() . buildEventsURL();?>">Pick of the week</a></li>
			   <li><a href="<?php print getSiteRootURL() . buildEventsURL(-1,'thisWeek');?>">What's on this week</a></li>
			   <li><a href="<?php print getSiteRootURL() . buildEventsURL(-1,'nextWeek');?>">Next week</a></li>
			   <li><a href="<?php print getSiteRootURL() . buildEventsURL(-1,'thisMonth');?>">This month</a></li>
			   <li><a href="<?php print getSiteRootURL() . buildEventsURL(-1,'nextMonth');?>">Next month</a></li>
			   <li><a href="<?php print getSiteRootURL() . buildEventsURL(-1,'full');?>">Full events listings</a></li>
		   </ul>
		
			<h2>Events by category</h2>
<?php
	if(sizeof($rootEventsCategories) > 0) {
?>
			<ul class="list icons events">
<?php
			foreach ($rootEventsCategories as $index => $rootCat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildEventsURL($rootCat->id);?>"><?php print encodeHtml($rootCat->name); ?> events</a></li>
<?php
			}
?>
			</ul>
<?php
	}
?>
		
			<h2>Frequently asked questions</h2>
			<ul class="list icons faqs">
				<li><a href="<?php print getSiteRootURL() . buildFAQURL();?>">Common questions</a></li>
				<li><a href="<?php print getSiteRootURL() . buildFAQURL(true);?>">Ask a question</a></li>
			</ul>
		

<?php
		if (!Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
?>

		
			<h2>Registration</h2>
			<ul class="list icons councillors">
				<li><a href="<?php print getSecureSiteRootURL() . buildRegisterURL();?>">Registration form</a></li>
				<li><a href="<?php print getSecureSiteRootURL() . buildForgotPasswordURL();?>">Password reminder</a></li>
			</ul>
			
<?php
		}
		else {
?>
				
		
			<h2>Your details</h2>
			<ul class="list icons councillors">
				<li><a href="<?php print getSecureSiteRootURL() . buildUserHomeURL();?>">Your account</a></li>
				<li><a href="<?php print getSecureSiteRootURL() . buildChangeDetailsURL();?>">Change your details</a></li>
				<li><a href="<?php print getSecureSiteRootURL() . buildChangePasswordURL();?>">Change your password</a></li>
				<li><a href="<?php print getSecureSiteRootURL() . buildForgotPasswordURL();?>">Password reminder</a></li>
			</ul>
		
			<h2>Your online forms</h2>
			<ul class="list icons forms">
				<li><a href="<?php print getSecureSiteRootURL() . buildUserHomeURL();?>">Online forms home</a></li>
				<li><a href="<?php print getSecureSiteRootURL() . buildUserFormURL();?>">Form archive</a></li>
			</ul>
		
<?php
		}
?>

	

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>