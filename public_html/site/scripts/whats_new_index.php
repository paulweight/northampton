<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduConstants.php");
	include_once("websections/JaduNews.php");
	include_once("websections/JaduEvents.php");
	include_once("websections/JaduDownloads.php");
	include_once("websections/JaduDocuments.php");
	include_once("egov/JaduXFormsForm.php");

	include("../includes/lib.php");

	define(MAX_WHATS_NEW, 10);

	$news = getAllNewsByDate(true, true);
	$events = getNumEvents(MAX_WHATS_NEW);
	$downloads = getXMostRecentlyCreatedDownloadFiles(MAX_WHATS_NEW);
	$documents = getXMostRecentlyCreatedDocuments(MAX_WHATS_NEW, true, true);
	$forms = getXMostRecentlyCreatedXFormsForms(MAX_WHATS_NEW);

	$breadcrumb = 'whatsNew';	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>What&#39;s new on site | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="whats new, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="The latest news, documents, jobs, events and files to be added to the <?php print METADATA_GENERIC_COUNCIL_NAME;?> web site" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - whats new on the site" />
	<meta name="DC.description" lang="en" content="The latest news, documents, jobs, events and files to be added to the <?php print METADATA_GENERIC_COUNCIL_NAME;?> web site" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
	<p class="first">Check here to see the most recent information published on site.</p>
	
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
			$sections[] = "Online forms";
		}

		$sectionsList = splitArray($sections);
?>
		<div class="cate_info">
			<ul class="info_left">
<?php
			foreach ($sectionsList['left'] as $section) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/whats_new_index.php#<?php print $section; ?>"><?php print $section; ?></a></li>
<?php
			}
?>
			</ul>
			<ul class="info_right">
<?php
			foreach ($sectionsList['right'] as $section) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/whats_new_index.php#<?php print $section; ?>"><?php print $section; ?></a></li>
<?php
			}
?>
			</ul>
		</div>

<?php 
		if (sizeof($news) > 0) {
?>
		<!-- most recent news -->
	<div class="cate_info">
		<h2 id="News">Latest news</h2>
		<ul>
<?php 
		for ($i = 0; $i < MAX_WHATS_NEW; $i++) {
			$item = $news[$i];
			if ($item != null) {
				$item->title = htmlentities($item->title);
?>
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/news_article.php?newsID=<?php print $item->id;?>"><?php print $item->title;?></a> <span class="small">- <?php print date("jS F y", $item->newsDate);?></span></li>
<?php
			}
		}
?>
		</ul>
	</div>
		<!-- END most recent news -->
<?php
		}

		if (sizeof($downloads) > 0) {
?>
		<!-- most recent downloads -->
	<div class="cate_info">
		<h2 id="Downloads">Downloads</h2>
		<ul>
<?php 
		for ($i = 0; $i < MAX_WHATS_NEW; $i++) {
			$item = $downloads[$i];
			
			if ($item != null) {
?>
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/download_info.php?fileID=<?php print $item->id;?>"><?php print htmlentities($item->title);?></a> <span class="small">- <?php print date("jS F y", $item->creationDate);?></span></li>
<?php
			}
		}
?>
		</ul>
	</div>
			<!-- END most recent downloads -->
<?php
		}

		if (sizeof($documents) > 0) {
?>
			<!-- most recent documents -->
	<div class="cate_info">
		<h2 id="Documents">Documents and information</h2>
		<ul>
<?php 
		foreach ($documents as $document) {
			
			$header = getDocumentHeader($document->headerOriginalID, true);			
			
			if ($document != null) {
?>
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/documents_info.php?documentID=<?php print $document->id;?>"><?php print htmlentities($header->title);?></a> <span class="small">- <?php print date("jS F y", $document->enterDate);?></span></li>
<?php
			}
		}
?>
		</ul>
	</div>
		<!-- END most recent documents -->
<?php
		}
?>
	
<?php 
		if (sizeof($events) > 0) {
?>
		<!-- most recent events -->
	<div class="cate_info">
		<h2 id="Events">Events</h2>
		<ul>
<?php 
		for ($i = 0; $i < MAX_WHATS_NEW; $i++) {
			$item = $events[$i];
			
			if ($item != null) {
?>
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/events_info.php?eventID=<?php print $item->id;?>"><?php print htmlentities($item->title);?></a> <span class="small">- <?php print date("jS F y", $item->dateCreated);?></span></li>
<?php
			}
		}
?>
		</ul>
	</div>
		<!-- END most recent events -->
<?php
		}

		if (sizeof($forms) > 0) {
?>
		<!-- most recent online forms -->
	<div class="cate_info">
		<h2 id="Forms">Online forms</h2>
		<ul>
<?php 
		for ($i = 0; $i < MAX_WHATS_NEW; $i++) {
			$item = $forms[$i];
	
			if ($item != null) {
?>
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/xforms_form.php?formID=<?php print $item->id;?>"><?php print htmlentities($item->title);?></a> <span class="small">- <?php print date("jS F y", $item->enterDate);?></span></li>
<?php
			}
		}
?>
		</ul>
	</div>
		<!-- END most recent online forms -->
<?php
		}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
				
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
