<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="news, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />	
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> news feed directory" />

	<meta name="DC.title" lang="en" content="<?php print $title;?>" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>s latest news directory" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->	

<?php
	if ($viewFeed) {
?>
	<h2><?php print encodeHtml($feed->name); ?></h2>
	<ul class="archive">
<?php
    if (isset($parsedFeed) && $parsedFeed !== false) {
    	$i = 1;
        if ($parsedFeed instanceof RSSChannel) {
            foreach ($parsedFeed->items as $item) {
                if ($feed->noItems != 0 && $i > $feed->noItems) {
                    break;
                }
?>

		<li>
			<h3><a href="<?php print encodeHtml($item->link); ?>"><?php print encodeHtml(strip_tags($item->title)); ?></a></h3>
<?php
	if ($item->pubDate != null) {
?>
			 <p class="date"><?php print formatDateTime(FORMAT_DATE_FULL, $item->pubDate); ?></p>
<?php
	}
?>
			<p><?php print encodeHtml(strip_tags($item->description)); ?></p>
		</li>
<?php
                $i++;
            }
        }
        else if ($parsedFeed instanceof AtomFeed) {
            foreach ($parsedFeed->entries as $entry) {
                if ($feed->noItems != 0 && $i > $feed->noItems) {
                    break;
                }
?>
		<li>
			<h3><a href="<?php print encodeHtml($entry->link); ?>"><?php print encodeHtml(strip_tags($entry->title)); ?></a></h3>
			<p class="date"><?php print formatDateTime(FORMAT_DATE_FULL, $entry->updated); ?></p>
			<p><?php print encodeHtml(strip_tags($entry->content)); ?></p>
		</li>

<?php
                $i++;
            }
        }
    }
?>
	</ul>
<?php
}
else {
    if (sizeof($allFeeds) > 0) {
?>

	<p>Choose a feed from below.</p>
	<ul  class="list icons news">
<?php
		foreach ($allFeeds as $feed) {
			print '<li><a href="' . getSiteRootURL() . buildFeedsURL($feed->id) . '">' . encodeHtml($feed->name) . '</a></li>';
		}
?>
	</ul>

<?php

	}
	else {
?>
	<p>Sorry, there are currently no external news feeds</p>
<?php	
	}
}	
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
