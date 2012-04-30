<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> - <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="whats new, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="The latest news, documents, jobs, events and files to be added to the <?php print encodeHtml(METADATA_GENERIC_NAME); ?> web site" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> - whats new on the site" />
	<meta name="DC.description" lang="en" content="The latest news, documents, jobs, events and files to be added to the <?php print encodeHtml(METADATA_GENERIC_NAME); ?> web site" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
		<p>Check here to see the most recent information published on site.</p>
	
<?php 
		$navCounter = 0; 
		
		// see which sections we're going to display and put them in an array
		$sections = array();
		
		if (sizeof($news) > 0) { 
			$sections[] = "News";
		}
		if (sizeof($downloads) > 0) {
			$sections[] = "Downloads";
		}								
		if (sizeof($documents) > 0) {
			$sections[] = "Documents";
		}
		if (sizeof($events) > 0) {
			$sections[] = "Events";
		}								
		if (sizeof($forms) > 0) {
			$sections[] = "Forms";
		}
		if (sizeof($press) > 0) {
			$sections[] = "Press Releases";
		}

?>
			<ul class="list">
<?php
			foreach ($sections as $section) {
?>
				<li><a href="http://<?php print DOMAIN . buildWhatsNewURL(); ?>#<?php print encodeHtml(str_replace(' ', '', $section)); ?>"><?php print encodeHtml($section); ?></a></li>
<?php
			}
?>
			</ul>
			
	<ul>
<?php 
		if (sizeof($news) > 0) {
?>
		<!-- most recent news -->
	<li>
		<h2 id="News">Latest news</h2>
		<ul class="list">
<?php 
		$itemsToShow = min(MAX_WHATS_NEW, count($news));
		for ($i = 0; $i < $itemsToShow; $i++) {
			$item = $news[$i];
			if ($item != null) {
?>
			<li>
				<a href="<?php print getSiteRootURL() . buildNewsArticleURL($item->id); ?>"><?php print encodeHtml($item->title); ?></a> <span class="small">- <?php print formatDateTime(FORMAT_DATE_LONG, $item->newsDate);?></span>
				<p><?php print $item->summary; ?></p>
			</li>
<?php
			}
		}
?>
		</ul>
	</li>
		<!-- END most recent news -->
<?php
		}

		if (sizeof($downloads) > 0) {
?>
		<!-- most recent downloads -->
	<li>
		<h2 id="Downloads">Downloads</h2>
		<ul class="list">
<?php 
		$itemsToShow = min(MAX_WHATS_NEW, count($downloads));
		for ($i = 0; $i < $itemsToShow; $i++) {
			$item = $downloads[$i];
			
			if ($item != null) {
?>
			<li>
				<a href="<?php print getSiteRootURL() . buildDownloadsURL( -1, $item->id); ?>"><?php print encodeHtml($item->title);?></a> <span class="small">- <?php print formatDateTime(FORMAT_DATE_LONG, $item->creationDate);?></span>
			</li>
<?php
			}
		}
?>
		</ul>
	</li>
			<!-- END most recent downloads -->
<?php
		}

		if (sizeof($documents) > 0) {
?>
			<!-- most recent documents -->
	<li>
		<h2 id="Documents">Documents</h2>
		<ul class="list">
<?php 
		foreach ($documents as $document) {
			
			$header = getDocumentHeader($document->headerOriginalID, true);			
			
			if ($document != null) {
?>
			<li>
				<a href="<?php print getSiteRootURL() . buildDocumentsURL($document->id); ?>"><?php print encodeHtml($header->title);?></a> <span class="small">- <?php print formatDateTime(FORMAT_DATE_LONG, $document->enterDate);?></span>
			</li>
<?php
			}
		}
?>
		</ul>
	</li>
		<!-- END most recent documents -->
<?php
		}
?>
	
<?php 
		if (sizeof($events) > 0) {
?>
		<!-- most recent events -->
	<li>
		<h2 id="Events">Events</h2>
		<ul class="list">
<?php 
		$itemsToShow = min(MAX_WHATS_NEW, count($events));
		for ($i = 0; $i < $itemsToShow; $i++) {
			$item = $events[$i];
			
			if ($item != null) {
?>
			<li>
				<a href="<?php print getSiteRootURL() . buildEventsURL(-1, '', $item->id);?>"><?php print encodeHtml($item->title);?></a> <span class="small">- <?php print formatDateTime(FORMAT_DATE_LONG, $item->dateCreated);?></span>
				<p><?php print $item->summary; ?></p>
			</li>
<?php
			}
		}
?>
		</ul>
	</li>
		<!-- END most recent events -->
<?php
		}

		if (sizeof($forms) > 0) {
?>
		<!-- most recent online forms -->
	<li>
		<h2 id="Forms">Online Forms</h2>
		<ul class="list">
<?php 
		$itemsToShow = min(MAX_WHATS_NEW, count($forms));
		for ($i = 0; $i < $itemsToShow; $i++) {
			$item = $forms[$i];
	
			if ($item != null) {
?>
			<li><a href="<?php print getSiteRootURL() . buildXFormsURL($item->id); ?>"><?php print encodeHtml($item->title);?></a> <span class="small">- <?php print formatDateTime(FORMAT_DATE_LONG, $item->enterDate);?></span></li>
<?php
			}
		}
?>
		</ul>
	</li>
		<!-- END most recent online forms -->
<?php
		}
		if (sizeof($press) > 0) {
?>
		<!-- most recent press releases -->
	<li>
		<h2 id="PressReleases">Latest Press Releases</h2>
		<ul class="list">
<?php 
		$itemsToShow = min(MAX_WHATS_NEW, count($press));
		for ($i = 0; $i < $itemsToShow; $i++) {
			$item = $press[$i];
			if ($item != null) {
?>
			<li><a href="<?php print getSiteRootURL() . buildPressArticleURL($item->id); ?>"><?php print encodeHtml($item->title); ?></a> <span class="small">- <?php print formatDateTime(FORMAT_DATE_LONG, $item->pressDate);?></span></li>
<?php
			}
		}
?>
		</ul>
	</li>
		<!-- END most recent press releases -->
<?php
		}
?>
	</ul>
	<!--<p><a href="<?php print getSiteRootURL() . buildRSSURL("whats_new", -1); ?>" target="_blank"><img src="<?php print getStaticContentRootURL(); ?>/site/images/xml.gif" alt="Get this feed" /> <?php print encodeHtml(METADATA_GENERIC_NAME); ?> what's new site feed</a></p> -->
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>