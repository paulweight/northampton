<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	
	include_once("egov/JaduEGovMeetingMinutes.php");
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");

	include_once("../includes/lib.php");

	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
		$allMeetingMinutes = getAllMeetingMinutesWithCategory ($_GET['categoryID'], true);
		
		//	Category Links
		$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		$allCategories = $lgclList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, MEETING_MINUTES_APPLIED_CATEGORIES_TABLE, true);
		splitArray($categories);
		
		//	Category Links
		$currentCategory = $lgclList->getCategory($_GET['categoryID']);
		$dirTree = $lgclList->getFullPath($_GET['categoryID']);
	}
	else {
		header("Location: meetings_index.php");
		exit;
	}

	$breadcrumb = 'meetingsCats';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Agendas, reports and minutes | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print $parent->name . ', '; } ?><?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s directory of Agendas, Reports and Minutes" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Agendas, Reports and Minutes" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s directory of Agendas, Reports and Minutes" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
<?php
		if (sizeof($splitArray['left']) > 0 || sizeof($splitArray['right']) > 0) {
?>
		<div class="cate_info">
			<h2>Categories in <?php print $parent->name; ?></h2>
<?php 
			if (sizeof($splitArray['left']) > 0) {
				print '<ul class="info_left">';
				foreach ($splitArray['left'] as $subCat) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/meetings.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a> </li>
<?php
				}
				print '</ul>';
			}
			
			if (sizeof($splitArray['right']) > 0) {
				print '<ul class="info_right">';
				foreach ($splitArray['right'] as $subCat) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/meetings.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a></li>
<?php
				}
				print '</ul>';
?>
		</div>		
<?php
		}
	}

		if (sizeof($allMeetingMinutes) > 0) {
?>
		<div class="cate_info">			
			<h2><?php print $currentCategory->name;?> meetings</h2>
			<ul>
<?php
				foreach ($allMeetingMinutes as $meeting) {
					$header = getMeetingMinutesHeader($meeting->headerID);
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/meetings_info.php?categoryID=<?php print $_GET['categoryID'];?>&amp;meetingID=<?php print $meeting->id;?>"><?php print $header->title;?></a> <span class="note">- <?php print $meeting->getMeetingMinutesDateFormatted('l jS F Y');?></span></li>
<?php
				}
?>
			</ul>	
		</div>
<?php
		}
?>					 
		
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>