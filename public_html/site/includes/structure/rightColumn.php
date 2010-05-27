<div id="Rightcolumn">
	<div class="Rightrelated">

	<!-- For events scripts -->
<?php 
	$calendar_pages = array('events_index.php','events_info.php','events.php');

	if(in_array(basename($_SERVER['PHP_SELF']),$calendar_pages)) {
?>
		<!-- Calendar panel -->
	<div id="calendarcontainer">
		<?php include($HOME . "site/includes/calendar.php"); ?>
	</div>
<?php
	}
?>


	<!-- For news scripts -->
<?php 
	$news_pages = array('news_index.php','news_article.php','news_category.php', 'view_feeds.php', 'news_archive.php', 'press_index.php', 'press_category.php', 'press_archive.php', 'press_article.php');

	if(in_array(basename($_SERVER['PHP_SELF']),$news_pages)) {
?>
	<ul class="newsLinks">
		<li><h2>More news</h2></li>
		<li class="bllt"><a href="http://<?php print $DOMAIN;?>/site/scripts/news_index.php">Latest news</a></li>
		<li class="bllt"><a href="http://<?php print $DOMAIN; ?>/site/scripts/news_archive.php">News archive</a></li>
		<li class="rssfeed"><a href="http://<?php print $DOMAIN;?>/site/scripts/rss.php" target="_blank">News RSS</a></li>
		<!--<li class="bllt"><a href="http://<?php print $DOMAIN;?>/site/scripts/press_index.php">Press releases</a></li>
		<li class="bllt"><a href="http://<?php print $DOMAIN; ?>/site/scripts/press_archive.php">Press release archive</a></li>
		<li class="rssfeed"><a href="http://<?php print $DOMAIN;?>/site/scripts/pressRss.php" target="_blank">Press release RSS</a></li>-->
	</ul>
	
<?php 	
		$allRSSItems = getAllRSSItemsForNews();
		if (!empty($allRSSItems)) {
?>
	<ul class="newsLinks">
	<li><h2>News feeds</h2></li>
<?php
		foreach ($allRSSItems as $RSSItem) {
			print '<li class="bllt"><a href="http://'.$DOMAIN.'/site/scripts/view_feeds.php?view=feed&amp;feedID='.$RSSItem->id.'">'.$RSSItem->name.'</a></li>';
		}
?>
	</ul>
		
<?php
		}
?>
	
	
<?php
	}
?>



	<!-- Related information -->
	<?php include("../includes/related_info.php"); ?>

	</div>

<?php
		
	//Right-hand Supplements -->
		// get right-hand supplements 
		if (isset($page->id) || isset($homepage->id)) {
			if (isset($page->id)) {
				$leftSupplements = getAllPageSupplements('', $page->id, '', 'Right');
			}
			elseif (isset($homepage->id)) {
				$leftSupplements = getAllPageSupplements('', '', $homepage->id, 'Right');
			}

			// loop through each supplement
			foreach ($leftSupplements as $supplement) {
				// include supplement front-end code
				$publicCode = getSupplementPublicCode($supplement->supplementWidgetID, $supplement->locationOnPage);
				$supplementWidget = getPageSupplementWidget($supplement->supplementWidgetID);

				include_once($supplementWidget->classFile);

				$record = new $supplementWidget->className;
				$record->id = $supplement->supplementRecordID;
				$record->get();
				include($HOME . '/site/includes/supplements/' . $publicCode->code);
			}
		}
?>
	<!-- End right-hand supplements -->

</div>
