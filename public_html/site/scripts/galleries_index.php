<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");
	include_once("multimedia/JaduMultimediaGalleries.php");

	include("../includes/lib.php");
    
	$breadcrumb = 'galleriesIndex';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Multimedia galleries | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

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
<?php
	$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$allRootCategories = $lgclList->getTopLevelCategories();
	$rootCategories = filterCategoriesInUse($allRootCategories, MULTIMEDIA_GALLERY_APPLIED_CATEGORIES_TABLE, true);

	foreach ($rootCategories as $rootCat) {
		$subCats = filterCategoriesInUse($lgclList->getChildCategories($rootCat->id), MULTIMEDIA_GALLERY_APPLIED_CATEGORIES_TABLE, true);
		$splitArray = splitArray($subCats);
?>	
                    
		<div class="cate_info">
			<h2><a href="http://<?php print $DOMAIN; ?>/site/scripts/galleries.php?categoryID=<?php print $rootCat->id;?>"><?php print $rootCat->name; ?></a></h2>                        
<?php
		if (sizeof($splitArray['left']) > 0) {
?>
			<ul class="info_left list">
<?php
			foreach ($splitArray['left'] as $subCat) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/galleries.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a></li>
<?php
			}
?>
			</ul>
<?php
		}

		if (sizeof($splitArray['right']) > 0) {
?>
			<ul class="info_right list">
<?php
			foreach ($splitArray['right'] as $subCat) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/galleries.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a></li>
<?php
			}
?>
			</ul>
<?php
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