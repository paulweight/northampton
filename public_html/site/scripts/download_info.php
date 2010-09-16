<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	
	include_once("websections/JaduDownloads.php");
	include_once("egov/JaduCL.php");
	include_once("JaduUpload.php");
	
	include_once("utilities/JaduMostPopular.php");

	if (isset($_SESSION['userID'])) {
		$user = getUser($_SESSION['userID']);
	}
	
	$showDownload = false;
	if (isset($_GET['fileID']) && is_numeric($_GET['fileID'])) {
		$fileID = $_GET['fileID'];
		$file = getDownloadFile($fileID);

		if ($file->id < 1) {
			header("Location: downloads_index.php");
			exit;
		}

		$download = getDownload($file->downloadID);

		$downloadID = $download->id;
		if ($file->url == "") {
			$filename = "http://" . $DOMAIN . "/downloads/" . $file->filename;
			$extension = $file->getFilenameExtension();
		}
		else {
			$filename = $file->url;
			$extension = $file->getURLExtension();
		}
		downloadFileRequestMade($fileID);
		$showDownload = true;
	}
	else if (isset($_GET['downloadID'])) {
		$downloadID = $_GET['downloadID'];
		$download = getDownload($downloadID);
		$allFiles = getAllDownloadFilesForDownload($downloadID);
	}

	if($download->id =='') {
		$download->id = '-1';
	}

	$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$categoryID = getFirstCategoryIDForItemOfType (DOWNLOADS_CATEGORIES_TABLE, $downloadID, BESPOKE_CATEGORY_LIST_NAME);	
	$currentCategory = $lgclList->getCategory($categoryID);
	$dirTree = $lgclList->getFullPath($categoryID);

	// most popular
	if (strpos($_SERVER['HTTP_REFERER'], 'google_results.php') !== false && isset($_GET['downloadID']) || isset($_GET['fileID'])) {

		$url = '/site/scripts/download_info.php?downloadID=' . $download->id;

		$mostPopularItem = getMostPopularItem ('url', $url);

		if ($mostPopularItem->id != -1) {
			$mostPopularItem->hits++;
			updateMostPopularItem($mostPopularItem);
		}
		else {
			$mostPopularItem->hits = 1;
			$mostPopularItem->url = $url;
			$mostPopularItem->title = $download->title;

			newMostPopularItem($mostPopularItem);
		}
	}
	$breadcrumb = 'downloadInfo';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Downloads | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="downloads, download, documents, pdf, word, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> download - <?php print htmlentities($download->title);?><?php foreach ($dirTree as $parent) { ?> | <?php print $parent->name; ?><? } ?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> download - <?php print htmlentities($download->title);?><?php foreach ($dirTree as $parent) { ?> | <?php print $parent->name; ?><? } ?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> download - <?php print htmlentities($download->title);?><?php foreach ($dirTree as $parent) { ?> | <?php print $parent->name; ?><? } ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
 	
<?php
	if ($download->id == '-1') {
		print '<h2>Sorry, this download is not available.</h2>';
	}
	else if ($showDownload) {
		if ($file->url == "") {
			$extension = $file->getFilenameExtension();
			$filename = "http://" . $DOMAIN . "/downloads/" . $file->filename;
		}
		else {
			$filename = $file->url;
			$extension = $file->getURLExtension();
		}
		
		$filename = htmlentities($filename, ENT_QUOTES, 'UTF-8');
?>
		<h2><?php print htmlentities($file->title, ENT_QUOTES, 'UTF-8');?></h2>
		<h3 class="downloadNow"><a href="<?php print $filename;?>">Download now</a></h3>
<?php
		if (!empty($download->description)) {
?>
		<p class="first"><?php print nl2br(htmlentities($download->description, ENT_QUOTES, 'UTF-8')); ?></p>
<?php
		}
?>
		<ul class="download_box">
			<li>Size: <?php print $file->getHumanReadableSize();?></li>
			<li>Extension: <?php print $extension;?> <img src="http://<?php print $DOMAIN; ?>/site/images/file_type_icons/<?php print $extension;?>.gif" alt="<?php print $extension;?> icon" /></li>
			<li>Estimated download time: <?php print $file->getDownloadTime56k();?></li>
			<li>Number of times viewed: <?php print $file->requests;?></li>
		</ul>

<?php
	} 
	else {

		if (sizeof($allFiles) > 0) {
?>
		<p><?php print nl2br($download->description); ?></p>

		
		<ul class="download_box">
<?php
			foreach ($allFiles as $fileItem) {
				if ($fileItem->url == "") {
					$extension = $fileItem->getFilenameExtension();
					$path = "http://". $DOMAIN . "/site/scripts/download_info.php?downloadID=".$download->id."&amp;fileID=".$fileItem->id;
				}
				else {
					$extension = $fileItem->getURLExtension();
					$path = $fileItem->url;
				}
				$fileItem->title = htmlentities($fileItem->title, ENT_QUOTES, 'UTF-8');
?>
            <li><a href="<?php print $path;?>"><?php print $fileItem->title;?></a></li>
<?php
	if ($extension != '') {
?>
            <li><img src="http://<?php print $DOMAIN;?>/site/images/file_type_icons/<?php print $extension;?>.gif" alt="<?php print $extension;?>" >&nbsp;(<?php print $extension;?>)</li>
<?php
	}
?>
            <li>Size: <?php print $fileItem->getHumanReadableSize();?></li>
            <li>Estimated download time: <?php print $fileItem->getDownloadTime56k();?></li>

<?php
			}
?>
		</ul>

<?php
		}
	}
?> 

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

					
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
