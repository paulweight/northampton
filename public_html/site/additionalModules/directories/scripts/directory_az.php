<?php
	session_start();
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");
	include_once("JaduImages.php");
	include_once("directoryBuilder/JaduDirectories.php");
	include_once("directoryBuilder/JaduDirectoryCategoryInformation.php");
	
	if (!isset($_REQUEST['directoryID']) || !is_numeric($_REQUEST['directoryID'])) {
	    header("Location: http://$DOMAIN/site/index.php");
        exit();
    }

    $directory = getDirectory($_REQUEST['directoryID']);

    if (isset($_REQUEST['startsWith'])) {
		$entryStartsWith = strtoupper($_REQUEST['startsWith']);
	}
	else {
		$entryStartsWith = 'A';
	}

	if (isset($_REQUEST['categoryInformationID']) && is_numeric($_REQUEST['categoryInformationID'])) {
		$categoryInfo = getDirectoryCategoryInformation($_REQUEST['categoryInformationID']);
        $category = getDirectoryCategory($categoryInfo->categoryID);
		$directory = getDirectory($category->directoryID);

		$records = getAllDirectoryEntriesUnderCategory ($directory->id, $categoryInfo->id, $entryStartsWith, true);
	}
	else {
	    $records = getAllDirectoryEntriesUnderCategory ($directory->id, -1, $entryStartsWith, true);
	}

	$breadcrumb = "directoryAZ";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - <?php print $directory->name; ?> - A to Z of records</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="<?php print $directory->name;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (isset($categoryInfo) && $categoryInfo->id != '-1') {
?>
    <a id="brand" href="http://<?php print $DOMAIN; ?>/site/scripts/directory_category.php?directoryCategoryID=<?php print $_REQUEST['categoryID']; ?>">
	    <img src="http://<?php print $DOMAIN . '/images/' . $categoryInfo->imageFilename; ?>" alt="<?php print getImageProperty($categoryInfo->imageFilename, 'altText'); ?> " />
	</a>
<?php
	}
?>

		<!-- A-Z top list-->
		<div id="az_index">
			<ul>
<?php
            $letters = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", 
                             "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
            foreach ($letters as $letter) {
?>
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/directory_az.php?directoryID=<?php print $directory->id; if ($categoryInfo->id != -1) print '&categoryInfoID=' . $categoryInfo->id ?>&amp;startsWith=<?php print $letter; ?>"><?php print $letter; ?></a></li>
<?php
            }
?>
			</ul>
			<div class="clear"></div>
		</div>
		<!-- End A-Z top list -->

<?php
	if(sizeof($records) > 0) {
?>
	<div class="display_box">
		<h2>Records starting with <?php print $entryStartsWith; ?></h2>
	
		<ul class="list">
<?php 
		foreach ($records as $record) {
?>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/directory_record.php?recordID=<?php print $record->id; if ($categoryInfo->id != -1) print '&categoryInfoID=' . $categoryInfo->id ?>"><?php print htmlentities($record->title); ?></a></li>
<?php
		}
?>
		</ul>
	</div>
<?php
	}
	else {
?>
	<h2>Sorry, no records found starting with <?php print $entryStartsWith; ?>.</h2>
<?php
	}
?>
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php");?>