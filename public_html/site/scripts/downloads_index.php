<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");
	include_once("websections/JaduDownloads.php");

	include("../includes/lib.php");

	$topDownloads = getTopXDownloadFiles (10); // no need for live as has no flag
	
	$breadcrumb = 'downloadsIndex';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Document and file downloads | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="downloads, download, documents, pdf, word, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Files and documents available for download" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Document and File Downloads" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Files and documents available for download" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<p class="first">The following documents are available for download. Documents in PDF format can be read using <a href="http://www.adobe.com/products/acrobat/readstep2.html">Adobe Reader</a>.</p>

<?php

	if (sizeof($topDownloads) > 0) {
?>
	<div class="divBox">
		<!-- The ten most used downloads listed here. -->
		<h2>Frequently requested downloads</h2>
		<ol class="list">
<?php 
		foreach($topDownloads as $item) {
			if ($item->url == "") {
				 $extension = $item->getFilenameExtension();
			}
			else {
				 $extension = $item->getURLExtension();
			}
?>
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/download_info.php?fileID=<?php print $item->id;?>"><?php print htmlentities($item->title);?></a> <img src="http://<?php print $DOMAIN; ?>/site/images/file_type_icons/<?=$extension;?>.gif" alt="<?php print $extension;?>" /> <span class="small">(<?php print $extension;?>)</span></li>
<?php
		}
?>
		</ol>
		 <!-- END of most used downloads -->
</div>
<?php
	}
?>

	<h2>Downloads by category</h2>

<?php
	//	must do here to ensure not using left nav version.
	
	$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$allRootCategories = $lgclList->getTopLevelCategories();
	$rootCategories = filterCategoriesInUse($allRootCategories, DOWNLOADS_APPLIED_CATEGORIES_TABLE, true);

	foreach ($rootCategories as $rootCat) {
		$subCats = filterCategoriesInUse($lgclList->getChildCategories($rootCat->id), DOWNLOADS_APPLIED_CATEGORIES_TABLE, true);
		$splitArray = splitArray($subCats);
?>	
                    
		<div class="cate_info">
			<h2><a href="http://<?php print $DOMAIN; ?>/site/scripts/downloads.php?categoryID=<?php print $rootCat->id;?>"><?php print $rootCat->name; ?></a></h2>                        
<?php
		if (sizeof($splitArray['left']) > 0) {
			print '<ul class="info_left list">';
			foreach ($splitArray['left'] as $subCat) {
?>
			 <li><a href="http://<?php print $DOMAIN; ?>/site/scripts/downloads.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a></li>
<?php
			}
			print '</ul>';
		}

		if (sizeof($splitArray['right']) > 0) {
			print '<ul class="info_right list">';
			foreach ($splitArray['right'] as $subCat) {
?>
			 <li><a href="http://<?php print $DOMAIN; ?>/site/scripts/downloads.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a></li>
<?php
			}
			print '</ul>';
		}
?>
			<div class="clear"></div>
		</div>
<?php
	}
?>
 

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>