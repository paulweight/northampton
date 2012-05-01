<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

<?php
	include_once("../includes/stylesheets.php");
	include_once("../includes/metadata.php");
	
	$metadata = getMetadataForItem (MULTIMEDIA_PODCAST_METADATA_TABLE, $podcast->id);
	if ($metadata->subject != '') {
		$metadata->subject .= ',';
	}
	$metadata->subject .= METADATA_GENERIC_KEYWORDS;
	if ($metadata->description == '') {
		$metadata->description = METADATA_GENERIC_NAME . '\'s ' . $podcast->title . ' podcast';
		foreach ($dirTree as $parent) {
			$metadata->description .= ' | ' . $parent->name;
		}
	}
?>
	
	<meta name="Keywords" content="<?php print encodeHtml($metadata->subject); ?>,podcast" />
	<meta name="Description" content="<?php print encodeHtml($episode->title);?> - <?php print encodeHtml($metadata->description); ?>" />

	<?php printMetadata(MULTIMEDIA_PODCAST_METADATA_TABLE, MULTIMEDIA_PODCAST_CATEGORIES_TABLE, $podcast->id, $podcast->title, "http://".DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>	
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
<?php 
if ($podcast->id < 1) {
?>
	<h2>Sorry, this podcast is no longer available</h2>
<?php 
}
else if ($episode->id < 1) { 
?>
	<h2>Sorry, this podcast episode is no longer available</h2>		
<?php
}
else {
?>
    <h2><?php print encodeHtml($episode->title); ?></h2>
	
	<div id="podcast_multimedia">
<?php
	if ($item->isAudio()) { 
		print $item->renderMediaPlayer(300, false);
	}
	else if ($item->isVideo()) {
		print $item->renderMediaPlayer($maxSize, false);
	}
?></div>
		<p class="summary"><?php print encodeHtml($episode->summary); ?></p>
		<div class="byEditor">
			<?php print processEditorContent($episode->description); ?>
		
    </div>
	<ul class="list icons podcasts">
    
<?php
        if ($podcast->downloadable) {
?>
            <li class="long"><a href="<?php print getSiteRootURL() . $item->getDownloadFilename();  ?>"><strong>Download this episode</strong></a></li>
<?php
        }
?>
    		<li class="long">Running time is <strong><?php print secondsToTimecode($item->length); ?></strong>.</li>
<?php
            if ($podcast->downloadable) {
?>
            <li class="long">File size is <strong><?php print humanReadableFilesize($item->filesize); ?></strong>.</li>
<?php
            }
?>
			<li class="long">Published on <strong><?php print formatDateTime(FORMAT_DATE_SHORT, $episode->dateCreated);?></strong>.</li>
	</ul>
	
	
<?php
	if ($podcast->downloadable) {
?>
	<ul class="bottomList">
		<li><a href="itpc://<?php print DOMAIN . buildRSSURL('podcasts', $podcast->id); ?>">iTunes</a></li>	
		<li><a href="zcast://<?php print DOMAIN . buildRSSURL('podcasts', $podcast->id); ?>">ZENCast</a></li>   
		<li><a href="zune://subscribe/?<?php print encodeHtml($podcast->title); ?>=<?php print DOMAIN . buildRSSURL('podcasts', $podcast->id); ?>">Zune</a></li>   
	</ul>
	<div class="clear"></div>
		<p><a class="rss" href="<?php print getSiteRootURL() . buildRSSURL('podcasts', $podcast->id); ?>"> RSS</a></p>
	
<?php
    }
}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>