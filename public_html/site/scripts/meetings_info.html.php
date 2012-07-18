<?php
include('../../404.php');
exit;
?>

<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="committee, meetings, minutes, agendas, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s directory of Agendas, Reports and Minutes" />

	<?php printMetadata(MEETING_MINUTES_METADATA_TABLE, MEETING_MINUTES_CATEGORIES_TABLE, $meeting->id, $header->title, "http://".DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
		
<?php
	if ($meeting === -1) {
?>
	<h2>Sorry, this meeting is no longer available</h2>
<?php
	}
	else {
?>
	<h2><?php print encodeHtml($header->title); ?></h2>
	<p class="date">Date: <?php print $meeting->getMeetingMinutesDateFormatted(FORMAT_DATE_FULL); ?></p>

<?php
	if (sizeof($attachmentList) < 1) {
?>
	<h3>There are currently no attachments for this meeting</h3>
<?php 
	}
	else {
		foreach ($typedAttachments as $index => $typeArray) {
			$attachmentType = getMeetingMinutesType($index);
?>
	<ul class="list icons meetings">
			
<?php
			foreach ($typeArray as $download) {
?>
		<li><a href="<?php if ($download->contentType == "file") print getSiteRootURL() . buildDownloadsURL(-1, $download->id, -1, true, 'meetings'); else print buildMeetingsURL(-1, 'meeting', $_GET['meetingID'], $download->id); ?>"><?php print encodeHtml($download->title); ?></a> <?php if ($download->contentType == "file" && $download->size > 0) print " (Download " . encodeHtml($attachmentType->type) . ". ".$download->getHumanReadableSize().")";?></li>
<?php
			}
?>			
	</ul>
<?php
		}
?>
				
	<!-- Meeting Attachment -->
<?php
			if (isset($_GET['attachmentID']) && is_numeric($_GET['attachmentID']) && $attachmentDetails !== -1) {
				if ($attachmentDetails->contentType == "manual") {
					$t = getMeetingMinutesType($attachmentDetails->typeID);
					$typeName = $t->type;
					$displayAnchor = true;
?>
	
	<h2><?php print encodeHtml($typeName); ?>: <?php print encodeHtml($attachmentDetails->title); ?></h2>
	<div class="byEditor article">
		<?php print $attachmentDetails->content; ?>
	</div>
<?php
				}
			}
		}
	}
?>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>