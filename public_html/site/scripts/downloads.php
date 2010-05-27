<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("websections/JaduDownloads.php");
	include_once("egov/JaduCL.php");

	include("../includes/lib.php");

	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
		$allDownloads = getAllDownloadsWithCategory ($_GET['categoryID'], true);
		
		//	Category Links
		$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		$allCategories = $lgclList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, DOWNLOADS_APPLIED_CATEGORIES_TABLE, true);
		$splitArray = splitArray($categories);	
		
		//	Category Links
		$currentCategory = $lgclList->getCategory($_GET['categoryID']);
		$dirTree = $lgclList->getFullPath($_GET['categoryID']);		
	}
	else {
		$dirTree = array();
	}
	
	$breadcrumb = 'downloadCats';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $currentCategory->name; ?> downloads | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print $parent->name . ', '; } ?><?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s Files and documents available for download within the following categories<?php foreach ($dirTree as $parent) { print ', ' . $parent->name; } ?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> downloads<?php foreach ($dirTree as $parent) { ?> | <?php print $parent->name; } ?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s Files and documents available for download within the following categories<?php foreach ($dirTree as $parent) { print ', ' . $parent->name; } ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
	
	<p class="first" >The following documents are available for download. Documents in PDF format can be read using <a href="http://www.adobe.com/products/acrobat/readstep2.html">Adobe Reader</a>.</p>
			
<?php
	if (sizeof($splitArray['left']) > 0 || sizeof($splitArray['right']) > 0) {
?>

		<div class="cate_info">
			<h2>More downloads in <?php print $parent->name; ?></h2>
<?php
		if (sizeof($splitArray['left']) > 0) {
?>
			<ul class="info_left list">
<?php
			foreach ($splitArray['left'] as $subCat) {
?>
				<li><a href="http://<?php print $DOMAIN ?>/site/scripts/downloads.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a> </li>
<?php
			}
			print '</ul>';
		}

		if (sizeof($splitArray['right']) > 0) {
			print '<ul class="info_right list">';
			foreach ($splitArray['right'] as $subCat) {
?>
				<li><a href="http://<?php print $DOMAIN ?>/site/scripts/downloads.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a> </li>
<?php
			}
			print '</ul>';
		}
?>
				<div class="clear"></div>
			</div>
<?php
	}

	if (sizeof($allDownloads) > 0) {
?>
		<h2>Available downloads</h2>
<?php
		foreach ($allDownloads as $downloadItem) {

			$allFiles = getAllDownloadFilesForDownload($downloadItem->id);

			if (sizeof($allFiles) > 0) {
				$downloadItem->title = htmlentities($downloadItem->title);
				$downloadItem->description = htmlentities($downloadItem->description);
?>
				<div class="download_box">
					<h3><?php print $downloadItem->title ;?></h3>
<p>
<?php
	
				print nl2br($downloadItem->description);
?>
</p>
<?php
				print "<div><ul>";
				foreach ($allFiles as $fileItem) {
					if ($fileItem->url == "") {
						$extension = $fileItem->getFilenameExtension();
						$filename = "http://" . $DOMAIN . "/downloads/" . $fileItem->filename;
					}
					else {
						$filename = $fileItem->url;
						$extension = $fileItem->getURLExtension();
					}
					$fileItem->title = htmlentities($fileItem->title);
?>
					<li>
						<ul>
							<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/download_info.php?fileID=<?php print htmlentities($fileItem->id);?>"><?php print $fileItem->title;?></a>
							<img src="http://<?php print $DOMAIN; ?>/site/images/file_type_icons/<?php print $extension;?>.gif" alt="<?php print $extension;?>" />&nbsp;(<?php print $extension;?>)</li>
							<li>Size: <?php print $fileItem->getHumanReadableSize();?></li>
							<li>Estimated download time: <?php print $fileItem->getDownloadTime56k();?></li>
						</ul>
					</li>
<?php
				}
					print "</ul></div>
				</div>";
			}

		}
	}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
