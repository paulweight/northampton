<?php
include('../../404.php');
exit;
?>

<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print encodeHtml($parent->name) . ', '; } ?><?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s directory of Agendas, Reports and Minutes" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Agendas, Reports and Minutes" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s directory of Agendas, Reports and Minutes" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
<?php
	
		if (sizeof($allMeetingMinutes) > 0) {
?>		
			<h2><?php print encodeHtml($currentCategory->name);?> meetings</h2>
			<ul class="list icons meetings">
<?php
				foreach ($allMeetingMinutes as $meeting) {
					$header = getMeetingMinutesHeader($meeting->headerID);
?>
				<li><a href="<?php print getSiteRootURL() . buildMeetingsURL($_GET['categoryID'], 'meeting', $meeting->id) ?>"><?php print encodeHtml($header->title); ?></a> <span class="small">- <?php print $meeting->getMeetingMinutesDateFormatted(FORMAT_DATE_FULL);?></span></li>
<?php
				}
?>
			</ul>	
<?php
		}
		if (sizeof($categories) > 0) {
?>
		<div class="cate_info">
			<h3>Categories in <?php print encodeHtml($parent->name); ?></h3>
<?php 
			if (sizeof($categories) > 0) {
				print '<ul class="list icons meetings">';
				foreach ($categories as $subCat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildMeetingsURL($subCat->id); ?>"><?php print encodeHtml($subCat->name); ?></a> </li>
<?php
				}
				print '</ul>';
			}
?>
		</div>		

<?php
	}
?>	
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>