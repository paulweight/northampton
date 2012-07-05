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
				<li><a href="<?php print getSiteRootURL() . buildFeedbackURL();?>">Feedback</a></li>
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

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>